<?php
// Include the database connection
include_once 'Smokesensor_data.php';

// Connect to the database
$db = Database::connect();

// Query to get the latest sensor data entry
$query = "SELECT * FROM sensor_readings ORDER BY timestamp DESC LIMIT 1";
$stmt = $db->query($query);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if data exists
if ($row) {
    // Return the data as JSON
    echo json_encode([
        'smoke_value' => $row['smoke_value'],
        'smoke_level_threshold' => $row['smoke_level_threshold'],
        'led_status' => $row['led_status'],
        'timestamp' => $row['timestamp']
    ]);
} else {
    // If no data, send a response indicating no entries
    echo json_encode([
        'smoke_value' => 0,
        'smoke_level_threshold' => 400,
        'led_status' => 'green',
        'timestamp' => 'No data available'
    ]);
}

// Disconnect from the database
Database::disconnect();
?>
