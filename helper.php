<?php
function getDatabaseConnection(): mysqli
{

  $configs = include('config.php');
// connect database
  $conn = mysqli_connect($configs["hostname"], $configs["username"], $configs["password"], $configs["database"]);
// check connection
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  return $conn;
}

function connToSE(PDO $conn): Closure
{
  return function (string $str) use ($conn) {
    return $conn->quote($str);
  };

}

function reqLogIn()
{
  if (!isset($_SESSION['uname'])) {
    echo "Need to login";
    header("location: ./");
    exit();
  }
  echo '<a href="./LogOut.php">Log Out</a>';
}

function reqAdmin()
{
  if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    echo "Need to login as admin";
    header("location: ./");
    exit();
  }
  echo '<a href="./LogOut.php">Log Out</a>';
}

function reqAuthor()
{
  if (!isset($_SESSION['isAuthor']) || !$_SESSION['isAuthor']) {
    echo "Need to login as author";
    header("location: ./");
    exit();
  }
  echo '<a href="./LogOut.php">Log Out</a>';
}


function pqef(string $query, PDO $conn)
{
  $stmt = $conn->prepare($query);
  $stmt->execute();
  return $stmt;
}

function println(string $str)
{
  print($str);
  print("\n");
}

function vardumpln(object $str)
{
  var_dump($str);
  print("\n");
}

function getBookInfo(int $book_id, mysqli $conn): array
{
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

  return array("author_name"    => $author_name,
               "genre_name"     => $genre_name,
               "publisher_name" => $publisher_name,
               "book_title"     => $row2['title']);
}