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
$orgQuery = "SELECT org_id, org_name FROM organizations";
$orgResult = $db->query($orgQuery);

// Handle employee registration
$message = '';
if (isset($_POST['register_employee'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $org_id = $_POST['org_id'];  // Get org_id from the dropdown

    if (empty($username) || empty($password) || empty($org_id)) {
        $message = "All fields are required!";
    } else {
        // Hash the password for secure storage
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Register the employee
        $message = $user->registerEmployee($username, $hashed_password, $org_id);

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
            <select name="org_id" required>
                <option value="">Select Organization</option>
                <?php while ($org = $orgResult->fetch_assoc()): ?>
                    <option value="<?php echo $org['org_id']; ?>"><?php echo htmlspecialchars($org['org_name']); ?></option>
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
