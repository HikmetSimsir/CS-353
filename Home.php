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

// find user display name
$sql = "SELECT display_name FROM user WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$display_name = $row['display_name'];

?>

<h2>
    Welcome <?php echo $display_name ?>
</h2>

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


<?php

// display books purchased by the user
$sql = "SELECT * FROM e_book
    natural join book 
    natural join book_genre natural join genre natural join publisher
    natural join purchase
    WHERE user_id = $user_id";

$result = $conn->query($sql);

// create table to display all the books
echo "<h2>Books Purchased by you</h2>";
echo "<table border='1' align='center'>
<tr>
<th>Book Name</th>
<th>Publisher Name</th>
<th>Genre</th>
<th>Price</th>
<th>Download</th>
</tr>";
define ('SITE_ROOT', realpath(dirname(__FILE__)));
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['publisher_name'] . "</td>";
    echo "<td>" . $row['genre_name'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    echo "<td><a href='download.php?file=".SITE_ROOT. "\\" .$row['content']."'>Download</a></td>";
    echo "</tr>";
}
echo "</table>";


$sql = "WITH RECURSIVE follower_closure as (SELECT follower_id as dst
                                    FROM user_follow_user
                                    WHERE user_id = $user_id
                                    UNION
                                    SELECT user_follow_user.follower_id
                                    FROM user_follow_user
                                             JOIN follower_closure ON follower_closure.dst = user_follow_user.user_id)
SELECT *
FROM follower_closure";

$result = $conn->query($sql);

// create table to display all the friends
echo "<h2>Your Follower Network</h2>";
echo "<table border='1' align='center'>
<tr>
<th>Follower Display Name</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    $sql = "SELECT display_name FROM user WHERE user_id = '{$row['dst']}'";
    $result2 = mysqli_query($conn, $sql);
    $row2 = mysqli_fetch_assoc($result2);
    echo "<td>" . $row2['display_name'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// display books reviewed by the user
echo "<h2>Books Reviewed by you</h2>";

//display reviews made by user using book_review table
$showMyReviewsQuery = "SELECT * FROM book_review, book where user_id = $user_id and book_review.book_id = book.book_id;";
$result = mysqli_query($conn, $showMyReviewsQuery );

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    //display contents of review
    echo "<h2>Review of " . $row['title'] . "</h2>";
    echo "<p>Rating: " . $row['rating'] . "</p>";
    echo "<p>Review: " . $row['text'] . "</p>";
    echo "<p>Review Date: " . $row['date'] . "</p>";
}








?>

</html>
