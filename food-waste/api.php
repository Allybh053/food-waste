<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *"); // Allow all origins for development
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow necessary headers

// Database connection
$servername = "localhost";
$username = "root"; // replace with your actual database username
$password = ""; // replace with your actual database password
$dbname = "demo"; // replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Fetch users
        $result = $conn->query("SELECT * FROM users");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $users]);
        break;

    case 'POST':
        // Create a new user
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['name']) && !empty($data['name'])) {
            $name = $conn->real_escape_string($data['name']);
            $sql = "INSERT INTO users (name) VALUES ('$name')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to create user: ' . $conn->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Name is required']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Unsupported request method']);
        break;
}

$conn->close();
?>
