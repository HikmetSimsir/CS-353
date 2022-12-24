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


<h2>Welcome to The Book Review Page</h2>

<table border="1" align="center">
    <tr>
        <td>Title</td>
        <td>Publisher Name</td>
        <td>Author Name</td>
        <td>Genre Info</td>
        <td> Your Current Status  </td>

    </tr>

  <?php


  $filter_title = $_GET["title"];
  $filter_author = $_GET["author"];
  $filter_publisher = $_GET["publisher"];
  $filter_genre = $_GET["genre"];

  if (is_null($filter_title)) {
    $filter_title = "";
  }
  if (is_null($filter_author)) {
    $filter_author = "";
  }
  if (is_null($filter_publisher)) {
    $filter_publisher = "";
  }
  if (is_null($filter_genre)) {
    $filter_genre = "";
  }
  $form = '<form method="get">

    <p>
        <label for="title">book name</label><br> <input type="text" name="title" id="title" value="' . "$filter_title" . '">
    </p>

    <p>
        <label for="author">author</label><br> <input type="text" name="author" id="author" value="' . "$filter_author" . '">
    </p>

    <p>
        <label for="publisher">publisher</label><br> <input type="text" name="publisher" id="publisher" value="' . "$filter_publisher" . '">
    </p>

    <p>
        <label for="genre">genre</label><br> <input type="text" name="genre" id="genre" value="' . "$filter_genre" . '">
    </p>
    <p>
        <button>Filter</button>
    </p>

</form>';

  echo $form;
  echo "<h2>Filtred Books</h2>";

  include_once "helper.php";
  $conn = getDatabaseConnection();

  $sql = "
select book.book_id, title, publisher.publisher_name, author.name, genre.genre_name
from book,
     book_author,
     publisher,
     author,
     genre,
     book_genre
where book.book_id = book_author.book_id
  and book_author.author_id = author.author_id
  and book.book_id = book_genre.book_id
  and book_genre.genre_id = genre.genre_id
  and book.publisher_id = publisher.publisher_id
  and author.name like '%$filter_author%'
  and publisher.publisher_name like '%$filter_publisher%'
  and genre.genre_name like '%$filter_genre%'
  and book.title like '%$filter_title%'
";

  $listBooktoReviewQuery = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_array($listBooktoReviewQuery, MYSQLI_ASSOC)) {

    $bookid = $row['book_id'];
    $currentUserName = $_SESSION['uname'];
    $userid_from_username_query = mysqli_query($conn, "Select user_id from user where email = '$currentUserName'");
    $qres = mysqli_fetch_array($userid_from_username_query, MYSQLI_ASSOC);
    $currentUserID = $qres['user_id'];
    $_SESSION['user_id'] = $currentUserID;

    $currentUserBookReadStatus = 'Not specified';

      $userBookReadStatusQuery = mysqli_query($conn,"Select book_list_name from user_has_book_lists where user_id = '$currentUserID' and book_id='$bookid'; ");
      $resq = mysqli_fetch_array($userBookReadStatusQuery, MYSQLI_ASSOC);

      if($resq != null)
          $currentUserBookReadStatus = $resq['book_list_name'];

    echo "<tr> 
    <td>{$row['title']}</td>
    <td>{$row['publisher_name']}</td>
    <td>{$row['name']}</td>
    <td>{$row['genre_name']}</td>
    <td>{$currentUserBookReadStatus}</td>

    <td><a href='makeReview.php?bookID=$bookid&userID=$currentUserID'>'Make Review to this Book'</a></td>

   </tr>\n";

  }




  ?>

</table>

<body>
<html>

<?php
echo "<h2>Filtred E-Books</h2>";

// find the e-books
$sql = "SELECT * FROM (e_book
    natural join author_publish_ebook natural join book
    natural join book_genre natural join genre natural join publisher), sys_author
         WHERE author_publish_ebook.author_id = sys_author.user_id and sys_author.first_name like '%$filter_author%'
  and publisher.publisher_name like '%$filter_publisher%' and genre.genre_name like '%$filter_genre%' and book.title like '%$filter_title%'";
$result = $conn->query($sql);

echo "<table border='1' align='center'>
<tr>
<th>Title</th>
<th>Publisher Name</th>
<th>Author Name</th>
<th>Genre Info</th>
<th> Price</th></tg>
<th> Your Current Status  </th>
</tr>";

while ($row = $result->fetch_assoc()) {
    $bookid = $row['book_id'];
    $currentUserName = $_SESSION['uname'];
    $userid_from_username_query = mysqli_query($conn, "Select user_id from user where email = '$currentUserName'");
    $qres = mysqli_fetch_array($userid_from_username_query, MYSQLI_ASSOC);
    $currentUserID = $qres['user_id'];
    $_SESSION['user_id'] = $currentUserID;

    $currentUserBookReadStatus = 'Not specified';

    $userBookReadStatusQuery = mysqli_query($conn,"Select book_list_name from user_has_book_lists where user_id = '$currentUserID' and book_id='$bookid'; ");
    $resq = mysqli_fetch_array($userBookReadStatusQuery, MYSQLI_ASSOC);

    if($resq != null)
        $currentUserBookReadStatus = $resq['book_list_name'];

    echo "<tr> 
    <td>{$row['title']}</td>
    <td>{$row['publisher_name']}</td>
    <td><a href='AuthorsPage.php'>{$row['first_name']} </a></td>
    <td>{$row['genre_name']}</td>
    <td>{$row['price']}</td>
    <td>{$currentUserBookReadStatus}</td>

    <td><a href='makeReview.php?bookID=$bookid&userID=$currentUserID'>'Make Review to this Book'</a></td>

   </tr>\n";
}



?>