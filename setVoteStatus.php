<?php
include_once "helper.php";
$conn = getDatabaseConnection();
session_start();

$status = $_GET['status'];
$bookID = $_GET['bookID'];
$userID = $_GET['userID'];
$reviewID= $_GET['reviewID'];


$checkAlreadyExistsQuery = mysqli_query($conn,"Select * from user_vote_review where user_id = '$userID' and book_id='$bookID' and review_id=$reviewID; ");
$resq = mysqli_fetch_array($checkAlreadyExistsQuery, MYSQLI_ASSOC);

if ( $resq == null)
{

    $setBookUserStatusQuery = mysqli_query($conn, "Insert into user_vote_review values('$reviewID','$userID','$bookID','$status');");

}

else{

    $updateBookUserStatusQuery = mysqli_query($conn, "Update user_vote_review set vote ='$status' where user_id = '$userID' and book_id='$bookID' and review_id=$reviewID;");

}

header("Location: makeReview.php?bookID=$bookID&userID=$userID&reviewID=$reviewID");
exit();

?>


