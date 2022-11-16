<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>


<h2>Welcome to The Book Review Page</h2>

<label for="name">Make your review below. </label>


<form method="post">

    <div class="container" id='transferMoney'>
        <label for="fromAccount"><b>Your Review </b></label> <input type="text" placeholder="Enter review"
                                                                    name="bookReview" required> <label
                for="bookRate"><b>Your Review </b></label> <input type="number" placeholder="5" name="bookRate" required
                                                                  max="5" min="0"> <br>
        <button type="submit">Submit Review</button>
    </div>


</form>


<br> <br> <br> <br>

<table border="1" align="left">

    <td>Previous Reviews of this book</td>
    <td>Rating</td>
    <td>User</td>


  <?php
  include_once "helper.php";
  $conn = getDatabaseConnection();
  session_start();

  $currentBook = $_GET['bookID'];
  $listPreviousReviewsQuery = mysqli_query($conn, "Select text from book_review where book_id = '$currentBook'");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currentUserName = $_SESSION['uname'];
    $currentUserID = $_SESSION['user_id'];

    $bookReview = $_POST['bookReview'];
    $bookRate = $_POST['bookRate'];
    $date = getdate();


    //get count of current number of reviews
    $numberOfReviewForThisBook = mysqli_num_rows($listPreviousReviewsQuery);

    // add this book review to the table
    $addBookReviewQuery = mysqli_query($conn, "insert into book_review values('$currentBook',null,'$currentUserID','$bookReview',null,'$bookRate');");

  }

  $listPreviousReviewsQuery = mysqli_query($conn, "Select text, rating, user.display_name from book_review, user where user.user_id = book_review.user_id and book_id = '$currentBook'");


  while ($row = mysqli_fetch_array($listPreviousReviewsQuery, MYSQLI_ASSOC)) {

    $currentReview = $row['text'];
    $currentRate = $row['rating'];
    $currentName = $row['display_name'];
    echo "<tr>

    <td>{$currentReview} </td>
    <td>{$currentRate} </td>
    <td>{$currentName} </td>

   </tr>\n";

  }


  ?>

</table>

<body>
<html>

