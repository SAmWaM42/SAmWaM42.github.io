<?php
class Organization {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register an organization with auto-generated unique ID
    public function registerOrganization($org_name, $org_unique_id) {
        $query = "INSERT INTO organizations (org_name, org_unique_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $org_name, $org_unique_id);
        if ($stmt->execute()) {
            return "Organization registered successfully! Your Organization ID is: " . $org_unique_id;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}
?>
