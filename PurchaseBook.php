<?php
session_start();
include "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages
$conn = getDatabaseConnection();
$filtered = false;

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Ebook Purchase</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles.css">
        </head>
    <body>
    <h2>Welcome to The Ebook Purchase Page</h2>
    <!--        filtering buttons-->
    <form action="" method="post">
        <div class="container">
            <h1>Filter Ebooks</h1>
            <p>Please fill in this form to filter ebooks.</p>
            <hr>
            <label for="title"><b>Title</b></label>
            <input type="text" placeholder="Enter Title" name="title">

            <label for="genre"><b>Genre</b></label>
            <input type="text" placeholder="Enter Genre" name="genre" >

            <label for="author"><b>Author</b></label>
            <input type="text" placeholder="Enter Author" name="author" >

            <label for="publisher"><b>Publisher</b></label>
            <input type="text" placeholder="Enter Publisher" name="publisher" >
            <!--price range-->
            <label for="min_price"><b>Min Price</b></label>
            <input type="number" min="0" max="1000000" name="min_price" >
            <label for="max_price"><b>Max Price</b></label>
            <input type="number" min="0" max="1000000" name="max_price">


            <input type="submit" value="Filter" name="filter">
        </div>
    </form>
<?php
if (isset($_POST['filter'])) {
//    echo "filter button was pressed";
    // check if fields are empty
    if (empty($_POST['genre']) && empty($_POST['author']) && empty($_POST['publisher'])
        && empty($_POST['min_price']) && empty($_POST['max_price']) && empty($_POST['title'])) {
        echo "Please fill in at least one field";
        $books = array();
    } else {
        // get data from form
        $books_genre = array();
        $books_author = array();
        $books_publisher = array();
        $books_price = array();
        $books_title = array();

        if (!empty($_POST['genre'])) {
            $genre = $_POST['genre'];

            // search for genre using like
            $sql = "SELECT * FROM genre WHERE genre_name LIKE '%$genre%'";
            $result = $conn->query($sql);


            while ($row = $result->fetch_assoc()) {
                $genre_id = $row['genre_id'];
//                echo "genre id is $genre_id";

                $sql2 = "SELECT * FROM book_genre WHERE genre_id = $genre_id";
                $result2 = $conn->query($sql2);

                while ($row2 = $result2->fetch_assoc()) {
                    $book_id = $row2['book_id'];
//                    echo "book id is $book_id";
                    array_push($books_genre, $book_id);
                }
            }

            echo "books genre";
            print_r($books_genre);
            echo "<br>";

        }
        if (!empty($_POST['title'])) {
            $title = $_POST['title'];
            echo "title is \"$title\"";


            // search for title using like
            $sql = "SELECT * FROM book WHERE title LIKE '%$title%'";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                array_push($books_title, $book_id);
            }

            echo "books title";
            print_r($books_title);
            echo "<br>";
        }

        if (!empty($_POST['author'])) {
            $author = $_POST['author'];

            // search for author using like
            $sql = "SELECT * FROM author natural join book_author WHERE name LIKE '%$author%'";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                array_push($books_author, $book_id);
            }

            echo "books author";
            print_r($books_author);
            echo "<br>";
        }


        if (!empty($_POST['publisher'])) {
            $publisher = $_POST['publisher'];

            // search for publisher using like
            $sql = "SELECT * FROM publisher natural join book WHERE publisher_name LIKE '%$publisher%'";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                array_push($books_publisher, $book_id);
            }

            echo "books publisher";
            print_r($books_publisher);
            echo "<br>";
        }

        if (!empty($_POST['min_price']) && !empty($_POST['max_price'])) {
            $min_price = $_POST['min_price'];
            $max_price = $_POST['max_price'];
            echo "min price is $min_price and max price is $max_price";
            // search for price using between
            $sql = "SELECT * FROM e_book WHERE price BETWEEN $min_price AND $max_price";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $book_id = $row['book_id'];
                array_push($books_price, $book_id);
            }

            echo "books price";
            print_r($books_price);
            echo "<br>";
        }

        // get the intersection of all the arrays if they are not empty
        $books = array();
        if (!empty($_POST['genre'])) {
            if ($books) {
                $books = array_intersect($books, $books_genre);
            } else {
                $books = $books_genre;
            }
        }
        if (!empty($_POST['author'])) {
            if ($books) {
                $books = array_intersect($books, $books_author);
            } else {
                $books = $books_author;
            }
        }
        if (!empty($_POST['publisher'])) {
            if ($books) {
                $books = array_intersect($books, $books_publisher);
            } else {
                $books = $books_publisher;
            }
        }

        if (!empty($_POST['min_price']) && !empty($_POST['max_price'])) {
            if ($books) {
                $books = array_intersect($books, $books_price);
            } else {
                $books = $books_price;
            }
        }
        if (!empty($_POST['title'])) {
            if ($books) {
                $books = array_intersect($books, $books_title);
            } else {
                $books = $books_title;
            }
        }

        echo "books";
        print_r($books);
        echo "<br>";
        $filtered = true;

    }

}
?>


<?php
// list all ebooks
if (!$filtered) {
    $sql = "SELECT * FROM e_book";
} else if ($filtered && !empty($books)) {
    $sql = "SELECT * FROM e_book WHERE book_id IN (" . implode(',', $books) . ")";
} else {
    $sql = "SELECT * FROM e_book WHERE book_id = -1";
}
echo $sql;
$result = $conn->query($sql);

echo "<h1>Ebooks Not Purchased by You</h1>";
echo "<p> Select a book to purchase</p>";
// create table with buy button for each ebook
if ($result->num_rows > 0) {
    echo "<form id='ebook_form' method='post'>";

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

            echo "<option value='$book_id'>" . "TITLE: " . $book_info['book_title'] . ", AUTHOR NAME:" . $book_info['author_name'] . "</option>";
        }
    }

    echo "</select>";
} else {
    echo "<h2>No ebooks found!</h2>";
}
?>


<?php
// list users all credit cards
$sql = "SELECT * FROM user_has_credit_card WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h1>Your Credit Cards</h1>";
    echo "<p> Choose a credit card to purchase the ebook</p>";
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

        echo "<option value='$card_id'>" . " CARD_NUMBER: " . $card_number . "--" . $card_type . "--" . $name_on_card . "</option>";
    }
    echo "</select>";
    echo "<input type='submit' value='Buy' name='buy'>";
    echo "</form>";

} else {
    echo "You have no credit cards. Please add a credit card. <br>";
}

?>
    </body>
    </html>



    <?php


// get data from submit button
if(isset($_POST['buy'])) {
    echo "buy button was pressed";
    if (isset($_POST['e_books']) && isset($_POST['credit_cards'])) {
        // get data from form
        $ebook_id = $_POST['e_books'];
        $card_id = $_POST['credit_cards'];

        // unset post
        unset($_POST['e_books']);
        unset($_POST['credit_cards']);

        echo "ebook id is $ebook_id" . "credit card id is $card_id";

        // check if the user has enough credit to buy the book
        $sql = "SELECT * FROM e_book WHERE book_id = $ebook_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $price = $row['price'];


        $sql = "SELECT * FROM credit_card WHERE card_id = $card_id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $credit = $row['balance'];

        if ($credit < $price) {
            // make alert that the user does not have enough credit
            echo "<script type='text/javascript'>alert('You do not have enough credit to buy this book.');</script>";
        } else {
            // insert into purchase table
            $sql = "INSERT INTO purchase (user_id, card_id, book_id, date) VALUES ($user_id, $card_id, $ebook_id, NOW())";
            $result2 = $conn->query($sql);
            if ($result) {
                echo "Purchase was successful";
            } else {
                echo "Purchase was not successful";
            }

            // update credit card balance
            $new_credit = $credit - $price;
            echo "new credit is $new_credit, old credit is $credit, price is $price";
            $sql = "UPDATE credit_card SET balance = $new_credit WHERE card_id = $card_id";
            $result = $conn->query($sql);

            // check the result
            if ($result && $result2) {
                // make alert that the purchase was successful
                echo "<script type='text/javascript'>alert('Purchase was successful.');</script>";
            }

        }



    } else {
        // make alert that the user did not select a book or credit card
        echo "<script type='text/javascript'>alert('You did not select a book or credit card.');</script>";
    }
// clear post

    header("Refresh:0");
}

?>
