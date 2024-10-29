<?php
    require_once "dbconnectionpdo.php";
    require_once "leavebalancetracking.php";
    //$Objinsert = new insertdata($pdo);
    $ObjDb = new dbconnectionpdo();
    $pdo = $ObjDb->connection();