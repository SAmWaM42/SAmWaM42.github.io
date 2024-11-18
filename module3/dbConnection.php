<?php
    //ignore this file
    class dbConnection{
        private $connection;
        private$db_type;
        private$db_host;
        private$db_port;
        private$db_user;
        private$db_pass;
        private$db_name;

        public function __construct($db_type,$db_host,$db_port,$db_user,$db_pass,$db_name){
            $this ->db_type = $db_type;
            $this ->db_host = $db_host;
            $this ->db_user = $db_user;
            $this ->db_pass = $db_pass;
            $this ->db_name = $db_name;

            $this ->connection($db_type,$db_host,$db_port,$db_user,$db_pass,$db_name);
        }
        public function connection ($db_type,$db_host,$db_port,$db_user,$db_pass,$db_name){
            switch($db_type){
                case 'PDO':
                if ($db_port <> Null){
                    $db_host .=":".$db_port;
                }
                try {
                    $this ->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
                    // set the PDO error mode to exception
                    $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    echo "Connected successfully";
                  } catch(PDOException $e) {
                    echo "Connection failed: " . $e->getMessage();
                  }
                  break;
                case 'MySQLi':
                    if ($db_port <> Null){
                        $db_host .=":".$db_port;
                    }
                    $this->connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
                    // Check connection
                    if ($this->connection->connect_error) { return "Connection failed: " . $this->connection->connect_error; } else{ echo "Connected successfully"; }
                    break;
            }
        }
        public function insert($table, $data){
            ksort($data);
            $fieldDetails = NULL;
            $fieldNames = implode('`,`',array_keys($data));
            $fiedlValues = implode('`,`',array_values($data));
            $sql1 = "INSERT INTO $table(`$fieldNames`)VALUES('$fieldValues')";
            return $this->extracted($sql1);
            /*switch($this->db_type){
                case 'PDO':
                    try{
                        $this -> connection ->exec($sql1);
                        return TRUE;

                    }
                    catch(PDOException $e){
                        return $sql1 . "<br>".$e->getMessage();
                    }
                    break;
                case 'MYSQLi':
                    if ($this->connection ->query($sql1)===TRUE){
                        return TRUE;

                    }
                    else{
                        return "Error: ".$sql1."<br>".$this->connection->error;
                    }
                    break;
            }*/
        }
        // MySQli escape string method
        public function escape_values($posted_values): string{
            switch($this->$db_type){
                case 'PDO':
                    $this ->posted_values =addslashes($posted_values);
                    break;
                case 'MySQli':
                    $this ->posted_values = $this ->connection->real_escape_string($posted_values);
                    break;

            }
            return $this ->posted_values;
        }
        //Row Count(Count Returned results)
        public function count_results($sql1){
            switch($this->$db_type){
                case 'PDO':
                    $res = $this->coneection->prepare($sql1);
                    $res->execute();
                    return $res->rowCount();
                    break;
                case 'MySQli':
                    if(is_object($this->connection->query($sql1))){
                        $result = $this->connection->query($sql1);
                        return $result ->num_rows;
                    }
                    else{
                        print "Error 5: " . $sql . "<br />" . $this->connection->error . "<br />";
                    }
                    break;

            }
        }


    }