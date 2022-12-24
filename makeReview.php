<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>

<?php
include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);
?>


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

    <td> Review</td>
    <td>Rating</td>
    <td>User</td>
    <td>Your Vote Status</td>


    Please specify your current reading status of this book
    <br>
        <?php

        include_once "helper.php";
        $conn = getDatabaseConnection();
        session_start();

        $currentBook = $_GET['bookID'];
        $currentUserName = $_SESSION['uname'];
        $currentUserID = $_GET['userID'];

          echo "    
                        <form method=\"post\" action=\"setUserBookStatus.php?status=0&bookID=$currentBook&userID=$currentUserID\"> 
                        <input type='submit' class='button_submit' value='Currently Reading'></form>
                        
                        <form method=\"post\" action=\"setUserBookStatus.php?status=1&bookID=$currentBook&userID=$currentUserID\"> 
                        <input type='submit' class='button_submit' value='Already Read'></form>
                      
                        <form method=\"post\" action=\"setUserBookStatus.php?status=2&bookID=$currentBook&userID=$currentUserID\"> 
                        <input type='submit' class='button_submit' value='Want To Read'></form>
                        
                        <form method=\"post\" action=\"setUserBookStatus.php?status=3&bookID=$currentBook&userID=$currentUserID\"> 
                        <input type='submit' class='button_submit' value='Not interested'></form>
                        
                        <form method=\"post\" action=\"setUserBookStatus.php?status=4&bookID=$currentBook&userID=$currentUserID\"> 
                        <input type='submit' class='button_submit' value='Favorite'></form>
                        

                        "


          ;
          ?>

    <h2> Previous Reviews Of This Book
    </h2>


  <?php

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

  $listPreviousReviewsQuery = mysqli_query($conn, "Select text, rating, user.display_name , review_id from book_review, user where user.user_id = book_review.user_id and book_id = '$currentBook'");


  while ($row = mysqli_fetch_array($listPreviousReviewsQuery, MYSQLI_ASSOC)) {

    $currentReview = $row['text'];
    $currentRate = $row['rating'];
    $currentName = $row['display_name'];

    $curReviewID = $row['review_id'];
    $currentVoteStatus = "Not voted";
    $currentVoteStatusQuery = mysqli_query($conn, "Select vote from user_vote_review as uvr where  uvr.review_id = $curReviewID and uvr.user_id=$currentUserID and uvr.book_id =$currentBook" );
    $currentVoteStatus =   mysqli_fetch_array($currentVoteStatusQuery,MYSQLI_ASSOC);

    if ($currentVoteStatus != null ){
        $currentVoteStatus = $currentVoteStatus['vote'];

    }

      echo "<tr>

 
    <td>{$currentReview} </td>
    <td>{$currentRate} </td>
    <td>{$currentName} </td>
    <td> {$currentVoteStatus} </td>
    <td> <form method=\"post\" action=\"setVoteStatus.php?status=up&bookID=$currentBook&userID=$currentUserID&reviewID=$curReviewID\"> 
                        <input type='submit' class='button_submit' value='upvote'></form></td>
    <td> <form method=\"post\" action=\"setVoteStatus.php?status=down&bookID=$currentBook&userID=$currentUserID&reviewID=$curReviewID\"> 
                        <input type='submit' class='button_submit' value='downvote'></form></td>


   </tr>\n";

  }


  ?>

</table>

<body>
<html>

