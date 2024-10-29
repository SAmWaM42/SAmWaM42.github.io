<?php
class Database {
    private $host = 'localhost';  // Your database host
    private $user = 'root';       // Your database username
    private $pass = '';           // Your database password
    private $dbname = 'se';  // Your database name
    private $conn;

    // Function to establish connection with the database
    public function connect() {
        $this->conn = null;

        // Create connection using MySQLi
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
?>
