<?php
require_once 'conn.php';
require_once 'User.php';

// Initialize Database
$db_instance = new conn();
$db_instance->connection('localhost', 'root', '', 'timeoff_database');
$db_conn = $db_instance->get_connection();

// Initialize User class
$user = new User($db_conn);

// Fetch all organizations
$orgQuery = "SELECT ID, name FROM organization";
$orgResult = $db_conn->query($orgQuery);

// Fetch all roles
$roleQuery = "SELECT ID, name FROM roles";
$roleResult = $db_conn->query($roleQuery);

// Fetch all genders
$genderQuery = "SELECT ID, name FROM gender";
$genderResult = $db_conn->query($genderQuery);

// Handle employee registration
$message = '';
if (isset($_POST['register_employee'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $org_id = $_POST['org_id'];
    $role_id = $_POST['role_id'];
    $gender_id = $_POST['gender_id'];

    if (empty($username) || empty($password) || empty($confirm_password) || empty($org_id) || empty($role_id) || empty($gender_id)) {
        $message = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Check if the gender_id exists in the gender table
        $genderCheckQuery = "SELECT ID FROM gender WHERE ID = ?";
        $stmt = $db_conn->prepare($genderCheckQuery);
        $stmt->bind_param("i", $gender_id);
        $stmt->execute();
        $genderResult = $stmt->get_result();

        if ($genderResult->num_rows > 0) {
            // Valid gender_id, proceed with registration
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $message = $user->registerEmployee($username, $hashed_password, $org_id, $role_id, $gender_id);

            if ($message === "Employee registered successfully.") {
                header("Location:login_employee.php");
                exit();
            }
        } else {
            // Invalid gender_id, return an error message
            $message = "Invalid gender selected.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Employee</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .existing-user {
            color: brown;
        }

        .existing-user a {
            color: green;
        }

        .existing-user a:hover {
            color: darkgreen;
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <img src="Timeoff.jpg" alt="Company Logo" class="company-logo">
    </div>
    <div class="form-container">
        <h2>Register Employee</h2>
        <form action="register_employee.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="org_id" required>
                <option value="">Select Organization</option>
                <?php while ($org = $orgResult->fetch_assoc()): ?>
                    <option value="<?php echo $org['ID']; ?>"><?php echo htmlspecialchars($org['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <select name="role_id" required>
                <option value="">Select Role</option>
                <?php while ($role = $roleResult->fetch_assoc()): ?>
                    <option value="<?php echo $role['ID']; ?>"><?php echo htmlspecialchars($role['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <select name="gender_id" required>
                <option value="">Select Gender</option>
                <?php while ($gender = $genderResult->fetch_assoc()): ?>
                    <option value="<?php echo $gender['ID']; ?>"><?php echo htmlspecialchars($gender['name']); ?></option>
                <?php endwhile; ?>
            </select>
            <input type="submit" name="register_employee" value="Register">
        </form>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Link for existing users to login -->
        <div class="existing-user">
            <p>Existing User? <a href="login_employee.php">Login</a></p>
        </div>
    </div>
</body>
</html>
