<?php
session_start();
include "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Create Event</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles.css">

<?php

// list all ebooks
$conn = getDatabaseConnection();
$sql = "SELECT * FROM e_book";
$result = $conn->query($sql);

// create table with buy button for each ebook
if ($result->num_rows > 0) {
    echo "<h2>Ebooks</h2>";
//    $row = $result->fetch_assoc();
//    print_r($row);
//    echo "<table><tr><th>Ebook Name</th><th>Author Name</th><th>Genre Name</th><th> PublisherName</th><th>PublishDate</th>
//            <th>Price</th><th>Buy</th></tr>";
//    echo "<select name='e_books' id='e_books_selection_id' multiple>";
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        // find books properties from the book id
        echo "book id is $book_id";
        $book_info = getBookInfo( $book_id, $conn );
        echo "book info";
        print_r($book_info);

//        echo "<option value='$book_id'>" . $book_info['book_name'] . $book_info['author_name'] . "</option>";
    }

//    echo "</select>";
}
