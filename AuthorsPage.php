<?php
session_start();
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
include "NavBar.php";
include "helper.php";

navBar($isAdmin, $isAuthor);
//$user_id = 1; // this will come from session array, once it is connected to rest of the pages
$user_id = $_SESSION['user_id'];
$conn = getDatabaseConnection();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AuthorsPage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>

<?php
// display all ebboks books of the author
$author_id = $_GET['author_id'];

//echo "author id is: " . $author_id;

$sql = "SELECT * FROM sys_author natural join user";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$author_name = $row['first_name'] . " " . $row['last_name'];


echo "<h2>Welcome to The Author's Page</h2>";
if ($user_id === $author_id) {
    echo "<h3>Hi, " . $author_name . "</h3>";
    echo "<p>Here are your books:</p>";
} else {
    echo "<h3>Here are all the e-books " .  $author_name ." have published:</h3>";
}



$sql = "SELECT * FROM e_book
    natural join author_publish_ebook natural join book 
    natural join book_genre natural join genre natural join publisher
         WHERE author_id = $author_id";
$result = $conn->query($sql);

// create table to display all the books
echo "<table border='1' align='center'>
<tr>
<th>Book Name</th>
<th>Publisher Name</th>
<th>Genre</th>
<th>Price</th>
<th>Download</th>
</tr>";
define ('SITE_ROOT', realpath(dirname(__FILE__)));
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['publisher_name'] . "</td>";
    echo "<td>" . $row['genre_name'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";

    // check if the has purchased the book if he is not the author
    if ($author_id != $user_id) {
        $sql = "SELECT * FROM purchase WHERE user_id = $user_id AND book_id = {$row['book_id']}";
        $result2 = $conn->query($sql);

        if (!$result2->num_rows > 0) {
            echo "<td>Not Purchased</td>";
        } else {
            echo "<td><a href='download.php?file=".SITE_ROOT. "\\" .$row['content']."'>Download</a></td>";
        }
    } else {
        echo "<td><a href='download.php?file=".SITE_ROOT. "\\" .$row['content']."'>Download</a></td>";
    }
    echo "</tr>";
}



