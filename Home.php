<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>

<a href="./makeReviewPage.php?title=&author=&publisher=&genre=">Make Review </a> <a href="./ListUsersPage.php?"> Follow
                                                                                                                 Other
                                                                                                                 Users</a>
<a href="./followedUsersPage.php?"> Followed Users </a> <a href="./forumList.php"> Forums </a>
<?php
if ($_SESSION["isAdmin"]) {
  echo '<a href = "./forumCreate.php"> Create Forum</a>';
}
?>


<?php


include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();
?>
<p>
    Welcome <?php echo $_SESSION["uname"] ?>
</p>
<?php
if ($_SESSION["isAdmin"]) {
  echo "<p>Your admin tel no: ";
  $sql = "SELECT phone_number FROM sys_adm_user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo $row["phone_number"];
  echo "</p>";
}
?>
<?php
if ($_SESSION["isAuthor"]) {
  echo "<p>Your author info: ";
  $sql = "SELECT author_info FROM sys_author_user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo $row["author_info"];
  $sql = "SELECT website_url FROM sys_author_user WHERE email = '{$_SESSION["uname"]}'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  echo "<a href='{$row["website_url"]}'>Website</a>";
  echo "</p>";
}
?>
</html>
