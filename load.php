<?php
session_start();
//Class Auto Load
function classAutoLoad($classname)
{
$directories=["connections","FLOW","INC","module3"];
foreach($directories as $dir)
{
$filename=dirname(__FILE__).DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$classname.".php";



   
    if(file_exists($filename) and is_readable($filename))
    {
    
       require_once $filename;
    }
}

}
spl_autoload_register('classAutoLoad');

$conn=new conn();
$flow=new flow();
$includes=new inc();


$conn->connection("localhost:3308","root","HomeEcide42","easy_leave");
$conn->pdo_connection("localhost","3308","root","HomeEcide42","easy_leave");
$Objlbt = new leavebalancetracking($conn->get_pdo_connection());
$Objretrieve = new retrieve($conn->get_pdo_connection());



