<?php 
//leave_request form
require_once "../load.php";
$myConn=$conn->get_connection();
$includes->header("Request");
$includes->nav_bar();
//$includes->inner_nav();
$flow->form();
$includes->footer();