<?php
session_start();
if ($_SESSION['role'] !== 'office_staff') {
    header("Location: SignIn.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
    <title>MacJ Pest Control</title>
</head>
<body>
    <div class="sidebar">
        <h2>MacJ Pest<br>Control</h2>
        <a href="calendar.php" class="active">Calendar</a>
        <a href="reports_from_technicians.php">Reports</a>
        <a href="chemical_inventory.php">Chemical Inventory</a>
        <a href="technicians.php">Technicians</a>
        <a href="clients.php">Clients</a>
    </div>
</body>
</html>