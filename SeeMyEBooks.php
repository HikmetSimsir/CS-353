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

// display all ebooks of the user
$conn = getDatabaseConnection();
$sql = "SELECT * FROM purchase WHERE user_id = $user_id";
$result = $conn->query($sql);

// create table for purchased ebooks

if ($result->num_rows > 0) {
    echo "<h2>Ebooks Purchased by You</h2>";
    echo "<table><tr><th>Ebook Name</th><th>Author Name</th><th>Genre Name</th><th> PublisherName</th><th>Purchase Date</th>
            <th>Price</th></tr>";
    while ($row = $result->fetch_assoc()) {
//        print_r($row);
        $book_id = $row['book_id'];
        $book_info = getBookInfo( $book_id, $conn );

        // find price of the book
        $sql2 = "SELECT * FROM e_book WHERE book_id = $book_id";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $price = $row2['price'];



        echo "<tr><td>" . $book_info['book_title'] . "</td><td>" . $book_info['author_name'] . "</td><td>" .
            $book_info['genre_name'] . "</td><td>" . $book_info['publisher_name'] . "</td><td>" . $row['date'] . "</td><td>" . $price . "</td></tr>";

    }
}
