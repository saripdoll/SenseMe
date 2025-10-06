<?php

$hostname = "localhost";  
$username = "root";       
$password = "";           
$database = "smokesensor_data";  

$conn = mysqli_connect($hostname, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Database connection is OK<br>";

// Check if data is sent via POST
if (isset($_POST["smoke_level_threshold"]) && isset($_POST["smoke_status"]) && isset($_POST["led_status"])) {
    // Get data from POST 
    $smoke_level_threshold = $_POST["smoke_level_threshold"];
    $smoke_status = $_POST["smoke_status"];
    $led_status = $_POST["led_status"];

    // SQL query to insert the data into the table
    $sql = "INSERT INTO sensor_readings (smoke_level_threshold, smoke_status, led_status) 
            VALUES ($smoke_level_threshold, '$smoke_status', '$led_status')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
} else {
    echo "Required data not received.<br>";
}

mysqli_close($conn);
?>

