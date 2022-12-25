<?php
    include_once "helper.php";
    $conn = getDatabaseConnection();
    session_start();

    $currentUserID = $_GET['userID'];
    $followedUserID = $_GET['followedID'];

    $checkAlreadyExistsQuery = mysqli_query($conn,"Select user_id from user_follow_user where user_id = '$followedUserID' and follower_id='$currentUserID'; ");
    $resq = mysqli_fetch_array($checkAlreadyExistsQuery, MYSQLI_ASSOC);




    if ($resq == null) {
        $listPreviousReviewsQuery = mysqli_query($conn, "INSERT INTO user_follow_user(user_id, follower_id) values('$followedUserID','$currentUserID');");
        header("Location: ListUsersPage.php");

    }

    else
    {
        echo("You are already following her!");
    }


    exit();

    ?>


