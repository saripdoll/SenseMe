<?php 

$host = 'localhost'; 
$db_name = 'smokesensor_data'; 
$username = 'root'; 
$password = ''; 

// connection to the database
$conn = new mysqli($host, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT smoke_status FROM sensor_readings ORDER BY timestamp DESC LIMIT 1"; 
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch latest smoke status
    $row = $result->fetch_assoc();
    $smokeStatus = $row['smoke_status']; 

    // Return the smoke status as JSON
    echo json_encode(['smoke_status' => $smokeStatus]);
} else {
    // no data return 'no_smoke'
    echo json_encode(['smoke_status' => 'no_smoke']);
}

$conn->close();
?>
