// Purpose: Establishes a connection to the database

<?php
$servername = "localhost";
$username = "root";
$password = "Pobox6467";
$dbname = "timeoff";

try {
  $conn = new PDO("mysql:host=$servername;dbname=timeoff", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>


