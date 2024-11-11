<?php
// Start session at the beginning of the script
session_start();

// Include necessary classes
require_once 'Login database.php';
require_once 'User.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize User class
$user = new User($db);

// Handle employee login
$message = '';
if (isset($_POST['login_employee'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Authenticate employee
    $result = $user->loginEmployee($username, $password);

    if ($result) {
        // Set session variables
        $_SESSION['username'] = $username;
        $_SESSION['user_id'] = $result; // Assuming $result returns the user ID

        // Redirect to dashboard.php in the module3 folder
        header("Location: module3/dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password";
    }
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
        <?php if ($message): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="login_employee.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login_employee" value="Login">
        </form>
    </div>
</body>
</html>
