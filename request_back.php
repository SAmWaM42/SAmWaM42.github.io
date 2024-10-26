<?php
session_start();
$_SESSION["ID"] = 1;
$_SESSION["Worker_ID"] = 1;
require_once "load.php";
var_dump($_POST);
$status = 'pending';
if ($_POST["leave_type"] == "Assigned") 
{
    $statement ='insert into leave_requests(worker_ID,start_date,end_date) values (?,?,?)';
    $values = [$_SESSION["Worker_ID"], $_POST["start_date"], $_POST["end_date"]];
    $conn->insert_data($statement, $values,false);
}
else
{
    $statement ='insert into special_requests(worker_ID,Type,start_date,end_date) values (?,?,?,?)';
    $values = [ $_SESSION["Worker_ID"], $_POST["special_type"],$_POST["start_date"], $_POST["end_date"]];
    $conn->insert_data($statement, $values,true);

}

