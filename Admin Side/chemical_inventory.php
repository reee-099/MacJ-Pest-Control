<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "macj_pest_control";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error = "";
    if (isset($_POST['add']) || isset($_POST['update'])) {
        $quantity_ml = $_POST['quantity'];
        // Validate ml input
        if ($quantity_ml < 0 || $quantity_ml > 1000) {
            $_SESSION['error'] = "Quantity must be between 0 and 1000 ml.";
            header("Location: chemical_inventory.php");
            exit;
            }
        $quantity_liters = $quantity_ml / 1000;
    }

    if (isset($_POST['add'])) {
        $name = $conn->real_escape_string($_POST['chemical_name']);
        $type = $conn->real_escape_string($_POST['type']);
        $conn->query("INSERT INTO chemical_inventory (chemical_name, type, quantity) VALUES ('$name', '$type', $quantity_liters)");
    } elseif (isset($_POST['update'])) {
        $original_name = $conn->real_escape_string($_POST['original_chemical_name']);
        $name = $conn->real_escape_string($_POST['chemical_name']);
        $type = $conn->real_escape_string($_POST['type']);
        $conn->query("UPDATE chemical_inventory SET chemical_name='$name', type='$type', quantity=$quantity_liters WHERE chemical_name='$original_name'");
    } elseif (isset($_POST['delete'])) {
        $name = $conn->real_escape_string($_POST['chemical_name']);
        
        // Archive before deleting
        $conn->query("INSERT INTO chemical_archive 
                     SELECT chemical_name, type, quantity, NOW() 
                     FROM chemical_inventory 
                     WHERE chemical_name='$name'");
        
        $conn->query("DELETE FROM chemical_inventory WHERE chemical_name='$name'");
    } elseif (isset($_POST['recover'])) {
        $name = $conn->real_escape_string($_POST['recover_name']);
        $type = $conn->real_escape_string($_POST['recover_type']);
        $quantity = floatval($_POST['recover_quantity']);
        $deleted_at = $conn->real_escape_string($_POST['deleted_at']);
    
        // Start transaction
        $conn->begin_transaction();
    
        try {
            // Check for existing inventory entry
            $check = $conn->query("SELECT * FROM chemical_inventory WHERE chemical_name = '$name'");
            
            if ($check->num_rows > 0) {
                throw new Exception("$name already exists in inventory. Delete it first to recover archived version.");
            }
    
            // Insert into main inventory
            $conn->query("INSERT INTO chemical_inventory (chemical_name, type, quantity) 
                        VALUES ('$name', '$type', $quantity)");
    
            // Remove from archive
            $conn->query("DELETE FROM chemical_archive 
                         WHERE chemical_name = '$name' 
                         AND deleted_at = '$deleted_at'");
    
            $conn->commit();
            $_SESSION['error'] = "Chemical recovered successfully!";
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['error'] = $e->getMessage();
        }
        
        header("Location: chemical_inventory.php");
        exit;
    }
}

$result = $conn->query("SELECT * FROM chemical_inventory");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/sidebar.css">
        <title>Chemical Inventory</title>
    </head>
<body>
    <div class="sidebar">
        <h2>MacJ Pest<br>Control</h2>
        <a href="calendar.php">Calendar</a>
        <a href="reports_from_technicians.php">Reports</a>
        <a href="chemical_inventory.php" class="active">Chemical Inventory</a>
        <a href="technicians.php">Technicians</a>
        <a href="clients.php">Clients</a>
    </div>
    <div class="container">
        <h2 style="text-align: center; color: #007BFF;">Chemical Inventory</h2>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        <form method="POST">
            <input type="hidden" name="original_chemical_name" id="original_chemical_name">
            <div class="form-group">
                <input type="text" name="chemical_name" id="chemical_name" placeholder="Chemical Name" required>
                <input type="text" name="type" id="type" placeholder="Type" required>
                <input type="number" name="quantity" id="quantity" placeholder="Quantity (ml)" min="0" max="1000" required>
            </div>
            <button type="submit" name="add">Add</button>
            <button type="submit" name="update">Update</button>
        </form>
        <table>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Quantity (ml)</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['chemical_name'] ?></td>
                <td><?= $row['type'] ?></td>
                <td><?= (int)($row['quantity'] * 1000) ?></td>
                <td>
                    <button onclick="editChemical('<?= $row['chemical_name'] ?>', '<?= $row['chemical_name'] ?>', '<?= $row['type'] ?>', <?= (int)($row['quantity'] * 1000) ?>)">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="chemical_name" value="<?= $row['chemical_name'] ?>">
                        <button type="submit" name="delete" onclick="return confirmDelete()">Delete</button>                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <h3 style="margin-top: 40px;">Recently Deleted Chemicals (Archive)</h3>
        <table>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Quantity (ml)</th>
                <th>Deleted Date</th>
                <th>Actions</th>
            </tr>
            <?php 
            $archive_result = $conn->query("SELECT * FROM chemical_archive");
            while ($archive_row = $archive_result->fetch_assoc()): ?>
            <tr>
                <td><?= $archive_row['chemical_name'] ?></td>
                <td><?= $archive_row['type'] ?></td>
                <td><?= (int)($archive_row['quantity'] * 1000) ?></td>
                <td><?= $archive_row['deleted_at'] ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="recover_name" value="<?= $archive_row['chemical_name'] ?>">
                        <input type="hidden" name="recover_type" value="<?= $archive_row['type'] ?>">
                        <input type="hidden" name="recover_quantity" value="<?= $archive_row['quantity'] ?>">
                        <input type="hidden" name="deleted_at" value="<?= $archive_row['deleted_at'] ?>">
                        <button type="submit" name="recover" onclick="return confirm('Restore <?= $archive_row['chemical_name'] ?>?')"> Recover </button>
    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
    <script>
        function editChemical(originalName, name, type, quantityMl) {
            document.getElementById('original_chemical_name').value = originalName;
            document.getElementById('chemical_name').value = name;
            document.getElementById('type').value = type;
            document.getElementById('quantity').value = quantityMl;
        }
        function confirmDelete() {
            const firstConfirm = confirm("Are you sure you want to delete this chemical?");
            if (!firstConfirm) return false;
            const secondConfirm = confirm("This action is irreversible. Confirm again to proceed.");
            return secondConfirm;
        }
        function confirmRecovery(name) {
            return confirm(`Are you sure you want to restore ${name}?\n\nThis will: 
            - Add it back to main inventory
            - Remove from archive
            - Preserve original properties`);
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>
<style>
    .container {
        margin-left: 290px;
        padding: 30px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }

    .container form {
        background-color: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .form-group {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
    }

    .form-group input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }

    .container button[type="submit"] {
        padding: 10px 25px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 500;
        transition: 0.3s;
        background-color: #007BFF;
        color: white;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    th, td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    th {
        background-color: #007BFF;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr:hover {
        background-color: #e9ecef;
    }

    td button {
        padding: 6px 12px;
        margin: 2px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background-color: #007BFF;
        color: white;
    }
    .error {
        color: red;
        margin-bottom: 15px;
        text-align: center;
    }
</style>