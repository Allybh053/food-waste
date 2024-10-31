<?php
ob_start();
session_start();
$connection = mysqli_connect("localhost:3306", "root", "", "demo"); // Combined connection parameters
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

include("connect.php"); 
if ($_SESSION['name'] == '') {
    header("location:deliverylogin.php");
    exit; // Make sure to exit after redirection
}

$name = $_SESSION['name'];
//$id = $_SESSION['Did'];//

// Fetch the user's city using IP address
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec($ch);
$result = json_decode($result);
$city = $result->city ?? 'Unknown'; // Default to 'Unknown' if city is not found

curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="delivery.css">
</head>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php">My Orders</a></li>
            <li><a href="../logout.php">Logout</a></li> 
        </ul>
    </nav>
</header>
<script>
    document.querySelector(".hamburger").onclick = function() {
        document.querySelector(".nav-bar").classList.toggle("active");
    }
</script>

<style>
    .itm {
        background-color: white;
        display: grid;
        text-align: center;
        justify-items: center;
    }
    .itm img {
        width: 400px;
        height: 400px;
        display: block; /* Make the image a block element */
        margin: 0 auto;
    }
    p {
        font-size: 30px; color: black; margin-top: 50px; text-align: center;
    }
    a {
        text-decoration: underline; 
    }
    @media (max-width: 767px) {
        .itm img {
            width: 350px;
            height: 350px;
        }
    }
</style>

<h2><center>Welcome <?php echo htmlspecialchars($name); ?></center></h2>
<div class="itm">
    <img src="../img/delivery.gif" alt="Delivery"> 
</div>
<h2><center>Your Location: <?php echo htmlspecialchars($city); ?></center></h2> 

<div class="get">
<?php
// Define the SQL query to fetch unassigned orders
$sql = "SELECT fd.Fid AS Fid, fd.name, fd.phoneno, fd.date, fd.delivery_by, fd.address AS From_address, 
               ad.name AS delivery_person_name, ad.address AS To_address
        FROM food_donations fd
        LEFT JOIN admin ad ON fd.assigned_to = ad.Aid 
        WHERE assigned_to IS NOT NULL AND delivery_by IS NULL AND fd.location = '$city'";

// Execute the query
$result = mysqli_query($connection, $sql);

// Check for errors
if (!$result) {
    die("Error executing query: " . mysqli_error($connection));
}

// Fetch the data as an associative array
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);

// If the delivery person has taken an order, update the assigned_to field in the database
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id']) && isset($_POST['delivery_person_id'])) {
    $order_id = $_POST['order_id'];
    $delivery_person_id = $_POST['delivery_person_id'];

    // Check if the order is already assigned
    $check_sql = "SELECT * FROM food_donations WHERE Fid = $order_id AND delivery_by IS NOT NULL";
    $check_result = mysqli_query($connection, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        die("Sorry, this order has already been assigned to someone else.");
    }

    // Update the order
    $update_sql = "UPDATE food_donations SET delivery_by = $delivery_person_id WHERE Fid = $order_id";
    if (!mysqli_query($connection, $update_sql)) {
        die("Error assigning order: " . mysqli_error($connection));
    }

    // Reload the page to prevent duplicate assignments
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

mysqli_close($connection);
?>

<div class="log">
    <button type="button" onclick="window.location.href='deliverymyord.php'">My Orders</button>
</div>

<!-- Display the orders in an HTML table -->
<div class="table-container">
    <p id="heading">Donated</p>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Food</th>
                    <th>Phone No</th>
                    <th>Date/Time</th>
                    <th>Pickup Address</th>
                    <th>Delivery Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row) { ?>
                <tr>
                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                    <td data-label="Food"><?= htmlspecialchars($row['Fid']) ?></td>
                    <td data-label="Phone No"><?= htmlspecialchars($row['phoneno']) ?></td>
                    <td data-label="Date/Time"><?= htmlspecialchars($row['date']) ?></td>
                    <td data-label="Pickup Address"><?= htmlspecialchars($row['From_address']) ?></td>
                    <td data-label="Delivery Address"><?= htmlspecialchars($row['To_address']) ?></td>
                    <td data-label="Action">
                        <?php if ($row['delivery_by'] == null) { ?>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['Fid']) ?>">
                                <input type="hidden" name="delivery_person_id" value="<?= htmlspecialchars($id) ?>">
                                <button type="submit">Take Order</button>
                            </form>
                        <?php } else if ($row['delivery_by'] == $id) { ?>
                            Order assigned to you
                        <?php } else { ?>
                            Order assigned to another delivery person
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<br>
<br>
</body>
</html>
