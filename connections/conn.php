<?php
class conn
{
  private $conn;
   public function connection($host,$name,$password,$database)
   {
    
    $this->conn=mysqli_connect($host,$name,$password,$database);
    if(!$this->conn)
    {
        echo "failed connection";
    }
   }
   public function get_connection()
   {
         return $this->conn;
   }
   public function insert_data($statement,$values,$special)
   {
    if(!$special)
{
    $exec=$this->conn->prepare($statement);

   
    $exec->bind_param('iss',$values[0],$values[1],$values[2]);
    $exec->execute();
}
else
{
    $exec=$this->conn->prepare($statement);

   
    $exec->bind_param('isss',$values[0],$values[1],$values[2],$values[3]);
    $exec->execute();
}

    
   }
   public function remove_row($id)
   {
    
   }
   public  function update_column($id,$statement)
   {

   }
}