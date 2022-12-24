<?php
// navigation bar for each user type (admin, user, sysauthor)
function navBar($isAdmin, $isSysAuthor) {
    $user_id = $_SESSION['user_id'];

    echo "<h1> Social Networking for Readers App </h1>";
    echo "<div class='topnav'>";


    echo "<a class='active'   href='Home.php'>  Home    </a>";

    echo "<a href=./makeReviewPage.php?title=&author=&publisher=&genre=> Make Review <br>  </a>";
    echo "<a href=./ListUsersPage.php?> Follow Other Users  </a>";

    echo "<a href=./followedUsersPage.php?> Followed Users  </a>";
    echo "<a href=./forumList.php> Forums  </a>";
    echo "<a href='DisplayEvents.php'> See Events  </a>";
    echo "<a href='CreateEvent.php'> Create Event  </a>";
    echo "<a href='CreditCard.php'> Credit Card  </a>";
    echo "<a href='PurchaseBook.php'> Purchase E-Book  </a>";
    echo "<a href='SReportList.php'> System Report List  </a>";



    if ($isAdmin) {
        echo "<a href='ManageEBooks.php'>Manage EBooks  </a>";
        echo '<a href = "./forumCreate.php">Create Forum  </a>';

        echo "<a href='SReportCreate.php'> System Report Create  </a>";

    }
    if ($isSysAuthor) {
        echo "<a href='Author_Publish_EBook.php'>Publish E-Book  </a>";
        echo "<a href='AuthorsPage.php?author_id=$user_id'>Your EBooks </a>";
    }
    echo "<a href='Logout.php'>Logout</a>";
    echo "</div>";
}
?>
