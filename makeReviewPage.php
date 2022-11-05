<html>
<body>



<h2>Welcome to The Book Review  Page</h2>

<table border="1" align="center">
    <tr>
        <td>Book id </td>
        <td>Title</td>
        <td>Publisher ID</td>
        <td>Publish Date</td>

    </tr>

    <?php

    $conn = mysqli_connect("localhost", "root", "", "dbproject");
    session_start();
    $listBooktoReviewQuery = mysqli_query($conn,"Select * from book");

    while ($row = mysqli_fetch_array($listBooktoReviewQuery,MYSQLI_ASSOC)) {

        $bookid = $row['book_id'];
        $currentUserName = $_SESSION['uname'];
        $userid_from_username_query = mysqli_query($conn,"Select user_id from user where email = '$currentUserName'");
        $qres = mysqli_fetch_array($userid_from_username_query,MYSQLI_ASSOC);
        $currentUserID = $qres['user_id'];
        $_SESSION['user_id'] = $currentUserID;

        echo
        "<tr> 
    <td>{$row['book_id']} </td>
    <td>{$row['title']}</td>
    <td>{$row['publisher_id']}</td>
    <td>{$row['publish_date']}</td>
    <td><a href='makeReview.php?bookID=$bookid userID=$currentUserID'>'Make Review to this Book'</a></td>

   </tr>\n";

    }



    ?>

</table>

<body>
<html>

