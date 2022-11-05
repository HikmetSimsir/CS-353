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