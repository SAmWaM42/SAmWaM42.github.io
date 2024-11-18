<?php
class Organization {
    private $conn;

    public function __construct($db_conn) {
        $this->conn = $db_conn;
    }

    public function registerOrganization($org_name, $org_unique_id) {
        $query = "INSERT INTO organization (name,ID) VALUES(?,?) ";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ss', $org_name,$org_unique_id);
      


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
