<?php
class retrieve {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    

    public function getEmployeeInfo($emp_id) {
        $sql = "SELECT name FROM employee WHERE ID = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result; // Employee found
        } else {
            // Handle case where no employee is found
            return null; // Or throw an exception
        }
    }

    // Fetch leave balances for the employee
    public function getLeaveBalances($emp_id) {
        $sql = "SELECT annual_leave_balance, sick_leave_balance, maternity_leave_balance FROM leave_balance WHERE emp_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result; // Leave balances found
        } else {
            // Handle case where no leave balances are found
            return null; // Or throw an exception
        }
    }
    public function getDaysTaken($emp_id){
        $sql = "SELECT type,DATEDIFF(end_date,start_date) AS days_taken FROM leave_record WHERE employee_ID = ? AND type = ?";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(1, $emp_id, PDO::PARAM_INT);
                $stmt->bindParam(2, $leaveType, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch leave requests for the employee
    public function getLeaveRequests($emp_id) {
        $sql = "SELECT type,status FROM leave_requests WHERE employee_ID = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$emp_id]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Will return an empty array if no requests found
    }

    public function getLeavetype($emp_id){
        $sql = "SELECT type FROM leave_requests WHERE employee_ID = ?";
        $stmt = $this ->pdo ->prepare($sql);
        $stmt->execute([$emp_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
