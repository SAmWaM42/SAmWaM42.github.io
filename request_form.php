<?php
require_once "load.php";
$myConn=$conn->get_connection();
$includes->header("Request");
$includes->inner_nav();
$flow->form();
$includes->footer();