<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'macj_pest_control');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle all operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conn->query("INSERT INTO technicians (username, password) VALUES ('$username', '$password')");
    } elseif (isset($_POST['update'])) {
        $id = (int)$_POST['id'];
        $username = $conn->real_escape_string($_POST['username']);
        $password = !empty($_POST['password']) 
                    ? password_hash($_POST['password'], PASSWORD_DEFAULT) 
                    : $_POST['old_password'];
        $conn->query("UPDATE technicians SET username='$username', password='$password' WHERE technician_id=$id");
    } elseif (isset($_POST['restore'])) {
        $id = (int)$_POST['restore_id'];
        $result = $conn->query("SELECT * FROM archived_technicians WHERE technician_id=$id");
        if ($result->num_rows > 0) {
            $tech = $result->fetch_assoc();
            $conn->query("INSERT INTO technicians (technician_id, username, password) 
                         VALUES ({$tech['technician_id']}, '{$tech['username']}', '{$tech['password']}')");
            $conn->query("DELETE FROM archived_technicians WHERE technician_id=$id");
        }
    }
} elseif (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Archive before deleting
    $result = $conn->query("SELECT * FROM technicians WHERE technician_id=$id");
    if ($result->num_rows > 0) {
        $tech = $result->fetch_assoc();
        $conn->query("INSERT INTO archived_technicians (technician_id, username, password) 
                      VALUES ({$tech['technician_id']}, '{$tech['username']}', '{$tech['password']}')");
        $conn->query("DELETE FROM technicians WHERE technician_id=$id");
    }
}

$technicians = $conn->query("SELECT * FROM technicians ORDER BY technician_id");
$archived = $conn->query("SELECT * FROM archived_technicians ORDER BY deleted_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>MacJ Pest Control - Technicians</title>
    <style>
        .main-content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #1e88e5;
            color: white;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
        }
        .btn-primary {
            background-color: #1e88e5;
        }
        .btn-danger {
            background-color: #e53935;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #1e88e5;
        }
        .archive-section {
            margin-top: 40px;
            padding: 20px;
            background: #e3f2fd;
            border-radius: 8px;
        }
        .archive-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin: 5px 0;
            background: white;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>MacJ Pest<br>Control</h2>
        <a href="calendar.php">Calendar</a>
        <a href="reports_from_technicians.php">Reports</a>
        <a href="chemical_inventory.php">Chemical Inventory</a>
        <a href="technicians.php" class="active">Technicians</a>
        <a href="clients.php">Clients</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h2 style="color: #1e88e5; margin-bottom: 20px;">Manage Technicians</h2>
            <button class="btn btn-primary" onclick="openModal('add')">Add New Technician</button>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($tech = $technicians->fetch_assoc()): ?>
                    <tr>
                        <td><?= $tech['technician_id'] ?></td>
                        <td><?= htmlspecialchars($tech['username']) ?></td>
                        <td>
                            <button class="btn btn-primary" onclick="openModal('edit', <?= $tech['technician_id'] ?>, '<?= $tech['username'] ?>')">Edit</button>
                            <a href="?delete=<?= $tech['technician_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="archive-section">
                <h3 style="color: #1e88e5; margin-bottom: 15px;">Archived Technicians</h3>
                <div id="archiveList">
                    <?php while($arch = $archived->fetch_assoc()): ?>
                    <div class="archive-item">
                        <div>
                            <span style="margin-right: 20px;">ID: <?= $arch['technician_id'] ?></span>
                            <span>Username: <?= htmlspecialchars($arch['username']) ?></span>
                            <span style="margin-left: 20px; color: #666;">
                                Deleted: <?= date('M d, Y', strtotime($arch['deleted_at'])) ?>
                            </span>
                        </div>
                        <form method="POST">
                            <input type="hidden" name="restore_id" value="<?= $arch['technician_id'] ?>">
                            <button type="submit" name="restore" class="btn btn-primary">
                                Restore
                            </button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="techModal" class="modal">
        <div class="modal-content">
            <form method="POST">
                <h3 id="modalTitle" style="color: #1e88e5;"></h3>
                <input type="hidden" name="id" id="techId">
                <input type="hidden" name="old_password" id="oldPassword">
                
                <div style="margin: 15px 0;">
                    <label>Username:</label>
                    <input type="text" name="username" id="username" required style="width: 100%; padding: 8px;">
                </div>
                
                <div style="margin: 15px 0;">
                    <label>Password:</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" 
                               style="width: 100%; padding: 8px 35px 8px 8px;">
                        <i class="toggle-password fas fa-eye" 
                           onclick="togglePasswordVisibility()"></i>
                    </div>
                    <small>Leave blank to keep current password</small>
                </div>
                
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary" name="add" id="submitBtn">Add</button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, id = null, username = '') {
            const modal = document.getElementById('techModal');
            document.getElementById('modalTitle').textContent = action === 'add' 
                ? 'Add New Technician' 
                : 'Edit Technician';
                
            if(action === 'edit') {
                document.getElementById('techId').value = id;
                document.getElementById('username').value = username;
                document.getElementById('oldPassword').value = "<?= $tech['password'] ?? '' ?>";
                document.getElementById('submitBtn').name = 'update';
            } else {
                document.getElementById('techId').value = '';
                document.getElementById('username').value = '';
                document.getElementById('password').required = true;
                document.getElementById('submitBtn').name = 'add';
            }
            
            modal.style.display = 'block';
        }

        function closeModal() {
            document.getElementById('techModal').style.display = 'none';
        }

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('techModal');
            if (event.target === modal) closeModal();
        }
    </script>
</body>
</html>