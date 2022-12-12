<?php
include_once "helper.php";
$conn = getDatabaseConnection();
session_start();

$currentUserID = $_GET['userID'];
$followedUserID = $_GET['followedID'];

$listPreviousReviewsQuery = mysqli_query($conn, "Delete from user_follow_user where user_id = '$followedUserID' and follower_id = '$currentUserID';");
header("Location: followedUsersPage.php");


exit();

?>


