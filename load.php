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


$conn->connection("localhost:3308","root","HomeEcide42","Easy_Leave");
$conn->pdo_connection("localhost","3308","root","HomeEcide42","Easy_Leave");
$includes=new inc();
$conn=new conn();
$flow=new flow();
<<<<<<< HEAD
$Objlbt = new leavebalancetracking($pdo);
//$Objretrieve = new retrieve($pdo);
=======
$Objlbt = new leavebalancetracking($conn->get_pdo_connection());
$Objretrieve = new retrieve($conn->get_pdo_connection());
>>>>>>> 0b09557a1946ab3d8ec74744962824978c404df9



