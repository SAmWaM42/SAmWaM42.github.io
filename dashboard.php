<?php
require_once "load.php";
$myConn=$conn->get_connection();
$includes->header("Request");
$includes->nav_bar();
?>
<iframe src="request_form.php" height="500px" width="1000px"></iframe>
<?php
$includes->footer();
