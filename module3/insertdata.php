
<?php
include("dbconnectionpdo.php");
//This is just for data entry demonstration
    class insertdata{
        private $pdo;
        private $lbt;//leavebalancetracking
        public function __construct($pdo){
            $this->pdo = $pdo;
            $this->lbt = new leavebalancetracking($pdo);
        }
        public function insert_form(){
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Userform</title>
            </head>
            <body>
                <form action="" method="post">
                    <label>employee_name</label>
                    <input type="text" placeholder="employeename" name="employee_name"></input>
                    <label>employee_role</label>
                    <input type="text" placeholder="employeerole" name="employee_role"></input>
                    <input type="submit"name="submit"></input>
                    
                </form>
            </body>
            </html>
            <?php
        }
        public  function datasubmit(){
            if(isset($_POST['submit'])){
                $empname = $_POST['employee_name'];
                $emprole = $_POST['employee_role'];

            
            try{
                
                $sql = "INSERT INTO employess (emp_name, emp_role) VALUES (:employee_name, :employee_role)";
                $stmt = $this->pdo->prepare($sql);
                $this->lbt->assignbalance($emp_id);
                $stmt->bindParam(':employee_name',$empname);
                $stmt->bindParam(':employee_role',$emprole);
                $stmt->execute();
                echo "Record added successfuly";
                $emp_id = $this->pdo->lastInsertId();
                return $emp_id;


            }
            catch(PDOexception $e){
                
                echo"Connection failed: ".$e->getMessage();
            }}

    ;       
            

    }}
    require('load.php') ;
     $pdo = $ObjDb->connection();
     $Objinsert = new insertdata($pdo);
     $Objinsert->insert_form();
     $Objinsert->datasubmit();?>