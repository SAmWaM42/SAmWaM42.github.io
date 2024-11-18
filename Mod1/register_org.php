<?php
require_once 'conn.php';
require_once 'Organization.php';

// Initialize Database
$db_instance = new conn();
$db_instance->connection('localhost', 'root', '', 'se');
$db_conn = $db_instance->get_connection();

// Initialize Organization class
$organization = new Organization($db_conn);

// Handle organization registration
$message = '';
if (isset($_POST['register_org'])) {
    $org_name = $_POST['org_name'];
    $org_unique_id = uniqid('ORG_');

    $message = $organization->register_org($org_name, $org_unique_id);

    if (strpos($message, "successfully") !== false) {
        header("Location:register_employee.php");
        exit();
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
        <form action="register_org.php" method="POST">
            <input type="text" name="org_name" placeholder="Organization Name" required>
            <input type="submit" name="register_org" value="Register">
        </form>
    </div>
</body>
</html>
