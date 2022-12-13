<?php
include_once "helper.php";
$conn = getDatabaseConnection();
session_start();

$status = $_GET['status'];
$bookID = $_GET['bookID'];
$userID = $_GET['userID'];


if ($status == 0){
    $status='reading';
}
    else if ( $status == 1){
        $status='read';
    }

    else if ( $status == 2){
        $status='want to read';
    }

    else if ( $status == 3){
        $status='not interested';
    }

    else{
        $status='favorite';
    }



$checkAlreadyExistsQuery = mysqli_query($conn,"Select * from user_has_book_lists where user_id = '$userID' and book_id='$bookID'; ");
$resq = mysqli_fetch_array($checkAlreadyExistsQuery, MYSQLI_ASSOC);

if ( $resq == null)
{

    $setBookUserStatusQuery = mysqli_query($conn, "Insert into user_has_book_lists values('$userID','$bookID','$status');");

}

else{

    $updateBookUserStatusQuery = mysqli_query($conn, "Update user_has_book_lists set book_list_name ='$status' where user_id = '$userID' and book_id='$bookID';");

}

header("Location: makeReview.php?bookID=$bookID&userID=$userID");
exit();

?>


