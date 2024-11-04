<?php
// Purpose: The HR views the leave requests made by the employees and either accepts or rejects them. 
// Before he/she does this, the default status of the leave request is pending.
$severname = "localhost:3307";
$username = "root";
$password = "b@bad1ana";
$dbname = "timeoff";

$conn = new mysqli($severname,$username,$password,$dbname);

if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['status']) && isset($_POST['id'])) {
    $status = $_POST['status'];
    $id = $_POST['id'];

    $sql = "UPDATE leave_requests SET status='$status' WHERE id=$id";
    $conn->query($sql);
}

$sql = "SELECT id, name, period, type_of_leave, status FROM leave_requests";
$result = $conn->query($sql);

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
    <header>
        <p>HEADER</p>
        <p>EASY LEAVE</p>
        <p>LOGO</p>      
    </header>

    <nav class="navigation">
        <ul>
            <li><a href="#">PENDING REQUESTS</a></li>
            <li id="nav3"><a href="#">SUBMIT REQUESTS</a></li>
            <li id="nav3"><a href="#">DASHBOARD</a></li>
        </ul>
    </nav>
    
    <main>
        <div class="tbl"> 
        <br>
        <h1>PENDING REQUESTS</h1>
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

                $conn->close();
                ?>
            </tbody>
        </table>
        
    </main>

    <footer>
          <div>Terms and Conditions</div>
          <div>&copy;CopyRight</div>
          <div>Help Contacts</div>
    </footer>

</body>
</html>