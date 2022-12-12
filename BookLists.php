<?php
session_start();
include_once "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages
define('list_types', array('reading', 'read', 'want to read', 'not interested', 'favorite'));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">

<?php
echo "<h1>Book Lists</h1>";


// list book of use of all type
$conn = getDatabaseConnection();

// for each type, get the list of books
foreach (list_types as $type) {
    $sql = "SELECT * FROM user_has_book_lists WHERE user_id = $user_id AND book_list_name = '$type'";
    $result = $conn->query($sql);

    // find book properties from the book id
    if ($result->num_rows > 0) {
        echo "<h2>". ucfirst($type) . " Books" . "</h2>";
        echo "<table><tr><th>Book Name</th><th>Author Name</th><th>Genre Name</th><th> PublisherName</th><th>PublishDate</th><th>Remove</th></tr>";


        while ($row = $result->fetch_assoc()) {
//            $row = $result->fetch_assoc();
            $book_id = $row['book_id'];

            $sql2 = "SELECT * FROM book WHERE book_id = $book_id";
            $result2 = $conn->query($sql2);
            $row2 = $result2->fetch_assoc();

            // find publisher name from publisher id
            $publisher_id = $row2['publisher_id'];
            $sql3 = "SELECT * FROM publisher WHERE p_id = $publisher_id";
            $result3 = $conn->query($sql3);
            $row3 = $result3->fetch_assoc();
            $publisher_name = $row3['p_name'];

            // find author name from table book_author
            $sql4 = "SELECT * FROM book_author WHERE book_id = $book_id";
            $result4 = $conn->query($sql4);
            if ($result4->num_rows > 0) {
                $row4 = $result4->fetch_assoc();
                $author_id = $row4['author_id'];
                $sql6 = "SELECT * FROM author WHERE author_id = $author_id";
                $result6 = $conn->query($sql6);
                $row6 = $result6->fetch_assoc();
                $author_name = $row6['name'] . " " . $row6['surname'];
            } else {
                $author_name = "N/A";
            }

            // find genre name from table book_genre
            $sql5 = "SELECT * FROM book_genre WHERE book_id = $book_id";
            $result5 = $conn->query($sql5);
            if ($result5->num_rows > 0) {
                $row5 = $result5->fetch_assoc();
                $genre_id = $row5['genre_id'];
                $sql7 = "SELECT * FROM genre WHERE genre_id = $genre_id";
                $result7 = $conn->query($sql7);
                $row7 = $result7->fetch_assoc();
                $genre_name = $row7['genre_name'];
            } else {
                $genre_name = "N/A";
            }

            // display table



//        // display the book
//        echo "<div class='book'>";
//        echo "<h2>" . $row2['title'] . "</h2>";
//        echo "<p>publish_date: " . $row2['publish_date'] . "</p>";
//        echo "<p>publish_name: " . $publisher_name . "</p>";
//        echo "<p>list type: " . $type . "</p>";
//        echo "</div>";

            echo "<tr><td>" . $row2['title'] . "</td><td>" . $author_name . "</td><td>" . $genre_name . "</td><td>" .
                $publisher_name . "</td><td>" . $row2['publish_date'] . "</td><td>";

            echo "<form method='post'>";
            echo "<input type='hidden' name='book_id' value='" . $book_id . "'>";
            echo "<input type='submit' value='Remove' name='remove'>";
            echo "</form>";


            echo "</td></tr>";
        }
        echo "</table>";

    }


//    if ($result->num_rows > 0) {
//        echo "<h2>$type</h2>";
//        echo "<table><tr><th>Book Name</th><th>Author</th><th>Rating</th><th>Review</th><th>Remove</th></tr>";
//        while ($row = $result->fetch_assoc()) {
//            echo "<tr><td>" . $row['book_name'] . "</td><td>" . $row['author'] . "</td><td>" . $row['rating'] . "</td><td>" . $row['review'] . "</td><td>";
//            echo "<form action='RemoveBook.php' method='post'>";
//            echo "<input type='hidden' name='book_id' value='" . $row['book_id'] . "'>";
//            echo "<input type='submit' name='remove' value='Remove'>";
//            echo "</form>";
//            echo "</td></tr>";
//        }
//        echo "</table>";
//    }
}
//// print list types array
//for ($i = 0; $i < count(list_types); $i++) {
//    echo list_types[$i] . " ";
//}
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
