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

include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);

/*
reqLogIn();
reqAdmin();*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $comment = $_POST['post'];
  $content = $comment . "\n";




    $sql = "select book_id, title, AVG(rating) as ar
from (select *
      from book_review
      where date > (select CURRENT_DATE - INTERVAL 3 MONTH)) as l3review
         natural join book
group by book_id, title
order by ar desc
limit 5";


$reportContent = " Books and Average Ratings of the last 3 months: <br>";
$result = mysqli_query($conn, $sql);



while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

    // concatenate report content with the book title and average rating
    $reportContent .= "Title : " . $row["title"] . " Rating :  "  . $row["ar"] . "<br>";
}

  //  get user id in session
  $user_id = $_SESSION['user_id'];


// add newline to the report content
$reportContent .= "\n";

// add report content header of user with max followers and make a newline
$reportContent .= "User with max followers: <br>  ";

      $sql = "select user_id, display_name, max(fc) as followers
from sys_author_user
         natural join
     (select user_id, COUNT(follower_id) as fc
      from user_follow_user
      group by user_id) as fcount
group by user_id;";


$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

  // concatenate report display name and followers
    $reportContent .= "User : " . $row["display_name"] . "  Follower Number : " . $row["followers"] . "<br>";
}

//  get user id in session
$user_id = $_SESSION['user_id'];


// get current number of users,books,reviews and events in four variable
$users = mysqli_query($conn, "select count(*) as users from user");
$books = mysqli_query($conn, "select count(*) as books from book");
$reviews = mysqli_query($conn, "select count(*) as reviews from book_review");
$events = mysqli_query($conn, "select count(*) as events from event");
//get current number of forums
$forums = mysqli_query($conn, "select count(*) as forums from book_forum");

// get all information in the statistics table and put them into variables
$stats = mysqli_query($conn, "select * from statistics");
$stat = mysqli_fetch_array($stats, MYSQLI_ASSOC);
$usersStat = $stat['total_users'];
$booksStat = $stat['total_books'];
$reviewsStat = $stat['total_book_reviews'];
$eventsStat = $stat['total_events'];
$forumPostsStat = $stat['total_forum_posts'];
$forumsStat = $stat['total_forums'];

//print usersStat
echo $reportContent;
    echo $reportContent;
    echo $reportContent;
    echo $reportContent;
    echo $reportContent;
    echo $reportContent;
    echo $reportContent;

//concate these new stastistics variables to the report content and make a newline
    //concatenate a new line in html format
    $reportContent .= "<br>";
$reportContent .= "Current Number of Users: " . $usersStat . "<br>";
$reportContent .= "Current Number of Books: " . $booksStat . "<br>";
$reportContent .= "Current Number of Reviews: " . $reviewsStat . "<br>";
$reportContent .= "Current Number of Events: " . $eventsStat . "<br>";
$reportContent .= "Current Number of Forums: " . $forumsStat . "<br>";
$reportContent .= "Current Number of Forum Posts: " . $forumPostsStat . "<br>";







// concatenate these new variables to the report content with suitable headers
/*
$reportContent .= " <br>";
$reportContent .= "Number of Users: ";
$reportContent .= mysqli_fetch_array($users, MYSQLI_ASSOC)["users"] . "<br>";
$reportContent .= "Number of Books: ";
$reportContent .= mysqli_fetch_array($books, MYSQLI_ASSOC)["books"] . "<br>";
$reportContent .= "Number of Reviews: ";
$reportContent .= mysqli_fetch_array($reviews, MYSQLI_ASSOC)["reviews"] . "<br>";
$reportContent .= "Number of Events:";
$reportContent .= mysqli_fetch_array($events, MYSQLI_ASSOC)["events"] . "<br>";
$reportContent .= "Number of Forums:";
$reportContent .= mysqli_fetch_array($forums, MYSQLI_ASSOC)["forums"] . "<br>";
*/
// update the report content in the database


    $addBookReviewQuery = mysqli_query($conn, "insert into system_report values($user_id, null ,'$reportContent', NOW() );" );



  if ($addBookReviewQuery) {
    echo "<script type='text/javascript'>alert('" . "Success" . "');</script>";
  } else {
    echo "<script type='text/javascript'>alert('" . "Something went wrong" . "');</script>";

  }
  echo "<script type='text/javascript'>window.location = './forumList.php?$row';</script>";
}

?>

<form method="post">


    <fieldset>
        <legend>Generate Report :</legend>


    </fieldset>
    <div>
        <button type="submit">Submit</button>
    </div>
</form>

<form>

</form>

</body>
</html>



