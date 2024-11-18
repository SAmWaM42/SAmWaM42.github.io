<?php
session_start();
require_once('../load.php');

$connection = $conn->get_connection();
if ($connection->connect_error) {
    die("$connection failed: " . $connection->connect_error);
}

// Fetch the employee record for the given ID
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);
    $sql = "SELECT name, gender_id, ID, org_ID, role_ID, Role FROM employee WHERE ID = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $employee = $result->fetch_assoc();
    $stmt->close();

    if (!$employee) {
        die("Employee not found.");
    }
} else {
    die("Invalid request.");
}

// Update the employee record if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $gender_id = $_POST['gender_id'];
    $org_id = $_POST['org_id'];
    $role_id = $_POST['role_id'];
    $role = $_POST['role'];

    $update_sql = "UPDATE employee SET name = ?, gender_id = ?, org_ID = ?, role_ID = ?, Role = ? WHERE ID = ?";
    $stmt = $connection->prepare($update_sql);
    $stmt->bind_param("sisssi", $name, $gender_id, $org_id, $role_id, $role, $employee_id);
    if ($stmt->execute()) {
        echo "<script>alert('Employee updated successfully!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Failed to update employee.');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar__container">
        <a href="#" id="navbar__logo"><img src="../Timeoff[1].jpg" width="65px"> TimeOff</a>
        <div class="navbar__toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
            <li class="navbar__item"><a href="/" class="navbar__links">Home</a></li>
            <li class="navbar__item"><a href="/" class="navbar__links">About</a></li>
            <li class="navbar__btn"><a href="login_employee.php" class="button">Login</a></li>
        </ul>
    </div>
</nav>
        <br>
        <h1 style="font-size:x-large; font-weight:bold; text-align:center">EDIT EMPLOYEE DETAILS</h1>
        <br>

    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required>
        <br>
        <label for="gender_id">Gender ID:</label>
        <input type="number" id="gender_id" name="gender_id" value="<?= htmlspecialchars($employee['gender_id']) ?>" required>
        <br>
        <label for="org_id">Organization ID:</label>
        <input type="number" id="org_id" name="org_id" value="<?= htmlspecialchars($employee['org_ID']) ?>" required>
        <br>
        <label for="role_id">Role ID:</label>
        <input type="number" id="role_id" name="role_id" value="<?= htmlspecialchars($employee['role_ID']) ?>" required>
        <br>
        <label for="role">Role:</label>
        <input type="text" id="role" name="role" value="<?= htmlspecialchars($employee['Role']) ?>" required>
        <br>
        <button type="submit">Edit</button>
    </form>

    <div class="footer__container">
    <div class="footer__links">
        <div class="footer__link--wrapper">
         <div class="footer__link--items">
            <h2>About Us</h2>
            <a href="/">How It works</a>
            <a href="/">Testimonials</a>
            <a href="/">Experience</a>
            <a href="/">Terms of Service</a>
         </div>  
         <div class="footer__link--items">
            <h2>Services</h2>
            <a href="/">How It works</a>
            <a href="/">Testimonials</a>
            <a href="/">Experience</a>
            <a href="/">Terms of Service</a>
         </div>  
        </div>
        <div class="footer__link--wrapper">
            <div class="footer__link--items">
               <h2>About Us</h2>
               <a href="/">How It works</a>
               <a href="/">Testimonials</a>
               <a href="/">Experience</a>
               <a href="/">Terms of Service</a>
            </div>  
            <div class="footer__link--items">
               <h2>About Us</h2>
               <a href="/">How It works</a>
               <a href="/">Testimonials</a>
               <a href="/">Experience</a>
               <a href="/">Terms of Service</a>
            </div>  
           </div>
    </div>
    <p class="website__right"> &copy; TimeOff 2024. All rights reserved</p>
</div>


</body>
</html>
