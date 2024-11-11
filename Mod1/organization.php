<?php
class Organization {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register an organization
    public function registerOrganization($org_name) {
        $query = "INSERT INTO organization (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $org_name);

        if ($stmt->execute()) {
            return "Organization registered successfully! Organization Name: " . $org_name;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Fetch all organizations
    public function getOrganizations() {
        $query = "SELECT ID, name FROM organization";
        $result = $this->conn->query($query);

        if ($result->num_rows > 0) {
            $organizations = [];
            while ($row = $result->fetch_assoc()) {
                $organizations[] = $row;
            }
            return $organizations;
        } else {
            return [];
        }
    }
}
?>
