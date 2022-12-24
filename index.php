<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
<?php
include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['uname'];
  $password = $_POST['psw'];
  try {
    $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $count = mysqli_num_rows($result);
    if ($count == 1) {
      // if $_SESSION['uname'] isset then user has succesfully logged in
      $_SESSION['uname'] = $email;
      $_SESSION['psw'] = $password;
      $userid = intval($row["user_id"]);


      $_SESSION['user_id'] = $userid;

      $sql = "SELECT * FROM sys_admin WHERE user_id = '$userid'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $count = mysqli_num_rows($result);
      if ($count == 1) {
        $_SESSION['isAdmin'] = true;

      } else {
        $_SESSION['isAdmin'] = false;
      }
      $sql = "SELECT * FROM sys_author WHERE user_id = '$userid'";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $count = mysqli_num_rows($result);
      if ($count == 1) {

        $_SESSION['isAuthor'] = true;

      } else {
        $_SESSION['isAuthor'] = false;
      }
      header("location: ./Home.php");
    } else {
      echo "<script type='text/javascript'>alert('Wrong username or password');</script>";
    }
  } catch (Exception $e) {
    echo "<script type='text/javascript'>alert('Wrong username or password');</script>";
  }
}
?>
<form action="" method="post">

    <p>
        <label for="uname">Email</label><br> <input type="email" name="uname" id="uname" required>
    </p>
    <p>
        <label for="psw">Password</label><br> <input type="password" name="psw" id="psw" required>
    </p>
    <p>
        <button>Log in</button>
    </p>
    <p>
        <a href="SignUp.php">Not a member? Sign Up</a>
    </p>
</form>
</body>
</html>
