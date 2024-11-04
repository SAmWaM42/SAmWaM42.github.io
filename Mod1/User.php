<?php
class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register employee
    public function registerEmployee($username, $hashed_password, $org_id) {
        $query = "INSERT INTO employees (username, password, org_id) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssi', $username, $hashed_password, $org_id);

        if ($stmt->execute()) {
            return "Employee registered successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Login employee
    public function loginEmployee($username, $password) {
        $query = "SELECT password FROM employees WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($hashed_password);

        if ($stmt->fetch()) {
            if (password_verify($password, $hashed_password)) {
                return "Login successful!";
            } else {
                return "Invalid username or password!";
            }
        } else {
            return "Invalid username or password!";
        }
    }
}
?>
