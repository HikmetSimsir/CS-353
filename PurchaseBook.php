<?php
session_start();
include "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Ebook Purchase</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles.css">

<?php

echo "<h2>Purchase Ebook</h2>";

// list all ebooks
$conn = getDatabaseConnection();
$sql = "SELECT * FROM e_book";
$result = $conn->query($sql);


// create table with buy button for each ebook
if ($result->num_rows > 0) {
    echo "<form id='ebook_form' method='post'>";
    echo "<h2>Ebooks Not Purchased by You</h2>";
//    $row = $result->fetch_assoc();
//    print_r($row);
//    echo "<table><tr><th>Ebook Name</th><th>Author Name</th><th>Genre Name</th><th> PublisherName</th><th>PublishDate</th>
//            <th>Price</th><th>Buy</th></tr>";
    echo "<select name='e_books' id='e_books_selection_id' multiple>";
    while ($row = $result->fetch_assoc()) {
        $book_id = $row['book_id'];
        // find books properties from the book id
        echo "book id is $book_id";
        // check if the book is already purchased by the user
        $sql2 = "SELECT * FROM purchase WHERE user_id = $user_id AND book_id = $book_id";
        $result2 = $conn->query($sql2);

        if ($result2->num_rows == 0) {
            $book_info = getBookInfo( $book_id, $conn );
            echo "book info";
            print_r($book_info);

            echo "<option value='$book_id'>" . $book_info['book_title'] . "--" . $book_info['author_name'] . "</option>";
        }
    }

    echo "</select>";
}

// list users all credit cards
$sql = "SELECT * FROM user_has_credit_card WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>Your Credit Cards</h2>";
    echo "<select name='credit_cards' id='credit_cards_selection_id' multiple>";
    while ($row = $result->fetch_assoc()) {
        $card_id = $row['card_id'];

        // find credit card properties from the card id
        $sql2 = "SELECT * FROM credit_card WHERE card_id = $card_id";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $card_number = $row2['card_number'];
        $card_type = $row2['card_type'];
        $name_on_card = $row2['name_on_card'];

        echo "<option value='$card_id'>" . $card_number . "--" . $card_type . "--" . $name_on_card . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Buy' name='buy'>";
    echo "</form>";

} else {
    echo "You have no credit cards. Please add a credit card. <br>";
}


// get data from submit button
if(isset($_POST['buy'])) {
    echo "buy button was pressed";
    if (isset($_POST['e_books']) && isset($_POST['credit_cards'])) {
        // get data from form
        $ebook_id = $_POST['e_books'];
        $card_id = $_POST['credit_cards'];

        echo "ebook id is $ebook_id" . "credit card id is $card_id";

        // insert into purchase table
        $sql = "INSERT INTO purchase (user_id, card_id, book_id, date) VALUES ($user_id, $card_id, $ebook_id, NOW())";
        $result = $conn->query($sql);
        if ($result) {
            echo "Purchase was successful";
        } else {
            echo "Purchase was not successful";
        }
    } else {
        echo "Please select an ebook and a credit card";
    }

    header("Refresh:0");
}