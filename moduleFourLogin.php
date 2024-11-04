//hii ndio login yake since inahitaji session:
<?php
session_start();
require_once 'DatabaseConnection/Database.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connect to the database
    $database = new Database();
    $conn = $database->getConnection();

    // Prepare and execute the query to find the employee by email
    $sql = "SELECT employee_id, password FROM Employees WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password directly
    if ($user && $password === $user['password']) { // Plaintext comparison
        // Store employee_id in session and redirect to leave history page
        $_SESSION['employee_id'] = $user['employee_id'];
        header("Location: Display3.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Employee Login</h2>

<?php if (isset($error)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br><br>
    <input type="submit" value="Login">
</form>

</body>
</html>