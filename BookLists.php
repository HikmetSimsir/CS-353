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
define('list_types', array('reading', 'read', 'want to read', 'not interested', 'favorite'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Lists</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">

<?php
echo "<h1>Book Lists</h1>";


// for each type, get the list of books
foreach (list_types as $type) {
//    echo "<h2>$type</h2>";
    $sql = "SELECT * FROM user_has_book_lists WHERE user_id = $user_id AND book_list_name = '$type'";
    $result = $conn->query($sql);

    // find book properties from the book id
    if ($result->num_rows > 0) {
        echo "<h2>". ucfirst($type) . " Books" . "</h2>";

//        echo "<h3>" . "Books" . "</h3>";
        echo "<table><tr><th>Book Name</th><th>Author Name</th><th>Genre Name</th><th> PublisherName</th><th>Remove</th></tr>";



        while ($row = $result->fetch_assoc()) {
//            $row = $result->fetch_assoc();
            $book_id = $row['book_id'];

            // check if the book is ebook
            $sql = "SELECT * FROM e_book WHERE book_id = $book_id";
            $result2 = $conn->query($sql);

            if ($result2->num_rows == 0) {
                $book_info = getBookInfo($book_id, $conn);
            } else {
                $book_info = getEBookInfo($book_id, $conn);
            }

            // display books
            echo "<tr>";
            echo "<td>" . $book_info['book_title'] . "</td>";
            echo "<td>" . $book_info['author_name'] . "</td>";
            echo "<td>" . $book_info['genre_name'] . "</td>";
            echo "<td>" . $book_info['publisher_name'] . "</td><td>";



            echo "<form method='post'>";
            echo "<input type='hidden' name='book_id' value='" . $book_id . "'>";
            echo "<input type='submit' value='Remove' name='remove'>";
            echo "</form>";



            echo "</td></tr>";

        }
        echo "</table>";

    } else {
        echo "<h2>". ucfirst($type) . " Books" . "</h2>";
        echo "<p>There are no books in this list.</p>";
    }
}
?>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book_id'])) {
        $book_id = $_POST['book_id'];
        echo "book id: " . $book_id;

        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $sql = "DELETE FROM user_has_book_lists WHERE book_id = $book_id";
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

?>






</html>
