<?php
class SensorData {
    
    // Method to insert sensor data into the database
    public static function insertSensorReading($id, $smoke_value, $smoke_level_threshold, $led_status) {
        // Only insert data if the threshold is at least 400
        if ($smoke_level_threshold >= 400) {
            // Get the database connection
            $db = Database::connect();

            // SQL query to insert data into the 'sensor_readings' table
            $sql = "INSERT INTO sensor_readings (id, smoke_value, smoke_level_threshold, led_status, timestamp)
                    VALUES (:id, :smoke_value, :smoke_level_threshold, :led_status, NOW())";

            // Prepare the SQL statement
            $stmt = $db->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':smoke_value', $smoke_value, PDO::PARAM_INT);
            $stmt->bindParam(':smoke_level_threshold', $smoke_level_threshold, PDO::PARAM_INT);
            $stmt->bindParam(':led_status', $led_status);

            // Execute the query
            $stmt->execute();

            // Disconnect from the database
            Database::disconnect();
        } else {
            echo "Threshold is below 400; data not inserted.";
        }
    }
}
?>
