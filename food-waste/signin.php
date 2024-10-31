<?php
session_start();
include 'connection.php';

// Establish database connection
$connection = mysqli_connect("localhost:3306", "root", "", "demo");
$msg = 0;

if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $row['name'];
            $_SESSION['gender'] = $row['gender'];
            header("location:home.html");
            exit();
        } else {
            $msg = 1; // Incorrect password
        }
    } else {
        echo "<h1><center>Account does not exist</center></h1>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>

<body>
    <div class="container">
        <div class="regform">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                <p class="logo">Food <b style="color:#06C167;">Donate</b></p>
                <p id="heading">Welcome back!</p>

                <div class="input">
                    <input type="email" placeholder="Email address" name="email" required />
                </div>
                <div class="password">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw"></i>

                    <?php
                    if ($msg == 1) {
                        echo '<i class="fa fa-exclamation-circle error-icon"></i>';
                        echo '<p class="error">Password does not match.</p>';
                    }
                    ?>
                </div>

                <div class="btn">
                    <button type="submit" name="sign">Sign in</button>
                </div>
                <div class="signin-up">
                    <p>Don't have an account? <a href="signup.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="login.js"></script>
</body>
</html>
