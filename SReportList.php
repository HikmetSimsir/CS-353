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

include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);

//reqLogIn();
//reqAdmin();

//create nested array for the topic
try {
$sql = "SELECT * FROM system_report natural JOIN sys_admin_user";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_all($result, MYSQLI_ASSOC); ?>
<body>
<table>
    <tr>
        <th>
            Title
        </th>
        <th>
            Creation Date
        </th>
    </tr>
  <?php
  foreach ($row as $item) {
    // language=HTML
    $html = "
<tr>
<td>
<a href='SReportView.php?repid=" . $item["report_id"] . "'" . ">" . $item["report_id"] . "by user " . $item["dname"] . "</a>
</td>
<td>" . $item["date"] . "</td>
</tr>";
    echo $html;
    println("");
  }

  } catch (Exception $e) {
    echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";
  }
  ?>
</table>
</body>
</html>
