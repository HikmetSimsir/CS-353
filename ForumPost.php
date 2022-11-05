<?php

class ForumPost
{
  public $forum_id;
  public $post_id;
  public $children;

  /**
   * @param $forum_id
   * @param $post_id
   */
  public function __construct($forum_id, $post_id)
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

  public function gettree()
  {
    $this->Immchildren();
    if ($this->children == null) {
      return null;
    }
    $allChildren = [];
    $counter = 0;
    foreach ($this->children as $child) {
      $temp = new ForumPost($this->forum_id, $child);
      $t = $temp->gettree();
      if ($t != null) {
        $allChildren[$counter] = $t;
        $counter = $counter + 1;
      }
    }
    return $allChildren;
  }

}