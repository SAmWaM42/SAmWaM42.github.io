<?php
// Include necessary classes
require_once 'Database.php';
require_once 'Organization.php';

// Initialize Database
$database = new Database();
$db = $database->connect();

// Initialize Organization class
$organization = new Organization($db);

// Handle organization registration
$message = '';
if (isset($_POST['register_org'])) {
    $org_name = trim($_POST['org_name']);

    // Validate input
    if (empty($org_name)) {
        $message = "Organization name is required!";
    } else {
        // Attempt to register the organization
        if ($organization->registerOrganization($org_name)) {
            // Redirect to register_employee.php after successful registration
            header("Location: mod1/register_employee.php");
            exit(); // Ensure no further code is executed after the redirect
        } else {
            $message = "Failed to register organization. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Organization</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to external CSS file -->
</head>
<body>
    <div class="logo-container">
        <img src="Timeoff.jpg" alt="Company Logo" class="company-logo">
    </div>
    <div class="form-container">
        <h2>Register Organization</h2>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="register_organization.php" method="POST">
            <input type="text" name="org_name" placeholder="Organization Name" required>
            <input type="submit" name="register_org" value="Register">
        </form>
    </div>
</body>
</html>
