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
if (isset($_POST['register_org'])) {
    $org_name = $_POST['org_name'];
    $org_unique_id = uniqid('ORG_');  // Auto-generate unique ID
    $message = $organization->registerOrganization($org_name, $org_unique_id);
    echo $message;
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
        <form action="" method="POST">
            <input type="text" name="org_name" placeholder="Organization Name" required>
            <input type="submit" name="register_org" value="Register">
        </form>
    </div>
</body>
</html>
