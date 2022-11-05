<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<?php
include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();

//create nested array for the topic
try {
  $sql = "SELECT * FROM post WHERE parent_id IS NULL";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  var_dump($row);
  $count = mysqli_num_rows($result);
} catch (Exception $e) {
  echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";
}
?>
<body>
<table>
    <tr>
        <th>By caa</th>
    </tr>
    <tr>
        <td>
            <p>caa</p>
        </td>
    </tr>
    <tr>
        <td style="padding-left: 30px">
            <table>
                <tr>
                    <th> by bb</th>
                </tr>
                <tr>
                    <td><p> cbb </p></td>
                </tr>

                <tr>
                    <td style="padding-left: 30px">
                        <table>

                            <tr>
                                <th>by cc</th>
                            </tr>
                            <tr>
                                <td><p>ccc</p></td>
                            </tr>
                            <tr>
                                <td style="padding-left: 30px">
                                    <table>
                                        <tr>
                                            <th>by ee</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p>cee</p></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <table>
                            <tr>
                                <th>by dd</th>
                            </tr>
                            <tr>
                                <td style="padding-left: 30px">
                                    <p>cdd</p></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
<form action="" method="post">

    <p>
        <label for="uname">Email</label><br> <input type="email" name="uname" id="uname" required></p>
    <p>
        <label for="psw">Password</label><br> <input type="password" name="psw" id="psw" required>
    </p>
    <p>
        <button>Log in</button>
    </p>
    <p>
        <a href="SignUp.php">Not a member? Sign Up</a>
    </p>
</form>
</html>
