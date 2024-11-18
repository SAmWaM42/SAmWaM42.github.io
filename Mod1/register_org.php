<?php
require_once '../load.php';
require_once 'Organization.php';
require_once 'user.php';

// Initialize Database

$db_conn = $conn->get_connection();

// Initialize Organization class
$organization = new Organization($db_conn);
$user=new user($db_conn);
$genderQuery = "SELECT * FROM gender";

$genderResult = $db_conn->query($genderQuery);


// Handle organization registration
$message = '';

if (isset($_POST['register_org']))
 {
    $org_name = $_POST['org_name'];
    $org_unique_id = uniqid('ORG_');
    //relation issue
    $message = $organization->registerOrganization($org_name, $org_unique_id);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $gender_id = $_POST['gender_id'];
    if (empty($username) || empty($password) ) {
        $message = "All fields are required!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
       
    }

    $name="Admin";
    $orgQuery = "SELECT ID FROM roles where name='$name'";
    $orgResult = $db_conn->query($orgQuery);
    $r_id=$orgResult->fetch_assoc()["ID"];
    $message=$user->registerAdmin($username,$hashed_password,$org_unique_id,$r_id,$gender_id);

    if (strpos($message, "successfully") !== false) {
        header("Location: register_employee.php");
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
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="gender_id" required>
                <option value="">Select Gender</option>
                <?php while ($gender = $genderResult->fetch_assoc()): ?>
                    <option value="<?php echo $gender['ID']; ?>"><?php echo htmlspecialchars($gender['name']); ?></option>
                <?php endwhile; ?>
            </select>

            <input type="submit" name="register_org" value="Register">
            
        </form>
        </form>
    </div>
</body>
</html>
