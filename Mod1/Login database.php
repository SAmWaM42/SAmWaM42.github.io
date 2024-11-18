<?php
class Database {
    private $host = 'localhost';  // Your database host
    private $user = 'root';       // Your database username
    private $pass = '';           // Your database password
    private $dbname = 'timeoff_database';  // Your database name
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
    public function connectPDO() {
        $this->conn = null;

        try {
            $dsn ="mysql:host=" . $this->host. ";dbname=" . $this->dbname;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true,

            ];
            $this ->conn = new PDO($dsn, $this->user, $this->pass, $options);

        }catch(PDOException $e){
            echo "PDO connection error: " .$e->getMessage(); 
        }
        return $this->conn;
    }
}
?>
