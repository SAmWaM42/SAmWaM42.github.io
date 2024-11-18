<?php
// Purpose: The HR views the leave requests made by the employees and either accepts or rejects them. 
// Before he/she does this, the default status of the leave request is pending.
session_start();

require_once('../load.php');

$connection = $conn->get_connection();
if ($connection->connect_error) {
    die("$connection failed: " . $connection->connect_error);
}

// Handle status update from form submission
if (isset($_POST['status']) && isset($_POST['id'])) {
    $status = $_POST['status'];
    $id = $_POST['id'];

    if ($status === 'accepted' || $status === 'rejected') {
        // Insert the approved/rejected request into `leave_record` without specifying `id`
        $insertSql = "INSERT INTO leave_record (employee_ID, start_date, end_date, type, status)
                      SELECT employee_ID, start_date, end_date, type, '$status'
                      FROM leave_requests WHERE ID = $id";
                      
        $connection->query($insertSql);

        // Delete the request from leave_requests table
        $deleteSql = "DELETE FROM leave_requests WHERE ID = $id";
        $connection->query($deleteSql);
    }
}

// Retrieve pending leave requests
$sql = "SELECT ID, employee_ID, start_date, end_date, type, status FROM leave_requests";
$result = $connection->query($sql);

// Retrieve approved/rejected requests from leave_record
$sqlApprovedRejected = "SELECT employee_ID, start_date, end_date, type, status FROM leave_record";
$resultApprovedRejected = $connection->query($sqlApprovedRejected);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="requests.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar__container">
        <a href="#" id="navbar__logo"> <img src= "../Timeoff[1].jpg" width="65px"> TimeOff</a>
        <div class="navbar__toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
        <ul class="navbar__menu">
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    Home
                </a>
            </li>
            <li class="navbar__item">
                <a href="/" class="navbar__links">
                    About
                </a>
            </li>
            <li class="navbar__btn">
                <a href="login_employee.php" class="button">
                    Login
                </a>
            </li>
        </ul>
    </div>
</nav>

<main>
    <div class="tbl"> 
    <br>
    <h1 style="font-size:x-large; font-weight:bold;">PENDING REQUESTS</h1>
    <br>
    <table>
        <thead>
        <tr>
           <th>EMPLOYEE ID</th>
           <th>START DATE</th>
           <th>END DATE</th>
           <th>TYPE</th>
           <th>STATUS</th>
        </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['employee_ID'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>";
                    
                    echo "<form method='POST' action=''>";
                    echo "<select name='status' onchange='this.form.submit()'>";
                    echo "<option class='pending' value='pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>";
                    echo "<option class='accepted' value='accepted' " . ($row['status'] == 'Accepted' ? 'selected' : '') . ">Accepted</option>";
                    echo "<option class='rejected' value='rejected' " . ($row['status'] == 'Rejected' ? 'selected' : '') . ">Rejected</option>";
                    echo "</select>";
                    echo "<input type='hidden' name='id' value='" . $row['ID'] . "'>";
                    echo "</form>";
                    
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No leave requests found</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Approved and Rejected Requests Table -->
    <br>
    <h1 style="font-size:x-large; font-weight:bold;">APPROVED AND REJECTED REQUESTS</h1>
    <br>
    <table>
        <thead>
            <tr>
                <th>EMPLOYEE ID</th>
                <th>START DATE</th>
                <th>END DATE</th>
                <th>TYPE</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($resultApprovedRejected->num_rows > 0) {
                while ($row = $resultApprovedRejected->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['employee_ID'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['type'] . "</td>";
                    echo "<td>" . ucfirst($row['status']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No approved or rejected requests found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</main>

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
