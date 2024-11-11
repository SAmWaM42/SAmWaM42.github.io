<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register employee
    public function registerEmployee($name, $hashed_password, $org_id, $role_id, $gender_id) {
        $query = "INSERT INTO employee (name, password, org_ID, role_ID, gender_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssiii', $name, $hashed_password, $org_id, $role_id, $gender_id);

        if ($stmt->execute()) {
            return "Employee registered successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Employee login
    public function loginEmployee($username, $password) {
        $query = "SELECT e.ID, e.name, e.password, e.org_ID, e.Role, e.role_ID, 
                         o.name AS org_name 
                  FROM employee e
                  JOIN organization o ON e.org_ID = o.ID
                  WHERE e.name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Validate password
            if (password_verify($password, $user['password'])) {
                return $user; // Authentication successful, return user data
            }
        }

        return false; // Authentication failed
    }
}
?>
