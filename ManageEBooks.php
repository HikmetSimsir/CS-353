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
// display all ebooks books of the system
$sql = "SELECT * FROM ebooks_view";
$result = $conn->query($sql);

echo "<h2>Welcome to The Admin's Page</h2>";
echo "<h3>Here are all the e-books in the system:</h3>";
// create table to display all the books
echo "<table border='1' align='center'>
<tr>
<th>Book Name</th>
<th>Publisher Name</th>
<th>Genre</th>
<th>Price</th>
<th>Author</th>
<th>Remove</th>
</tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['title'] . "</td>";
    echo "<td>" . $row['publisher_name'] . "</td>";
    echo "<td>" . $row['genre_name'] . "</td>";
    echo "<td>" . $row['price'] . "</td>";
    echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td><td>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='book_id' value='" . $row['book_id'] . "'>";
    echo "<input type='submit' value='Remove' name='remove'>";
    echo "</form>";
    echo "</td></tr>";
}

?>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['book_id'])) {
$book_id = $_POST['book_id'];
echo "card id: " . $book_id;

if ($conn->connect_error) {
    die('Connection Failed : ' . $conn->connect_error);
} else {
    $sql = "DELETE FROM book WHERE book_id = $book_id";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

}
// refresh page
header("Refresh:0");

}

