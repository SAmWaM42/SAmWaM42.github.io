<?php
class User {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    // Register employee
    public function registerEmployee($username, $hashed_password, $org_id, $gender_id) {
     
        $query = "INSERT INTO employee (name, password, org_ID, gender_ID) VALUES (?, ?, ?,?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssi', $username, $hashed_password, $org_id, $gender_id);

        echo $org_id;

        if ($stmt->execute()) {
            return "Employee registered successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }
    public function registerAdmin($username, $hashed_password, $org_id,$role_ID,$gender_id) {
      
        $query = "INSERT INTO employee (name,password,org_ID,role_ID,gender_ID) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssii', $username,$hashed_password,$org_id,$role_ID,$gender_id);

        if ($stmt->execute()) {
            return "Employee registered successfully.";
        } else {
            return "Error: " . $this->conn->error;
        }
    }


    public function loginEmployee($username, $password) {
        $query = "SELECT e.ID, e.name, e.password, e.org_ID,e.role_ID, e.gender_id, o.name AS org_name
                  FROM employee e
                  JOIN organization o ON e.org_ID = o.ID
                  WHERE e.name = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            die("Error preparing statement: " . $this->conn->error);
        }
    
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
    
            // Validate password
            if (password_verify($password, $user['password'])) {
                return $user; // Return user data
            }
        }
    
        return false; // Authentication failed
    }
}
?>