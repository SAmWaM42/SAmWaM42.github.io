<?php
require_once 'conn.php';
require_once 'User.php';

$db_instance = new conn();
$db_instance->connection('localhost', 'root', '', 'se');
$db_conn = $db_instance->get_connection();

$user = new User($db_conn);

$message = '';
if (isset($_POST['login_employee'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $user->loginEmployee($username, $password);

    if ($result) {
        session_start();
        $_SESSION['username'] = $result['name'];
        $_SESSION['user_id'] = $result['ID'];
        $_SESSION['org_name'] = $result['org_name'];

        header("Location: /SAmWaM42.github.io/module3/Dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password";
    }
}
?>
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

