<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<?php
include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();

//create nested array for the topic
try {
  $forumid = $_GET["forumid"];
//create nested structure
// get only top comments
  $sql = "SELECT post_id FROM post WHERE post.forum_id = '$forumid' and parent_id is null ";
  $result = mysqli_query($conn, $sql);
  $rowtop = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $topAllChilren = [];
  $count = 0;
  foreach ($rowtop as $value) {
    $temp = new ForumPost($forumid, $value["post_id"]);
    $topAllChilren[$count] = $temp->gettree();
    $count = $count + 1;
  }
  var_dump($topAllChilren);

} catch (Exception $e) {
  echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";

} ?>
<body></body>
</html>
