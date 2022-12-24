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
  $type = intval($_POST['rtype']);
  $comment = $_POST['post'];
  $content = $comment . "\n";
  if ($type === 1) {
    <<<'SQL'
select book_id, title, AVG(rating) as ar
from (select *
      from book_review
      where date > (select CURRENT_DATE - INTERVAL 3 MONTH)) as l3review
         natural join book
group by book_id, title
order by ar desc
limit 10 
SQL;
    $sql = "SELECT phone_number FROM sys_adm_user WHERE email = '{$_SESSION["uname"]}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
  }
  if ($type === 2) {
    <<<'SQL'
select user_id, display_name, max(fc) as followers
from sys_author_user
         natural join
     (select user_id, COUNT(follower_id) as fc
      from user_follow_user
      group by user_id) as fcount
group by user_id    
SQL;
    $sql = "SELECT phone_number FROM sys_adm_user WHERE email = '{$_SESSION["uname"]}'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
  }
  // Use the query to create a report of the count of transitive followers the current user (admin creating the report)
  // has
  if ($type === 3) {
    $sql=  <<<'SQL'
WITH RECURSIVE follower_closure as (SELECT follower_id as dst
                                    FROM user_follow_user
                                    WHERE user_id = '{$_SESSION["user_id"]}'
                                    UNION
                                    SELECT user_follow_user.follower_id
                                    FROM user_follow_user

                                             JOIN follower_closure ON follower_closure.dst = user_follow_user.user_id)
select count(*) as c
from follower_closure
SQL;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
  }
  $date = date("Y-m-d");
  $addBookReviewQuery = mysqli_query($conn, "insert into system_report values({$_SESSION["user_id"]}, null,$content,'$date');");
  if ($addBookReviewQuery) {
    echo "<script type='text/javascript'>alert('" . "Success" . "');</script>";
  } else {
    echo "<script type='text/javascript'>alert('" . "Something went wrong" . "');</script>";

  }
  echo "<script type='text/javascript'>window.location = './forumList.php';</script>";
}

?>

<form method="post">

    <p>
        <label for="post">Create Forum</label><br> <textarea name="post" id="post" cols="40" rows="6" required
                                                             maxlength="240"></textarea>
    </p>
    <fieldset>
        <legend>Report Type:</legend>
        <div>
            <input type="radio" id="rtype1" name="rtype" value="1"/> <label for="rtype1">Highest Rated Books (Last 3
                                                                                         Months)</label> <input
                    type="radio" id="rtype2" name="rtype" value="2"/> <label for="rtype2">Most Followed Authors</label>
            <input type="radio" id="rtype3" name="rtype" value="3"/> <label for="rtype3">Show off your
                                                                                         reachability </label>
        </div>

    </fieldset>
    <div>
        <button type="submit">Submit</button>
    </div>
</form>

<form>

</form>

</body>
</html>



