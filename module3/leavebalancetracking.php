<?php

class leavebalancetracking {
    
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function assignBalance() {
        try {
            // Fetch all employee IDs
            $sql1 = "SELECT ID FROM employee"; 
            $stmt1 = $this->pdo->prepare($sql1);
            $stmt1->execute();
            $employeeIds = $stmt1->fetchAll(PDO::FETCH_COLUMN);
    
            // Fetch leave types and corresponding days
            $sql2 = "SELECT name, days FROM type_values";
            $stmt2 = $this->pdo->prepare($sql2);
            $stmt2->execute();
            $leaveTypes = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR); // Leave types as key-value pairs
    
            foreach ($employeeIds as $emp_id) {
                // Check if leave balance already exists for this employee
                $checkSql = "SELECT COUNT(*) FROM leave_balance WHERE emp_id = :emp_id";
                $checkStmt = $this->pdo->prepare($checkSql);
                $checkStmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
                $checkStmt->execute();
                $exists = $checkStmt->fetchColumn();
    
                if ($exists) {
                    continue; // Skip if leave balance already exists
                }
    
                // Prepare SQL for inserting initial leave balances
                $sql3 = "INSERT INTO leave_balance 
                         (emp_id, annual_leave_balance, sick_leave_balance, maternity_leave_balance, last_accrual_date) 
                         VALUES (:emp_id, :annual_leave_balance, :sick_leave_balance, :maternity_leave_balance, NOW())";
    
                $stmt3 = $this->pdo->prepare($sql3);
    
                // Map leave types to the specific columns
                $annualLeave = $leaveTypes['Annual'] ?? 0;
                $sickLeave = $leaveTypes['Sick'] ?? 0;
                $maternityLeave = $leaveTypes['Maternity'] ?? 0;
    
                // Bind values explicitly
                $stmt3->bindValue(':emp_id', $emp_id, PDO::PARAM_INT);
                $stmt3->bindValue(':annual_leave_balance', $annualLeave, PDO::PARAM_INT);
                $stmt3->bindValue(':sick_leave_balance', $sickLeave, PDO::PARAM_INT);
                $stmt3->bindValue(':maternity_leave_balance', $maternityLeave, PDO::PARAM_INT);
    
                $stmt3->execute();
            }
    
            echo "<script>alert('Assigned balance successfully.');</script>";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { 
                echo "<script>alert('Leave balance already exists.');</script>";
            } else {
                echo "Failed to assign balance: " . $e->getMessage();
            }
        }
    }
    
    
    public function findBalance($emp_id) {
        

        try {
            $Objretrieve = new Retrieve($this->pdo);
            // Fetch leave types for the employee
            $leaveTypes = $Objretrieve->getLeavetype($emp_id);
    
            // Loop through each leave type and find balances
            foreach ($leaveTypes as $leaveTypeData) {
                $leaveType = $leaveTypeData['leave_type'];
    
                // Fetch leave request status and days taken from requests
                $sql = "SELECT status, DATEDIFF(end_date,start_date) AS days_taken FROM leave_record WHERE employee_ID = ? AND type = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $leaveType, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($result&&$result['status']==='Accepted') {                    
                     // Determine which balance to adjust based on the leave type
                    $balanceColumn = '';
                    switch ($leaveType) {
                        case 'annual':
                            $balanceColumn = 'annual_leave_balance';
                            break;
                        case 'sick':
                            $balanceColumn = 'sick_leave_balance';
                            break;
                        case 'maternity':
                            $balanceColumn = 'maternity_leave_balance';
                            break;
                        default:
                            echo "Invalid leave type.";
                            return;
                    }
    
                        // Get the current balance
                        $sql = "SELECT $balanceColumn FROM leave_balance WHERE employee_id = ?";
                        $stmt = $this->pdo->prepare($sql);
                        $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $currentBalance = $stmt->fetchColumn();
    
                        if ($currentBalance !== false) {
                            // Calculate the new balance after deduction
                            $newBalance = $currentBalance - $daysTaken;
    
                            if ($newBalance >= 0) {
                                // Update the balance in the leave_balance table
                                $sql = "UPDATE leave_balance SET $balanceColumn = ? WHERE emp_id = ?";
                                $stmt = $this->pdo->prepare($sql);
                                $stmt->bindParam(1, $newBalance, PDO::PARAM_INT);
                                $stmt->bindParam(2, $emp_id, PDO::PARAM_INT);
                                $stmt->execute();
    
                                echo "<script>alert('Leave balance updated successfully for $leaveType.');</script>";
                            } else {
                                echo "<script>alert('Insufficient leave balance for $leaveType.');</script>";
                            }
                        } else {
                            echo "<script>alert('Leave balance not found for $leaveType.');</script>";
                        }
                    } else {
                        echo "<script>alert('Your leave request for $leaveType is pending or not approved.');</script>";
                    }
                } 
                
            }
         catch (PDOException $e) {
            echo "Failed to update balance: " . $e->getMessage();
        }
    }
    
    public function carryoverLeave($emp_id) {
        try {
            // Retrieve the current leave balances for the employee
            $sql = "SELECT annual_leave_balance, sick_leave_balance, maternity_leave_balance FROM leave_balance WHERE emp_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
            $stmt->execute();
            $leaveBalances = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($leaveBalances) {
                // Update the leave balances for the new year and reset the last accrual date
                $sql = "UPDATE leave_balance 
                        SET annual_leave_balance = ?, 
                            sick_leave_balance = ?, 
                            maternity_leave_balance = ?, 
                            last_accrual_date = NOW() 
                        WHERE emp_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $leaveBalances['annual_leave_balance'], PDO::PARAM_INT);
                $stmt->bindParam(2, $leaveBalances['sick_leave_balance'], PDO::PARAM_INT);
                $stmt->bindParam(3, $leaveBalances['maternity_leave_balance'], PDO::PARAM_INT);
                $stmt->bindParam(4, $emp_id, PDO::PARAM_INT); // Corrected variable name
                $stmt->execute();

                echo "Leave balances successfully carried over to the next year.";
            } else {
                echo "No leave balances found for employee.";
            }
        } catch (PDOException $e) {
            echo "Error carrying over leave: " . $e->getMessage();
        }
    }
}

//require_once 'C:\XAMPP\htdocs\SAmWaM42.github.io\DatabaseConnection\Database.php';
//$ObjDb = new Database();
//$pdo = $ObjDb->getConnection();
require_once 'load.php';

$pdo = $ObjDb->connection();
$Objbalance = new leavebalancetracking($pdo);
require ("retrieve.php");
$Objretrieve = new Retrieve($pdo);
//$Objbalance->assignBalance();

