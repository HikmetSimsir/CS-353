<?php
include_once "helper.php";
$conn = getDatabaseConnection();

$sql = "drop view if exists sys_admin_user";
$conn->query($sql);
$sql = "drop view if exists sys_author_user";
$conn->query($sql);
$sql = "drop view if exists ebooks_view";
$conn->query($sql);

$sql = "create view ebooks_view as 
    SELECT * FROM (e_book
    natural join author_publish_ebook natural join book
    natural join book_genre natural join genre natural join publisher), sys_author
         WHERE author_publish_ebook.author_id = sys_author.user_id";
$result = $conn->query($sql);

$sql = "create view sys_admin_user as
select *
from sys_admin natural join user;";
$result = $conn->query($sql);

$sql = "create view sys_author_user as
select *
from sys_author natural join user;";
$result = $conn->query($sql);

?>
