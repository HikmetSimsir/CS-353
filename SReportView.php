<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
<?php
include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();
//create a nested array for the topic
try {
  $repid = $_GET["repid"];
  $sql = "SELECT * FROM sys_adm_user join system_report where report_id = '$repid'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $dname = $row[0]["display_name"];
  $title = $row[0]["report_id"] . "by user " . $dname;
  $cdate = $row[0]["date"];
  $content = $row[0]["comment"];


  // language=HTML
  $html = "
<h1>
Title: {$title}
</h1>
<p><em>
by: $dname
</em></p>
<p>
date: $cdate
</p>
<p>
<table>
<tr>
        <th>
            Content
    <tr>
        <td>
        $content 
</td>
</tr>
</table>
</p>
";
  echo $html;

} catch (Exception $e) {
  println($e->getMessage());
  echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";

} ?>
</body>
</html>
