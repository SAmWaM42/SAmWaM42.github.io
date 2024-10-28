<?php

//Class Auto Load
function classAutoLoad($classname)
{
$directories=["connections","FLOW","INC",];
foreach($directories as $dir)
{
$filename=dirname(__FILE__).DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$classname.".php";

   

    if(file_exists($filename) and is_readable("request_form.php"))
    {
    
       require_once $filename;
    }
}

}

spl_autoload_register('classAutoLoad');


$includes=new inc();
$conn=new conn();
$flow=new flow();

$conn->connection("localhost","root","424635","Easy_Leave");
