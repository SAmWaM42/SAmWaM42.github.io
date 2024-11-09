<?php
//back end of request_form
session_start();
$_SESSION["ID"] = 1;
$_SESSION["Worker_ID"] = 1;
require_once "../load.php";
var_dump($_POST);
$status = 'pending';
if ($_POST["leave_type"] == "Assigned") 
{
    $statement ='insert into leave_requests(employee_ID,start_date,end_date) values (?,?,?)';
    $values = [$_SESSION["Worker_ID"], $_POST["start_date"], $_POST["end_date"]];
    $conn->insert_data($statement, $values,false);

    header("Location:../module3/Dashboard.php");
}
else
{
    $connection=$conn->get_connection();
    $type=$_POST["special_type"];
    $values=$connection->execute_query(" select days from type_values where name='$type' ");
     $comp=$values->fetch_assoc()["days"];
     $val=$_POST["start_date"];
     $date=date_create($val);
     date_add($date,date_interval_create_from_date_string("$comp days"));
    $val=date_format($date,'Y-m-d');
    echo $val;
    $statement ='insert into leave_requests(employee_ID,type,start_date,end_date) values (?,?,?,?)';
    $values = [ $_SESSION["Worker_ID"], $_POST["special_type"],$_POST["start_date"], $val];
    $conn->insert_data($statement, $values,true);
    header("Location:../module3/Dashboard.php");
}

