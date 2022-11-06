<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<?php

class ForumPost
{
  public string $forum_id;
  public string $post_id;
  public array $children;

  /**
   * @param $forum_id
   * @param $post_id
   */
  public function __construct(string $forum_id, string $post_id)
  {
    $this->forum_id = $forum_id;
    $this->post_id = $post_id;
    $this->Immchildren();
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

}


include_once "helper.php";
session_start();
$conn = getDatabaseConnection();
reqLogIn();

//create nested array for the topic
try {
  $forumid = $_GET["forumid"];
  //create nested structure
  // get only top comments
  $sql = "SELECT post_id FROM post WHERE post.forum_id = '$forumid' and parent_id is null ";
  $result = mysqli_query($conn, $sql);
  $rowtop = mysqli_fetch_all($result, MYSQLI_ASSOC);
  $topAllChilren = [];
  foreach ($rowtop as $value) {
    $temp = new ForumPost($forumid, $value["post_id"]);
    $topAllChilren[] = $temp->gettree();
  }
  var_dump($topAllChilren);

} catch (Exception $e) {
  println($e->getMessage());
  echo "<script type='text/javascript'>alert('" . $e->getMessage() . "');</script>";

} ?>
<body></body>
</html>
