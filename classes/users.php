<?php
require_once 'connection.php';

class USERS extends Connection {
	
public function addNewUser($data) {
    $conn = $this->connect();
    $stmt = $conn->prepare("INSERT INTO ads (user_id, username, name, family, join_date, join_time) VALUES (?, ?, ?, ?, ?, CURDATE(), CURTIME()");
    if ($stmt === false) {
        die("خطای SQL: " . $conn->error);
    }
    $stmt->bind_param("isssss", 
        $data['user_id'],
        $data['username'],
        $data['name'],
        $data['family'],
        $data['join_date'],
        $data['join_time']
    );
    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

}
?>