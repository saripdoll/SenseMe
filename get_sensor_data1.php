<?php
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "smokesensor_data"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch the latest record 
$sql = "SELECT smoke_level_threshold, smoke_status, led_status FROM sensor_readings ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    $row = $result->fetch_assoc();
    
    echo json_encode([
        "smoke_level_threshold" => $row['smoke_level_threshold'],
        "smoke_status" => $row['smoke_status'],
        "led_status" => $row['led_status']
    ]);
} else {
    echo json_encode(["error" => "No data available"]);
}

$conn->close();
?>
