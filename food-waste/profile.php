<?php
session_start();
$connection = mysqli_connect("localhost:3306", "root", "", "demo");

// Redirect to signup page if the user is not logged in
if (empty($_SESSION['name'])) {
    header("location: signup.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>

<script>
    const hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
        const navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    };
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: left; font-size: 30px;">
            <img src="user.png" alt="User Icon" style="width: 40px; height: 25px; padding-right: 10px;"> Profile
        </p>
        <div class="info" style="padding-left: 10px;">
            <p>Name: <?php echo htmlspecialchars($_SESSION['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
            <p>Gender: <?php echo htmlspecialchars($_SESSION['gender']); ?></p>
            <a href="logout.php" style="float: left; margin-top: 6px; border-radius: 5px; background-color: #06C167; color: white; padding: 5px 10px;">Logout</a>
        </div>
        <br>
        <hr>
        <br>
        <p class="heading">Your Donations</p>
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $email = mysqli_real_escape_string($connection, $_SESSION['email']);
                        $query = "SELECT * FROM food_donations WHERE email='$email'";
                        $result = mysqli_query($connection, $query);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr><td>" . htmlspecialchars($row['food']) . "</td><td>" . htmlspecialchars($row['type']) . "</td><td>" . htmlspecialchars($row['category']) . "</td><td>" . htmlspecialchars($row['date']) . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No donations found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
