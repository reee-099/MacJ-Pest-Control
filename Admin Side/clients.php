<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'macj_pest_control');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $firstName = $conn->real_escape_string($_POST['first_name']);
        $lastName = $conn->real_escape_string($_POST['last_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $contact = $conn->real_escape_string($_POST['contact']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        
        $conn->query("INSERT INTO clients (first_name, last_name, email, contact_number, password) 
                     VALUES ('$firstName', '$lastName', '$email', '$contact', '$password')");
    } elseif (isset($_POST['update'])) {
        $id = (int)$_POST['id'];
        $firstName = $conn->real_escape_string($_POST['first_name']);
        $lastName = $conn->real_escape_string($_POST['last_name']);
        $email = $conn->real_escape_string($_POST['email']);
        $contact = $conn->real_escape_string($_POST['contact']);
        $password = !empty($_POST['password']) 
                    ? password_hash($_POST['password'], PASSWORD_DEFAULT) 
                    : $_POST['old_password'];
        
        $conn->query("UPDATE clients SET 
                     first_name='$firstName', 
                     last_name='$lastName', 
                     email='$email', 
                     contact_number='$contact', 
                     password='$password' 
                     WHERE client_id=$id");
    } elseif (isset($_POST['restore'])) {
        $id = (int)$_POST['restore_id'];
        $result = $conn->query("SELECT * FROM archived_clients WHERE client_id=$id");
        if ($result->num_rows > 0) {
            $client = $result->fetch_assoc();
            $conn->query("INSERT INTO clients VALUES (
                {$client['client_id']}, 
                '{$client['first_name']}', 
                '{$client['last_name']}', 
                '{$client['email']}', 
                '{$client['contact_number']}', 
                '{$client['password']}', 
                '{$client['registered_at']}'
            )");
            $conn->query("DELETE FROM archived_clients WHERE client_id=$id");
        }
    }
} elseif (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Archive client
    $result = $conn->query("SELECT * FROM clients WHERE client_id=$id");
    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
        $conn->query("INSERT INTO archived_clients VALUES (
            {$client['client_id']}, 
            '{$client['first_name']}', 
            '{$client['last_name']}', 
            '{$client['email']}', 
            '{$client['contact_number']}', 
            '{$client['registered_at']}',
            NOW()
        )");
        $conn->query("DELETE FROM clients WHERE client_id=$id");
    }
}

$clients = $conn->query("SELECT * FROM clients ORDER BY client_id");
$archived = $conn->query("SELECT * FROM archived_clients ORDER BY deleted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>MacJ Pest Control - Clients</title>
    <style>
        /* Use same styles as technicians.html */
        .main-content { margin-left: 250px; padding: 20px; background-color: #f8f9fa; min-height: 100vh; }
        .card { background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e0e0e0; }
        th { background-color: #1e88e5; color: white; }
        .btn { padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; color: white; }
        .btn-primary { background-color: #1e88e5; }
        .btn-danger { background-color: #e53935; }
        .archive-section { margin-top: 40px; padding: 20px; background: #e3f2fd; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>MacJ Pest<br>Control</h2>
        <a href="calendar.php">Calendar</a>
        <a href="reports_from_technicians.php">Reports</a>
        <a href="chemical_inventory.php">Chemical Inventory</a>
        <a href="technicians.php">Technicians</a>
        <a href="clients.php" class="active">Clients</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h2 style="color: #1e88e5; margin-bottom: 20px;">Manage Clients</h2>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($client = $clients->fetch_assoc()): ?>
                    <tr>
                        <td><?= $client['client_id'] ?></td>
                        <td><?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['contact_number']) ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="openModal('edit', <?= $client['client_id'] ?>, '<?= $client['first_name'] ?>', '<?= $client['last_name'] ?>', '<?= $client['email'] ?>', '<?= $client['contact_number'] ?>')">Edit</button>
                            <a href="?delete=<?= $client['client_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="archive-section">
                <h3 style="color: #1e88e5; margin-bottom: 15px;">Archived Clients</h3>
                <?php while($arch = $archived->fetch_assoc()): ?>
                <div class="archive-item">
                    <div>
                        <span>ID: <?= $arch['client_id'] ?></span>
                        <span>Name: <?= htmlspecialchars($arch['first_name'].' '.$arch['last_name']) ?></span>
                        <span>Deleted: <?= date('M d, Y', strtotime($arch['deleted_at'])) ?></span>
                    </div>
                    <form method="POST">
                        <input type="hidden" name="restore_id" value="<?= $arch['client_id'] ?>">
                        <button type="submit" name="restore" class="btn btn-primary">Restore</button>
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="clientModal" class="modal">
        <div class="modal-content">
            <form method="POST">
                <h3 id="modalTitle"></h3>
                <input type="hidden" name="id" id="clientId">
                <input type="hidden" name="old_password" id="oldPassword">
                
                <div>
                    <label>First Name:</label>
                    <input type="text" name="first_name" id="firstName" required>
                </div>
                
                <div>
                    <label>Last Name:</label>
                    <input type="text" name="last_name" id="lastName" required>
                </div>
                
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                
                <div>
                    <label>Contact Number:</label>
                    <input type="text" name="contact" id="contact" required>
                </div>
                
                <div>
                    <label>Password:</label>
                    <input type="password" name="password" id="password">
                    <small>Leave blank to keep current password</small>
                </div>
                
                <div>
                    <button type="submit" class="btn btn-primary" name="add" id="submitBtn">Save</button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, id = null, firstName = '', lastName = '', email = '', contact = '') {
            const modal = document.getElementById('clientModal');
            document.getElementById('modalTitle').textContent = action === 'add' ? 'Add Client' : 'Edit Client';
            
            if(action === 'edit') {
                document.getElementById('clientId').value = id;
                document.getElementById('firstName').value = firstName;
                document.getElementById('lastName').value = lastName;
                document.getElementById('email').value = email;
                document.getElementById('contact').value = contact;
                document.getElementById('submitBtn').name = 'update';
            } else {
                document.getElementById('clientId').value = '';
                document.getElementById('firstName').value = '';
                document.getElementById('lastName').value = '';
                document.getElementById('email').value = '';
                document.getElementById('contact').value = '';
                document.getElementById('password').required = true;
                document.getElementById('submitBtn').name = 'add';
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('clientModal').style.display = 'none';
        }
    </script>
</body>
</html>