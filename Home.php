
<!DOCTYPE html>
<head>
    <div class="topnav">
        <a class="active" href="#home">Home</a>
        <a href="#news">News</a>
        <a href="#contact">Contact</a>
        <a href="#about">About</a>
    </div>
</head>
<?php
session_start();

if (isset($_POST['uname']) && isset($_POST['psw'])) {
    $email = $_POST['uname'];
    $password = $_POST['psw'];

    $_SESSION['uname'] = $email;
    $_SESSION['psw'] = $password;
} else if (isset($_SESSION['uname']) && isset($_SESSION['psw'])) {
    $email = $_SESSION['uname'];
    $password = $_SESSION['psw'];
} else {
    session_destroy();
    header("Location: ./Login.php");
    exit();
}
?>
<?php
try {
    $conn = mysqli_connect("localhost", "root", "", "DBProject");
} catch (Exception $e) {
    echo "Connection failed: " . $e->getMessage();
}

// check if username and password is correct in database
$sql = "SELECT email, display_name FROM user WHERE email = '$email' AND password = '$password'";

try {
    $result = mysqli_query($conn, $sql);
} catch (Exception $e) {
    echo "Query failed: " . $e->getMessage();
    exit();
}

$row = mysqli_fetch_assoc($result);
$count = mysqli_num_rows($result);

if ($count == 1) {
    echo "<h3> Login successful <br> </h3>";
    echo "<h3> Welcome " . $row['display_name'] . ". </h3>";

} else {
    // wrong username or password
    // go back to login page
    $_SESSION['login'] = -1;
    header("Location: ./Index.php");
    exit();
}


?>

</html>
<?php
