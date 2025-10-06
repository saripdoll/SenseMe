<?php
// Include the database connection class
include_once 'Smokesensor_data.php';

// Connect to the database
$db = Database::connect();

// SQL query to retrieve all sensor readings from the table
$sql = "SELECT * FROM sensor_readings ORDER BY timestamp DESC";
$stmt = $db->query($sql);

// Display the data in a table format
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Smoke Value</th><th>Smoke Level Threshold</th><th>LED Status</th><th>Timestamp</th></tr>";

// Loop through each row in the result set and display it in the table
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
    echo "<td>" . htmlspecialchars($row['smoke_value']) . "</td>";
    echo "<td>" . htmlspecialchars($row['smoke_level_threshold']) . "</td>";
    echo "<td>" . htmlspecialchars($row['led_status']) . "</td>";
    echo "<td>" . htmlspecialchars($row['timestamp']) . "</td>";
    echo "</tr>";
}

echo "</table>";

// Close the database connection
Database::disconnect();
?>
