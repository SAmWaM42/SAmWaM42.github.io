<?php

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
//$Objlbt = new leavebalancetracking();
//$Objretrieve = new retrieve();


$conn->connection("localhost:3307","root","b@bad1ana","timeoff");
$conn->pdo_connection("localhost","3307","root","b@bad1ana","timeoff");
