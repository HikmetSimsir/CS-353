<?php
session_start();
include "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages

$conn = getDatabaseConnection();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Publish E-Book</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>


    <body>
        <h2>Welcome to The E-Book Publishing Page</h2>
        <!--create ebook form-->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="container">
                <h1>Publish E-Book</h1>
                <p>Please fill in this form to publish an e-book.</p>
                <hr>
                <label for="bookname"><b>Book Name</b></label>
                <input type="text" placeholder="Enter Book Name" name="bookname" required>
                <label for="publishername"><b>Publisher Name</b></label>
                <input type="text" placeholder="Enter Publisher Name" name="publishername" required>
                <label for="genre"><b>Genre</b></label>
                <input type="text" placeholder="Enter Genre" name="genre" required>

                <label for="price"><b>Price</b></label>
                <input type="text" placeholder="Enter Price" name="price" required>

<!--                select pdf file-->
                <label for="pdf"><b>Select PDF File</b></label>
                <input type="file" name="fileToUpload" id="fileToUpload">


                <input type="submit" value="Publish" name="publish">
            </div>


    </body>
</html>



<?php
// get data from submit button

$author_id = 1; // this will come from session array, once it is connected to rest of the pages

if(isset($_POST['publish'])) {
//    echo "publish button was pressed";

    // get data from form
    $book_name = $_POST['bookname'];
    $publisher_name = $_POST['publishername'];
    $genre = $_POST['genre'];
    $price = $_POST['price'];

    print_r($_FILES['fileToUpload']);
    // read pdf content
    $targetfolder = "uploads/";
    $targetfolder = $targetfolder . basename( $_FILES['fileToUpload']['name']) ;

    echo $targetfolder . "<br>";
    echo "tmp file name is: " . $_FILES['fileToUpload']['tmp_name'] . "<br>";


    define ('SITE_ROOT', realpath(dirname(__FILE__)));
    // check file type
    $fileType = strtolower(pathinfo($targetfolder,PATHINFO_EXTENSION));

    if ($fileType != "pdf") {
        // script to display error message
        echo "<script>alert('Sorry, only PDF files are allowed.');</script>";
    } else {
        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], SITE_ROOT.'\\uploads\\'. basename( $_FILES['fileToUpload']['name']))) {
            echo "The file ". basename( $_FILES['fileToUpload']['name']). " is uploaded";
        } else {
            echo "Problem uploading file";
        }
    }

    // find publisher id
    $sql = "SELECT publisher_id FROM publisher WHERE publisher_name = '$publisher_name'";
    $result = $conn->query($sql);


    // check if publisher exists
    if ($result->num_rows == 0) {
        // insert publisher
        $sql = "INSERT INTO publisher (publisher_name) VALUES ('$publisher_name')";
        $conn->query($sql);
        // get publisher id
        $publisher_id = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $publisher_id = $row['publisher_id'];
    }


    // find genre id
    $sql = "SELECT genre_id FROM genre WHERE genre_name = '$genre'";
    $result = $conn->query($sql);

    // check if genre exists
    if ($result->num_rows == 0) {
        // insert genre
        $sql = "INSERT INTO genre (genre_name) VALUES ('$genre')";
        $conn->query($sql);
        // get genre id
        $genre_id = $conn->insert_id;
    } else {
        $row = $result->fetch_assoc();
        $genre_id = $row['genre_id'];
    }



    // insert data into database
    $sql = "INSERT INTO book (title, publisher_id, publish_date) VALUES ('$book_name', '$publisher_id', NOW())";
    $result1 = $conn->query($sql);

    $book_id = $conn->insert_id;

    // insert price and path into database
    $sql = "INSERT INTO e_book (book_id, price, content) VALUES ($book_id , '$price', '$targetfolder')";
    $result2 = $conn->query($sql);

    // insert genre into database
    $sql = "INSERT INTO book_genre (book_id, genre_id) VALUES ('$book_id', '$genre_id')";
    $conn->query($sql);

    // inser book_author into database
    $sql = "INSERT INTO author_publish_ebook (book_id, author_id, date) VALUES ('$book_id', '$user_id', NOW())";
    $conn->query($sql);

    // check if data was inserted
    if($result1 && $result2) {
        // script for successful insertion
        echo "<script>alert('Book was successfully published!')</script>";
    } else {
        // script for unsuccessful insertion
        echo "<script>alert('Book was not published!')</script>";
    }
}

?>
