<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register an employee with organization ID
    public function registerEmployee($username, $password, $org_id) {
        $query = "INSERT INTO employees (username, password, org_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssi', $username, $password, $org_id);
        if ($stmt->execute()) {
            return "Employee registered successfully!";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Login employee
    public function loginEmployee($username, $password, $org_unique_id) {
        $query = "SELECT e.* FROM employees e
                  JOIN organizations o ON e.org_id = o.org_id
                  WHERE e.username = ? AND e.password = ? AND o.org_unique_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sss', $username, $password, $org_unique_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return "Login successful!";
        } else {
            return "Invalid credentials or Organization ID!";
        }
    }
}
?>
