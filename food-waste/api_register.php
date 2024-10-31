<?php
header('Content-Type: application/json; charset=UTF-8');
header("Access-Control-Allow-Origin: *"); // Allow all origins for development
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'connection.php'; // Make sure 'connection.php' contains correct DB connection logic

$servername = "localhost";
$username = "root";
$password = ""; // Use the actual database password
$dbname = "demo"; // Use the actual database name

// Database connection
$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $connection->connect_error]);
    exit();
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $gender = $_POST['gender'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password) && !empty($gender)) {
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $connection->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $response['status'] = 'error';
            $response['message'] = 'Account already exists';
        } else {
            // Insert new user
            $stmt = $connection->prepare("INSERT INTO login (name, email, password, gender) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $hashedPassword, $gender);
            
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Registration successful';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Failed to save data';
            }
        }
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'All fields are required';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);

$connection->close();
?>
