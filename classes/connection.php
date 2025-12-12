<?php
// کلاس Connection
class Connection {
    private $servername = "localhost";
    private $username = "atarixir_gamedivar";
    private $dbname = "atarixir_gamedivar";
    private $password = "Rde*54sEw";

    protected function connect() {
      $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
      $conn->query("SET NAMES 'utf8mb4'");
      // $conn->query("SET timezone = 'Asia/Tehran'"); // Commented out
    
      if ($conn->connect_error) {
        // 1. Log the Error:
        $errorMessage = "Connection failed: " . $conn->connect_error;
        error_log($errorMessage, 0); // Log to system error log
    
        // 2. Handle the Error Gracefully:
        // - Throw an exception for further handling (recommended):
        throw new mysqli_sql_exception($errorMessage, $conn->connect_errno);
    
        // OR (alternative approach):
        //   - Return a specific error code or object to indicate failure:
        //     return false;  // Or a custom error object
      }
    
      return $conn;
    }

}

?>
