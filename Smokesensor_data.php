<?php
class Database {
    // Database credentials
    private static $dbName = 'smokesensor_data'; // Your actual database name
    private static $dbHost = '192.168.0.4'; // Database host, typically 'localhost' or the server IP
    private static $dbUsername = 'root'; // Database username
    private static $dbUserPassword = ''; // Database password

    private static $cont = null; // Connection variable

    // Private constructor to prevent instantiation
    private function __construct() {
        die('Init function is not allowed');
    }

    // Establish a database connection (Singleton pattern)
    public static function connect() {
        if (null == self::$cont) {
            try {
                self::$cont = new PDO("mysql:host=" . self::$dbHost . ";" . "dbname=" . self::$dbName, self::$dbUsername, self::$dbUserPassword);
                self::$cont->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
            } catch (PDOException $e) {
                die("Connection error: " . $e->getMessage()); // Connection failure
            }
        }
        return self::$cont;
    }

    // Disconnect from the database
    public static function disconnect() {
        self::$cont = null;
    }

    // Insert sensor data into the 'sensor_readings' table
    public static function insertSensorData($smoke_value, $smoke_level_threshold) {
        $conn = self::connect();

        // Prepare the SQL insert query
        $sql = "INSERT INTO sensor_readings (smoke_value, smoke_level_threshold) VALUES (:smoke_value, :smoke_level_threshold)";
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':smoke_value', $smoke_value, PDO::PARAM_INT);
        $stmt->bindParam(':smoke_level_threshold', $smoke_level_threshold, PDO::PARAM_INT);

        // Execute the query
        try {
            $stmt->execute();
            echo json_encode(["message" => "Data inserted successfully."]);
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error inserting data: " . $e->getMessage()]);
        }
    }

    // Fetch the latest sensor data
    public static function fetchLatestSensorData() {
        $conn = self::connect();

        // SQL query to get the latest entry
        $sql = "SELECT smoke_value, smoke_level_threshold FROM sensor_readings ORDER BY id DESC LIMIT 1";
        $stmt = $conn->prepare($sql);

        try {
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                echo json_encode($result);
            } else {
                echo json_encode(["error" => "No data found"]);
            }
        } catch (PDOException $e) {
            echo json_encode(["error" => "Error fetching data: " . $e->getMessage()]);
        }
    }
}

// Handle HTTP requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assume data is being posted from an ESP32 or other sensor device
    $smoke_value = isset($_POST['smoke_value']) ? (int)$_POST['smoke_value'] : null;
    $smoke_level_threshold = isset($_POST['smoke_level_threshold']) ? (int)$_POST['smoke_level_threshold'] : null;

    if ($smoke_value !== null && $smoke_level_threshold !== null) {
        Database::insertSensorData($smoke_value, $smoke_level_threshold);
    } else {
        echo json_encode(["error" => "Missing parameters"]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // If it's a GET request, fetch the latest sensor data for monitoring
    Database::fetchLatestSensorData();
}

// Disconnect when done
Database::disconnect();
?>
