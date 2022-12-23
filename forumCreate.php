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
reqAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $post = $_POST['post'];
  $date = date("Y-m-d");
  $addBookReviewQuery = mysqli_query($conn, "insert into book_forum values(null, '$post','{$_SESSION["userid"]}','$date');");
  if ($addBookReviewQuery) {
    echo "<script type='text/javascript'>alert('" . "Success" . "');</script>";
  } else {
    echo "<script type='text/javascript'>alert('" . "Something went wrong" . "');</script>";

  }
  echo "<script type='text/javascript'>window.location = './forumList.php';</script>";
}

?>

<form action="" method="post">

    <p>
        <label for="post">Create Forum</label><br> <textarea name="post" id="post" cols="40" rows="6" required
                                                             maxlength="240"></textarea>
    </p>

    <p>
        <button>Create</button>
    </p>

</form>

</body>
</html>



