<?php
    class leavebalancetracking{
        private $pdo;
        public function __construct($pdo){
            $this->pdo = $pdo;
        }
        public function assignbalance(){
            
            try{
            $sql1 = "SELECT emp_id FROM employess";
            $stmt1 = $this->pdo->prepare($sql1);
            $stmt1->execute();
            $employeeIds = $stmt1->fetchAll(PDO::FETCH_COLUMN); 
            foreach($employeeIds as $emp_id){
   
            $sql2 = "INSERT INTO leave_balance(emp_id,annual_leave_balance,sick_leave_balance,maternity_leave_balance,last_accrual_date)
            values (:emp_id,21,14,90,NOW())";
            $stmt = $this->pdo->prepare($sql2);
            $stmt -> bindParam(':emp_id',$emp_id,PDO::PARAM_INT);
            $stmt -> execute();
            echo "Assugned balance successful";
            }}
            catch (PDOexception $e){
                echo "Failed " .$e->getMessage();
            }
        }public function findBalance($emp_id, $leavetype, $daysTaken, $pdo) {
            try {
                // Check leave request status and fetch initial balance and days taken in a single query
                $sql = "SELECT l.initial_balance, r.days_taken, r.status
                        FROM leave_balances l
                        JOIN requests r ON l.emp_id = r.emp_id AND l.leave_type = r.leave_type
                        WHERE l.emp_id = ? AND l.leave_type = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bind_param("is", $emp_id, $leavetype);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
        
                $status = $row['status'];
                $initialBalance = $row['initial_balance'];
                $daysTakenFromRequest = $row['days_taken'];
        
                if ($status === 'approved') {
                    // Calculate the new balance
                    $newBalance = $initialBalance - $daysTakenFromRequest;
        
                    // Update the leave balance
                    $sql = "UPDATE leave_balances SET initial_balance = ? WHERE employee_id = ? AND leave_type = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bind_param("iis", $newBalance, $emp_id, $leavetype);
                    $stmt->execute();
        
                    echo "Leave balance updated successfully.";
                } else {
                    echo "<script>alert('Your leave is pending or not approved.');</script>";
                }
            } catch (PDOException $e) {
                echo "<script>alert('Failed to update Balance');</script>" . $e->getMessage();
            }
        }
        function carryoverLeave($employeeId, $pdo) {
            try {
                // Determine the end of the current leave year (e.g., December 31st)
                $endOfYear = new DateTime('last day of December this year');
        
                // Retrieve the current leave balances for the employee
                $sql = "SELECT leave_type, initial_balance, days_taken FROM leave_balances WHERE employee_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->bind_param("i", $employeeId);
                $stmt->execute();
                $result = $stmt->get_result();
        
                while ($row = $result->fetch_assoc()) {
                    $leaveType = $row['leave_type'];
                    $initialBalance = $row['initial_balance'];
                    $daysTaken = $row['days_taken'];
        
                    // Calculate the unused balance
                    $unusedBalance = $initialBalance - $daysTaken;
        
                    // Update the initial balance for the next year, adding the unused balance to the default value
                    $sql = "UPDATE leave_balances SET initial_balance = (SELECT initial_balance FROM leave_balances WHERE employee_id = ? AND leave_type = ?) + ? WHERE employee_id = ? AND leave_type = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bind_param("iiii", $unusedBalance, $employeeId, $leaveType, $employeeId, $leaveType);
                    $stmt->execute();
                }
            } catch (PDOException $e) {
                echo "Error carrying over leave: " . $e->getMessage();
            }
        }
            
    }
    require('load.php');
    $pdo = $ObjDb->connection();
    $Objbalance = new leavebalancetracking($pdo);
    $Objbalance->assignbalance();