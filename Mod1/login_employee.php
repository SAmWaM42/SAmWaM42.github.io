<?php
require_once '../load.php';
require_once 'User.php';



$db_conn = $conn->get_connection();

$user = new User($db_conn);

$message = '';
if (isset($_POST['login_employee'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $user->loginEmployee($username, $password);

    if ($result) {
        session_start();
        $_SESSION['username'] = $result['name'];
        $_SESSION['user_id'] = $result['ID'];
        $_SESSION['org_name'] = $result['org_name'];

        header("Location: module3/dashboard.php");
        exit();
    } else {
        $message = "Invalid username or password";
    }
}
?>
