<?php
class LeaveRequest {
    private $conn;
    private $table_name = "EmployeeLeaves";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method to get leave requests with optional filters
    public function getLeaveRequests($employee_id = null, $start_date = null, $end_date = null) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE 1=1";
        
        // Add filters based on provided parameters
        if ($employee_id) {
            $query .= " AND employee_id = :employee_id"; // Filter by employee_id
        }
        if ($start_date) {
            $query .= " AND start_date >= :start_date";
        }
        if ($end_date) {
            $query .= " AND end_date <= :end_date";
        }
    
        $stmt = $this->conn->prepare($query);
    
        // Bind parameters if they exist
        if ($employee_id) {
            $stmt->bindParam(":employee_id", $employee_id);
        }
        if ($start_date) {
            $stmt->bindParam(":start_date", $start_date);
        }
        if ($end_date) {
            $stmt->bindParam(":end_date", $end_date);
        }
    
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>