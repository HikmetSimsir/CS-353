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
session_start();

include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);

session_start();
include "NavBar.php";
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
navBar($isAdmin, $isAuthor);

class ForumPost
{
  public string $forum_id;
  public string $post_id;
  public array $children;
  public string $dname;
  public string $text;

  /**
   * @param $forum_id
   * @param $post_id
   */
  public function __construct(string $forum_id, string $post_id)
  {
    $this->forum_id = $forum_id;
    $this->post_id = $post_id;
    $this->Immchildren();
    $conn = getDatabaseConnection();
    $sql = "select display_name from post join user using (user_id) where post.forum_id = '$this->forum_id' and post_id = '$this->post_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $this->dname = $row[0]["display_name"];
    $sql = "select text from post join user using (user_id) where post.forum_id = '$this->forum_id' and post_id = '$this->post_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $this->text = $row[0]["text"];
  }

  public function Immchildren()
  {
    // get children
    $conn = getDatabaseConnection();
    $sql = "SELECT post_id FROM post WHERE post.forum_id = '$this->forum_id' and parent_id = '$this->post_id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $this->children = $row;
    return $this->children;
  }

  public function gettree(): array
  {

    $ret["post_id"] = $this->post_id;
    $ret["children"] = [];
    foreach ($this->children as $child) {
      $temp = new ForumPost($this->forum_id, $child["post_id"]);
      $tree = $temp->gettree();
      $ret["children"][] = $tree;
    }
    return $ret;
  }

  public function gethtml(): string
  {


    $ret["post_id"] = $this->post_id;
    $ret["children"] = [];
    $hlist = "";
    foreach ($this->children as $child) {
      $temp = new ForumPost($this->forum_id, $child["post_id"]);
      $tree = $temp->gethtml($this->post_id);
      // language=HTML
      $hlist = $hlist . $tree;
    }

    // language=HTML
    $html = "
<table>
    <tr>
        <td>
        <ul>
  <!-- <li>{$this->post_id}</li> -->
  <li>User: {$this->dname}</li>
  <li>Post: {$this->text}</li>
  <li><a href='forumReply.php?postid=$this->post_id&forumid={$this->forum_id}'>Reply to this post</a>
</li>
  <ul>
    {$hlist}
  </ul>
</ul>
        </td>
    </tr>
</table>
";
    return $html;
  }

}


include_once "helper.php";
$conn = getDatabaseConnection();
reqLogIn();
//create a nested array for the topic
try {
  $forumid = $_GET["forumid"];
  $sql = "SELECT display_name, title, creationDate FROM book_forum join user using (user_id) WHERE forum_id = '$forumid'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $title = $row[0]["title"];
  $dname = $row[0]["display_name"];
  $cdate = $row[0]["creationDate"];

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
<a href='forumPost.php?forumid={$forumid}'>Reply to this forum</a>
</p>
";
  echo $html;
  //create nested structure
  // get only top comments
  $sql = "SELECT post_id FROM post WHERE post.forum_id = '$forumid' and parent_id is null ";
  $result = mysqli_query($conn, $sql);
  $rowtop = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //$topAllChilren = [];
  $topAllhtml = "";
  foreach ($rowtop as $value) {
    $temp = new ForumPost($forumid, $value["post_id"]);
    //$topAllChilren[] = $temp->gettree();
    $topAllhtml = $topAllhtml . $temp->gethtml(null);
  }
  //var_dump();
  echo $topAllhtml;

} catch (Exception $e) {
  println($e->getMessage());
  echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";

} ?>
</body>
</html>
