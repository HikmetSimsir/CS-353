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
    $newUserInsertionQuery = mysqli_query($conn, "Insert into user values('$username',null,'$password','$dname');");
    $userExists = false;
    $successfullSignup = true;

    $sql = "SELECT user_id FROM user where email = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $userid = $row[0]["user_id"];

    if (!is_null($_POST["Admin"])) {
      $pno = $_POST["phonenumber"];
      $AddAdmin = mysqli_query($conn, "Insert into sys_admin values('$userid','$pno');");

    }

    if (!is_null($_POST["Author"])) {
      $wsite = $_POST["adminWSite"];
      $ainfo = $_POST["ainfo"];

      $AddAdmin = mysqli_query($conn, "Insert into sys_author values('$userid','$wsite','$ainfo');");
    }

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
        <label for="phonenumber">Phone number for Admin</label><br> <input type="tel" name="phonenumber"
                                                                           id="phonenumber" maxlength="200"
                                                                           value="444 4 44" required>
    </p>
    <p>
        <label for="adminWSite">Website for Author</label><br> <input type="url" name="adminWSite" id="adminWSite"
                                                                      maxlength="200" value="https://demo-site.com"
                                                                      required>
    </p>
    <p>
        <label for="ainfo">Info for Author</label><br> <input type="text" name="ainfo" id="ainfo" maxlength="200"
                                                              value="A mystery." required>
    </p>
    <fieldset>
        <legend>User Type</legend>
        <div>
            <input type="checkbox" id="Normal" name="Normal" value="Normal" disabled checked/> <label for="Normal">Normal</label>
        </div>
        <div>
            <input type="checkbox" id="Author" name="Author" value="Author"/> <label for="Author">Author</label>
        </div>
        <div>
            <input type="checkbox" id="Admin" name="Admin" value="Admin"/> <label for="Admin">Admin</label>
        </div>
    </fieldset>
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



