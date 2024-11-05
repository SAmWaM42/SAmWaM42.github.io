<?php
    require_once "dbconnectionpdo.php";
    require_once "leavebalancetracking.php";
    //require_once 'C:\XAMPP\htdocs\SAmWaM42.github.io\DatabaseConnection\Database.php';
    //$ObjDb = new Database();
    //$pdo = $ObjDb->getConnection();
    //$Objinsert = new insertdata($pdo);
    $ObjDb = new dbconnectionpdo();
    $pdo = $ObjDb->connection();