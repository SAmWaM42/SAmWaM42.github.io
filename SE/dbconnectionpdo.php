<?php
    class dbconnectionpdo{ 
        
        private $servername = 'localhost';
        private $username = 'root';
        private $password = 'Baguvix67';
        private $dbname = 'easyleave';
        public $con;

        public function connection(){
            try{
                $this->con = new PDO("mysql:host ={$this->servername};dbname={$this->dbname}",$this->username,$this->password);
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->con;
                echo "connected successfully";

    }catch(PDOException $e){
        echo"Connection failed: ".$e->getMessage();
    }}}
    $ObjDb = new dbconnectionpdo();
    $ObjDb->connection();
?>