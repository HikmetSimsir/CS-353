<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>

<?php
session_start();
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
$user_id = $_SESSION['user_id'];
include "NavBar.php";
navBar($isAdmin, $isAuthor);

include_once "helper.php";
$conn = getDatabaseConnection();
reqLogIn();
?>

<p>
    Welcome <?php echo $_SESSION["uname"] ?>
</p>

<?php
if ($_SESSION["isAdmin"]) {
  echo "<p>Your admin tel no: ";
  $sql = "SELECT phone_number FROM sys_adm natural join user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo $row["phone_number"];
  echo "</p>";
}
?>
<?php
if ($_SESSION["isAuthor"]) {
  echo "<p>Your author info: ";
  $sql = "SELECT author_info FROM sys_author natural join user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo $row["author_info"];
  $sql = "SELECT website_url FROM sys_author natural join user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo "<a href='{$row["website_url"]}'>Website</a>";
  echo "</p>";
}
?>
</html>
