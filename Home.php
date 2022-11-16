<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>

<a href="./makeReviewPage.php?title=&author=&publisher=&genre=">Make Review</a>
<?php


include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();
?>
<p>
    Welcome <?php echo $_SESSION["uname"] ?>
</p>
</html>
