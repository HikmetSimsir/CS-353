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
reqLogIn();
$forumid = $_GET["forumid"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $post = $_POST['post'];
  $date = date("Y-m-d");
  $addBookReviewQuery = mysqli_query($conn, "insert into post values('$forumid',null,'{$_SESSION["user_id"]}','$post','$date',null);");
  if ($addBookReviewQuery) {
    echo "<script type='text/javascript'>alert('" . "Success" . "');</script>";
  } else {
    echo "<script type='text/javascript'>alert('" . "Something went wrong" . "');</script>";

  }
  echo "<script type='text/javascript'>window.location = './forumView.php?forumid={$forumid}';</script>";
}

?>

<form action="" method="post">

    <p>
        <label for="post">Your Post</label><br> <textarea name="post" id="post" cols="40" rows="6" required
                                                          maxlength="240"></textarea>
    </p>

    <p>
        <button>Post</button>
    </p>

</form>

</body>
</html>



