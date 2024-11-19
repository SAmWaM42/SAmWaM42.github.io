<?php


require_once('../load.php');

$connection = $conn->get_connection();
if ($connection->connect_error) {
    die("$connection failed: " . $connection->connect_error);
}

// Retrieve employee records
$sql = "SELECT name,gender_id, ID, org_ID, role_ID FROM employee";
$result = $connection->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <link rel="stylesheet"href="../CSS/style.css">
        <link rel="stylesheet" href="../mod1/stylee.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="requests.css">


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

<main>
    <?php $includes->inner_nav("whatever"); ?>
   
    <div class="tbl"> 
        <br>
        <br>
        <h1 style="font-size:x-large; font-weight:bold;">EMPLOYEE RECORDS</h1>
        <br>
        <table>
            <thead>
            <tr>
                <th>NAME</th>
                <th>GENDER ID</th>
                <th>ID</th>
                <th>ORGANIZATION ID</th>
                <th>ROLE ID</th>
              
                <th>ACTION</th>
                <th>EDIT</th>
            </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['gender_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['org_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['role_ID']) . "</td>";
                   
                        echo "<td>
                            <form method='POST' style='display:inline-block;'>
                                <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['ID']) . "' />
                                <button type='submit' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</button>
                            </form>
                        </td>";
                        echo "<td>
                            <a href='edit.php?id=" . htmlspecialchars($row['ID']) . "'>Edit</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No employees found</td></tr>";
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
