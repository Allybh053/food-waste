<?php
session_start();
$connection = mysqli_connect("localhost:3306", "root", "");
$db = mysqli_select_db($connection, 'demo');
include '../connection.php';
$msg = 0;
if (isset($_POST['sign'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $location = $_POST['district'];
    $address = $_POST['address'];

    $pass = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        echo "<h1>Already an account is created</h1>";
        echo '<script type="text/javascript">alert("Already an account is created")</script>';
        echo "<h1><center>Account already exists</center></h1>";
    } else {

        $query = "INSERT INTO admin(name, email, password, location, address) VALUES('$username', '$email', '$pass', '$location', '$address')";
        $query_run = mysqli_query($connection, $query);
        if ($query_run) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $username;

            header("location:signin.php");
            echo "<h1><center>Account does not exist</center></h1>";
            echo '<script type="text/javascript">alert("Account created successfully")</script>';
        } else {
            echo '<script type="text/javascript">alert("Data not saved")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formstyle.css">
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <title>Register</title>
</head>
<body>
    <div class="container">
        <form action=" " method="post" id="form">
            <p class="logo">Food <b style="color:#06C167;">Donate</b></p>
            <span class="title">Register</span>
            <br><br>
            <div class="input-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" required/>
                <div class="error"></div>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required/>
            </div>
            <div class="input-group">
                <label for="phoneno">Phone Number</label>
                <input type="text" id="phoneno" name="phoneno" placeholder="Phone Number" required/>
                <div class="error"></div>
            </div>

            <label class="textlabel" for="password">Password</label>
            <div class="password">
                <input type="password" name="password" id="password" required/>
                <i class="fa fa-eye-slash" aria-hidden="true" id="showpassword"></i>
                <i class="bi bi-eye-slash" id="showpassword"></i>
                <i class="uil uil-lock icon"></i>
                <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
                <?php
                if ($msg == 1) {
                    echo ' <i class="bx bx-error-circle error-icon"></i>';
                    echo '<p class="error">Password don\'t match.</p>';
                }
                ?> 
            </div>
            <div class="input-group">
                <label for="cpassword">Confirm Password</label>
                <input type="password" id="cpassword" name="cpassword" required>
                <div class="error"></div>
            </div>
            <div class="input-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="input-field">
                <label for="district">Location:</label>
                <br>
                <select id="district" name="district" style="padding:10px; padding-left: 20px;">
                    <option value="Kuala Lumpur">Kuala Lumpur</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Penang">Penang</option>
                    <option value="Johor">Johor</option>
                    <option value="Sabah">Sabah</option>
                    <option value="Sarawak">Sarawak</option>
                    <option value="Malacca">Malacca</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                    <option value="Pahang">Pahang</option>
                    <option value="Perak">Perak</option>
                    <option value="Perlis">Perlis</option>
                    <option value="Kelantan">Kelantan</option>
                    <option value="Terengganu">Terengganu</option>
                    <option value="Putrajaya">Putrajaya</option>
                    <option value="Labuan">Labuan</option>
                </select>
            </div>

            <button type="submit" name="sign">Register</button>
            <div class="login-signup">
                <span class="text">Already a member?
                    <a href="signin.php" class="text login-link">Login Now</a>
                </span>
            </div>
        </form>
    </div>
    <br><br>
    <script src="login.js"></script>
    <script src="../login.js"></script> 
</body>
</html>
