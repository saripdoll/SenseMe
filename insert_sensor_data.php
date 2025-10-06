<?php
// Include the necessary classes
include_once 'Smokesensor_data.php';
include_once 'SensorData.php';

// Get data from the POST request (sent by the Arduino/ESP32)
$smoke_value = isset($_POST['smoke_value']) ? $_POST['smoke_value'] : 0;
$smoke_level_threshold = isset($_POST['smoke_level_threshold']) ? $_POST['smoke_level_threshold'] : 400; // Set default threshold to 400
$id = 'esp32-1'; // Device ID, you can make this dynamic if needed

// Determine the LED status based on smoke level
$led_status = ($smoke_value > $smoke_level_threshold) ? 'red' : 'green';

// Insert sensor data into the database if the threshold is 400 or above
if ($smoke_level_threshold >= 400) {
    SensorData::insertSensorReading($id, $smoke_value, $smoke_level_threshold, $led_status);
    echo json_encode(["status" => "success", "message" => "Sensor data successfully inserted!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Threshold below 400; data not inserted."]);
}
?>
