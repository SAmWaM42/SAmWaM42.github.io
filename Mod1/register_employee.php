<?php
// Include necessary classes
require_once 'Database.php';
require_once 'User.php';
require_once 'Organization.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize Organization and User classes
$organization = new Organization($db);
$user = new User($db);

// Fetch all organizations for the dropdown
$orgQuery = "SELECT ID, name FROM organization";
$orgResult = $db->query($orgQuery);

// Fetch all roles for the dropdown
$roleQuery = "SELECT ID, name FROM roles";
$roleResult = $db->query($roleQuery);

// Handle employee registration
$message = '';
if (isset($_POST['register_employee'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $org_id = $_POST['org_id'];
    $role_id = $_POST['role_id'];

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirm_password) || empty($org_id) || empty($role_id)) {
        $message = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match!";
    } else {
        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Register the employee
        $message = $user->registerEmployee($username, $hashed_password, $org_id, $role_id);

        // Redirect if successful
        if ($message === "Employee registered successfully.") {
            header("Location: login_employee.php");
            exit();
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
            <input type="submit" name="register_employee" value="Register">
        </form>
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
