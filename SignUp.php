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
$userExists = false;
$successfullSignup = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $conn = getDatabaseConnection();


  $username = $_POST["username"];
  $password = $_POST["psw"];
  $dname = $_POST["dname"];


  $checkUserNameExistQuery = "Select * from user where email='$username'";
  $result = mysqli_query($conn, $checkUserNameExistQuery);
  $count = mysqli_num_rows($result);


  $totalNumberOfUsersQuery = mysqli_query($conn, "Select * from user;");
  $currentNumberOfUsers = mysqli_num_rows($totalNumberOfUsersQuery);


  // check if user with same name exists
  if ($count == 0) {

    $userid = $currentNumberOfUsers + 1;
    $newUserInsertionQuery = mysqli_query($conn, "Insert into user values('$username',$userid,'$password','$dname');");
    $userExists = false;
    $successfullSignup = true;

  } else {
    $userExists = true;
  }

}

?>

<form action="" method="post">

    <p>
        <label for="username">Email</label><br> <input type="email" name="username" id="username" required>
    </p>
    <p>
        <label for="psw">Password</label><br> <input type="password" name="psw" id="psw" required>
    </p>
    <p>
        <label for="dname">Display name</label><br> <input type="text" name="dname" id="dname" required>
    </p>
    <p>
        <button>Sign up</button>
    </p>
    <p>
        <a href="/">Already a User? Log in</a>
    </p>
</form>

<?php if ($userExists) {
  echo "<script type='text/javascript'>alert('!!!User with same name exists, Signup Failed!!!');</script>";
}
?>

<?php if ($successfullSignup) {
  echo "<script type='text/javascript'>alert('!!!You have succesfully signed up, please login!!!');</script>";
} ?>
</body>
</html>



