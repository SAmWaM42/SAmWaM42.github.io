<?php

class leavebalancetracking {
    
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function assignBalance() {
        try {
            $sql1 = "SELECT emp_id FROM employess"; 
            $stmt1 = $this->pdo->prepare($sql1);
            $stmt1->execute();
            $employeeIds = $stmt1->fetchAll(PDO::FETCH_COLUMN); 

            foreach ($employeeIds as $emp_id) {
                $sql2 = "INSERT INTO leave_balance (emp_id, annual_leave_balance, sick_leave_balance, maternity_leave_balance, last_accrual_date) 
                         VALUES (:emp_id, 21, 14, 90, NOW()) 
                         ON DUPLICATE KEY UPDATE 
                         annual_leave_balance = VALUES(annual_leave_balance), 
                         sick_leave_balance = VALUES(sick_leave_balance), 
                         maternity_leave_balance = VALUES(maternity_leave_balance), 
                         last_accrual_date = NOW()"; // Prevents duplicate entries
                         
                $stmt = $this->pdo->prepare($sql2);
                $stmt->bindParam(':emp_id', $emp_id, PDO::PARAM_INT);
                $stmt->execute();
            }
            echo "<script>alert('Assigned balance successfully.');</script>";
        } catch (PDOException $e) {
            echo "Failed to assign balance: " . $e->getMessage();
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
                $sql = "SELECT status, days_taken FROM requests WHERE emp_id = ? AND leave_type = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $leaveType, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($result) {
                    $status = $result['status'];
                    $daysTaken = $result['days_taken'];
    
                    if ($status === 'approved') {
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
                        $sql = "SELECT $balanceColumn FROM leave_balance WHERE emp_id = ?";
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
                } else {
                    echo "No leave request found for employee $emp_id and leave type $leaveType.";
                }
            }
        } catch (PDOException $e) {
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

require('load.php');
$pdo = $ObjDb->connection();
$Objbalance = new leavebalancetracking($pdo);
require ("retrieve.php");
$Objretrieve = new Retrieve($pdo);
//$Objbalance->assignBalance();

