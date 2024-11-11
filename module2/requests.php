<?php
// Purpose: The HR views the leave requests made by the employees and either accepts or rejects them. 
// Before he/she does this, the default status of the leave request is pending.
session_start();

require_once('../load.php');

$connection=$conn->get_connection();
if($connection->connect_error) {
    die("$connection failed: " . $connection->connect_error);
}

// Handle status update from form submission
if (isset($_POST['status']) && isset($_POST['id'])) {
    $status = $_POST['status'];
    $id = $_POST['id'];

    if (isset($_POST['status']) && isset($_POST['id'])) {
        $status = $_POST['status'];
        $id = $_POST['id'];
    
        if ($status === 'accepted' || $status === 'rejected') {
            // Insert the approved/rejected request into `approved_requests` without specifying `id`
            $insertSql = "INSERT INTO approved_requests (name, period, type_of_leave, status)
                          SELECT name, period, type_of_leave, '$status'
                          FROM leave_requests WHERE id=$id";
            $connection->query($insertSql);
        
            // Delete the request from leave_requests table
            $deleteSql = "DELETE FROM leave_requests WHERE id=$id";
            $connection->query($deleteSql);
            // Update the status in leave_requests to keep only pending entries
            //$updateSql = "UPDATE leave_requests SET status='$status' WHERE id=$id";
            //$conn->query($updateSql);
        }
    }
    
}

// Retrieve pending leave requests
$sql = "SELECT id, name, period, type_of_leave, status FROM leave_requests";
$result = $connection->query($sql);

// Retrieve approved/rejected requests from approved_requests
$sqlApprovedRejected = "SELECT name, period, type_of_leave, status FROM approved_requests";
$resultApprovedRejected = $connection->query($sqlApprovedRejected);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="requests.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar__container">
        <a href="#" id="navbar__logo" color: = "black"> <img src= "../Timeoff[1].jpg" width="65px"> TimeOff</a>
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
               <th>NAME</th>
               <th>PERIOD</th>
               <th>TYPE</th>
               <th>STATUS</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['period'] . "</td>";
                        echo "<td>" . $row['type_of_leave'] . "</td>";
                        echo "<td>";

                        echo "<form method='POST' action=''>";

                        echo "<select name='status' onchange='this.form.submit()'>";
                        echo "<option class='pending' value='pending' " . ($row['status'] == 'pending' ? 'selected' : '') . ">Pending</option>";
                        echo "<option class='accepted' value='accepted' " . ($row['status'] == 'accepted' ? 'selected' : '') . ">Accepted</option>";
                        echo "<option class='rejected' value='rejected' " . ($row['status'] == 'rejected' ? 'selected' : '') . ">Rejected</option>";
                        echo "</select>";

                        /*if ($row['status'] == 'Pending') {
                            echo "<span class='pending'>Pending</span><br>";
                            echo "<input type='radio' name='status' value='accepted' onclick='this.form.submit()'> Accept";
                            echo "<input type='radio' name='status' value='rejected' onclick='this.form.submit()'> Reject";
                        } else if ($row['status' == 'accepted']) {
                            echo "<span class='accepted'>Accepted</span>";
                        } else if($row['status' == 'rejected']) {
                            echo "<span class='rejected'>Rejected</span>";
                        }*/

                        echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";

                        echo "</form>";
                        
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No leave requests found</td></tr>";
                }

                $connection->close();
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
                    <th>NAME</th>
                    <th>PERIOD</th>
                    <th>TYPE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($resultApprovedRejected->num_rows > 0) {
                    while ($row = $resultApprovedRejected->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['period'] . "</td>";
                        echo "<td>" . $row['type_of_leave'] . "</td>";
                        echo "<td>" . ucfirst($row['status']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No approved or rejected requests found</td></tr>";
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
            <a href = "/">How It works</a>
            <a href = "/">Testimonials</a>
            <a href = "/">Experience</a>
            <a href = "/">Terms of Service</a>
         </div>  
         <div class="footer__link--items">
            <h2>Services</h2>
            <a href = "/">How It works</a>
            <a href = "/">Testimonials</a>
            <a href = "/">Experience</a>
            <a href = "/">Terms of Service</a>
         </div>  
        </div>
        <div class="footer__link--wrapper">
            <div class="footer__link--items">
               <h2>About Us</h2>
               <a href = "/">How It works</a>
               <a href = "/">Testimonials</a>
               <a href = "/">Experience</a>
               <a href = "/">Terms of Service</a>
            </div>  
            <div class="footer__link--items">
               <h2>About Us</h2>
               <a href = "/">How It works</a>
               <a href = "/">Testimonials</a>
               <a href = "/">Experience</a>
               <a href = "/">Terms of Service</a>
            </div>  
           </div>
    </div>
    <p class="website__right"> &copy; TimeOff 2024. All rights reserved</p>
</div>

</body>
</html>