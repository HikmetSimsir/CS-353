<html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>


<?php

session_start();
include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);
?>

<h2>Welcome to The Users Page , You can see all the users below</h2>




<table border="1" align="center">
    <tr>
        <td>User Name</td>

    </tr>

    <?php

    if ( array_key_exists("followUserName",$_GET))
        $filter_author = $_GET["followUserName"];
    else
        $filter_author = null;

    if (is_null($filter_author)) {
        $filter_author = "";
    }



    $form = '<form method="get">


    <p>
        <label for="followUserName">Search user</label><br> <input type="text" name="followUserName" id="followUserName" value="' . "$filter_author" . '">
    </p>

    <p>
        <button>Filter</button>
    </p>

</form>';
    echo $form;
    include_once "helper.php";
    $conn = getDatabaseConnection();
    $sql = "
select email,user_id, display_name
from user
where  display_name like '%$filter_author%'
";
    $listAllUsersQuery = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($listAllUsersQuery, MYSQLI_ASSOC)) {

        //$bookid = $row['book_id'];
        $currentUserName = $_SESSION['uname'];
        $userid_from_username_query = mysqli_query($conn, "Select user_id from user where email = '$currentUserName'");
        $qres = mysqli_fetch_array($userid_from_username_query, MYSQLI_ASSOC);
        $currentUserID = $qres['user_id'];
        $_SESSION['user_id'] = $currentUserID;

        $followedUserMail = $row['email'];

        $followedUserIDQuery = mysqli_query($conn, "Select user_id from user where email = '$followedUserMail'");
        $followedUserID = mysqli_fetch_array($followedUserIDQuery, MYSQLI_ASSOC);
        $followedUserID = $followedUserID['user_id'];

        echo "<tr> 
    <td>{$row['display_name']}</td>
    <td><a href='followUser.php?followedID=$followedUserID&userID=$currentUserID'   >'Follow this user'</a></td>



   </tr>\n";

    }


    ?>

</table>

<body>
<html>

