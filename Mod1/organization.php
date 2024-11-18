<?php
class Organization {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    public function registerOrganization($org_name) {
        // Insert organization with name, ID will be auto-generated
        $query = "INSERT INTO organization (name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('s', $org_name);

        if ($stmt->execute()) {
            // Fetch the inserted organization ID
            $org_id = $stmt->insert_id;
            return "Organization registered successfully! Your Organization ID is: " . $org_id;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}
?>
