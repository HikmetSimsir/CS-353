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
                                                                    name="bookReview" required height=100 width=200>
        <br>
        <button type="submit">Submit Review</button>
    </div>


</form>


<br> <br> <br> <br>

<table border="1" align="left">

    <td>Previous Reviews of this book</td>


  <?php


  $conn = mysqli_connect("localhost", "root", "", "dbproject");
  session_start();

  $currentBook = $_GET['bookID'];
  $listPreviousReviewsQuery = mysqli_query($conn, "Select text from book_review where book_id = '$currentBook'");

  if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currentUserName = $_SESSION['uname'];
    $currentUserID = $_SESSION['user_id'];

    $bookReview = $_POST['bookReview'];


    //get count of current number of reviews
    $numberOfReviewForThisBook = mysqli_num_rows($listPreviousReviewsQuery);

    // add this book review to the table
    $addBookReviewQuery = mysqli_query($conn, "insert into book_review values('$currentBook',$numberOfReviewForThisBook+1,$currentUserID,'$bookReview',null,null);");

  }

  $listPreviousReviewsQuery = mysqli_query($conn, "Select text from book_review where book_id = '$currentBook'");


  while ($row = mysqli_fetch_array($listPreviousReviewsQuery, MYSQLI_ASSOC)) {

    $currentReview = $row['text'];
    echo "<tr>

    <td>{$currentReview} </td>
   </tr>\n";

  }


  ?>

</table>

<body>
<html>

