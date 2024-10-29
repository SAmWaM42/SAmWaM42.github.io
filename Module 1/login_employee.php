<?php
// Include necessary classes
require_once '../mod2/Database.php';
require_once 'User.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize User class
$user = new User($db);

// Handle employee login
if (isset($_POST['login_employee'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $org_unique_id = $_POST['org_unique_id'];

    $message = $user->loginEmployee($username, $password, $org_unique_id);
    echo $message;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Employee</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="logo-container">
        <img src="Timeoff.jpg" alt="Company Logo" class="company-logo">
    </div>
    <div class="form-container">
        <h2>Login Employee</h2>
        <form action="login_employee.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="org_unique_id" placeholder="Organization Unique ID" required>
            <input type="submit" name="login_employee" value="Login">
        </form>
    </div>
</body>
</html>
