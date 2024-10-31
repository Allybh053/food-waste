<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "demo";
$port = "3306"; // Ensure this matches your MySQL port

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>



<?php
//change mysqli_connect(host_name,username, password); 
//$connection = mysqli_connect("localhost", "root", "");
//$db = mysqli_select_db($connection, 'demo');
//?>
