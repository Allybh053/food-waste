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

    $pass = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM delivery_persons WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        echo "<h1>Already an account is created</h1>";
        echo '<script type="text/javascript">alert("Already an account is created")</script>';
        echo "<h1><center>Account already exists</center></h1>";
    } else {
        $query = "INSERT INTO delivery_persons(name, email, password, city) VALUES('$username', '$email', '$pass', '$location')";
        $query_run = mysqli_query($connection, $query);
        if ($query_run) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $username; // Corrected to set the username
            header("location:delivery.php");
            exit();
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
    <title>Register</title>
    <link rel="stylesheet" href="deliverycss.css">
</head>
<body>
    <div class="center">
        <h1>Register</h1>
        <form method="post" action="">
            <div class="txt_field">
                <input type="text" name="username" required/>
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required/>
                <span></span>
                <label>Password</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required/>
                <span></span>
                <label>Email</label>
            </div>
            <div class="">
                <label for="district">District:</label>
                <select id="district" name="district" style="padding:10px; padding-left: 20px;">
                    <option value="Johor">Johor</option>
                    <option value="Kedah">Kedah</option>
                    <option value="Kelantan">Kelantan</option>
                    <option value="Malacca">Malacca</option>
                    <option value="Negeri Sembilan">Negeri Sembilan</option>
                    <option value="Pahang">Pahang</option>
                    <option value="Penang">Penang</option>
                    <option value="Perak">Perak</option>
                    <option value="Perlis">Perlis</option>
                    <option value="Sabah">Sabah</option>
                    <option value="Sarawak">Sarawak</option>
                    <option value="Selangor">Selangor</option>
                    <option value="Terengganu">Terengganu</option>
                    <option value="Federal Territories">Federal Territories</option>
                </select>
            </div>
            <br>
            <div class="pass">Forgot Password?</div>
            <input type="submit" name="sign" value="Register">
            <div class="signup_link">
                Already a member? <a href="deliverylogin.php">Sign In</a>
            </div>
        </form>
    </div>
</body>
</html>
