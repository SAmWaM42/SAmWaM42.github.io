<?php
// Include necessary classes
require_once 'Database.php';
require_once 'User.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize User class
$user = new User($db);

// Handle employee registration
if (isset($_POST['register_employee'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $org_unique_id = $_POST['org_unique_id'];

    // Find the organization ID based on the unique ID
    $orgQuery = "SELECT org_id FROM organizations WHERE org_unique_id = ?";
    $stmt = $db->prepare($orgQuery);
    $stmt->bind_param('s', $org_unique_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $org_row = $result->fetch_assoc();
        $org_id = $org_row['org_id'];
        $message = $user->registerEmployee($username, $password, $org_id);

        // If the employee registration is successful
        if ($message === "Employee registered successfully.") {
            // Redirect to login page
            header("Location: login_employee.php");
            exit(); // Ensure the script stops running after redirect
        } else {
            echo $message;
        }
    } else {
        echo "Invalid Organization ID!";
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
            <input type="text" name="org_unique_id" placeholder="Organization Unique ID" required>
            <input type="submit" name="register_employee" value="Register">
        </form>
    </div>
</body>
</html>
