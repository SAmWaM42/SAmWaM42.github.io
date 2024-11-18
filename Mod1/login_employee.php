<?php
require_once '../load.php';
require_once 'User.php';



$db_conn = $conn->get_connection();

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
        $_SESSION["role"]=$result['role_ID'];

        header("Location:../module3/Dashboard.php");
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
    <style>
        /* Specific style for the "Don't have an account? Register" message and link */
        .no-account {
            color: brown;
        }

        .no-account a {
            color: green;
        }

        .no-account a:hover {
            color: darkgreen;
        }
    </style>
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
            
            <!-- Password field with toggle visibility functionality -->
            <input type="password" name="password" id="password" placeholder="Password" required>
            <button type="button" id="showPasswordBtn">Show Password</button>

            <input type="submit" name="login_employee" value="Login">
        </form>

        <!-- Link for users who don't have an account -->
        <div class="no-account">
            <p>Don't have an account? <a href="register_employee.php">Register</a></p>
        </div>
    </div>

    <script>
        // Toggle password visibility and automatically hide after 2 seconds
        document.getElementById("showPasswordBtn").addEventListener("click", function() {
            var passwordField = document.getElementById("password");
            var button = document.getElementById("showPasswordBtn");

            // Toggle the password visibility
            if (passwordField.type === "password") {
                passwordField.type = "text";
                button.textContent = "Hide Password";  // Change button text
            } else {
                passwordField.type = "password";
                button.textContent = "Show Password";  // Reset button text
            }

            // Optionally hide the password after 2 seconds
            setTimeout(function() {
                passwordField.type = "password";
                button.textContent = "Show Password";  // Reset button text after timeout
            }, 2000);
        });
    </script>
</body>
</html>
