<?php

// navigation bar for each user type (admin, user, sysauthor)
function navBar($isAdmin, $isSysAuthor) {
    echo "<h1> Social Networking App </h1>";
echo "<div class='topnav'>";


    echo "<a class='active'   href='Home.php'>  Home    </a>";

    echo "<a href=./makeReviewPage.php?title=&author=&publisher=&genre=> Make Review <br>  </a>";
    echo "<a href=./ListUsersPage.php?> Follow Other Users  </a>";

    echo "<a href=./followedUsersPage.php?> Followed Users  </a>";
    echo "<a href=./forumList.php> Forums  </a>";
    echo "<a href='DisplayEvents.php'> See Events  </a>";
    echo "<a href='CreateEvent.php'> Create Event  </a>";


    if ($isAdmin) {
        echo "<a href='Admin.php'>Admin  </a>";
        echo '<a href = "./forumCreate.php">Create Forum  </a>';
    }
    if ($isSysAuthor) {
        echo "<a href='Author.php'>Author  </a>";
    }
    echo "<a href='Logout.php'>Logout</a>";
    echo "</div>";
}
?>
