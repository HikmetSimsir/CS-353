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
include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);
$conn = getDatabaseConnection();
reqLogIn();
$forumid = $_GET["forumid"];
$postid = $_GET["postid"];


$sql = "select display_name, text from post join user using (user_id) where post.forum_id = '$forumid' and post_id = '$postid'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_all($result, MYSQLI_ASSOC);
$dname = $row[0]["display_name"];
$text = $row[0]["text"];
?>
<table>
    <tr>

    </tr>
    <tr>
        <td><p><em><?php echo $dname ?></em>:</p>
            <p>
              <?php echo $text ?>
            </p>
        </td>
    </tr>
</table>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $post = $_POST['post'];
  $date = date("Y-m-d");
  $addBookReviewQuery = mysqli_query($conn, "insert into post values('$forumid',null,'{$_SESSION["user_id"]}','$post','$date','$postid');");
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



