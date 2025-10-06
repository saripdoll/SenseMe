<?php
// Include the database connection file
include('Smokesensor_data.php');

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Check if data is valid and above threshold
if (isset($data->smoke_value) && isset($data->smoke_level_threshold) && $data->smoke_level_threshold >= 400) {
    $smoke_value = $data->smoke_value;
    $smoke_level_threshold = $data->smoke_level_threshold;

    // Connect to the database
    $db = Database::connect();

    // Insert data into the database
    $query = "INSERT INTO sensor_readings (smoke_value, smoke_level_threshold, timestamp) VALUES (?, ?, NOW())";
    $stmt = $db->prepare($query);
    $stmt->execute([$smoke_value, $smoke_level_threshold]);

    // Return success response
    echo json_encode(["status" => "success"]);
} else {
    // Return error if data is missing or below threshold
    echo json_encode(["status" => "error", "message" => "Invalid data received or below threshold"]);
}

// Disconnect from the database
Database::disconnect();
?>
