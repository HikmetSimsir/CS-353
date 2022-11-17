<?php
session_start();
include_once "helper.php";

$userid = 1; // this will come from session array, once it is connected to rest of the pages
?>

<!DOCTYPE html>
<html>
<head>
    <title>Participate Event</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<?php
//display all events
$conn = getDatabaseConnection();
$sql = "SELECT * FROM event";

$results = $conn->query($sql);

if($results->num_rows > 0){
    echo "<h2>Events</h2>";
    echo "Here are the events you can participate in. Click on the event name to participate.<br>";
    echo "If you are the creator of an event, you can edit  it by clicking on the Edit button.<br><br>";
    echo "<table><tr><th>Event Name</th><th>Start Date</th><th>Start Time</th><th>Location</th><th>Description</th><th>Creator Name</th><th>Participate/Edit</th></tr>";
    while($row = $results->fetch_assoc()){


//        echo "<div class='event'>";
//        echo "<h2>Event Name: " . $row['event_name'] . "</h2>";
//        echo "<p>Start Date: " . $row['start_date'] . "</p>";
//        echo "<p>End Date: " . $row['end_date'] . "</p>";
//        echo "<p>Location: " . $row['location'] . "</p>";

        echo "<tr><td>" . $row['event_name'] . "</td><td>" . $row['start_date'] . "</td><td>" . $row['start_time'] . "</td><td>" .
            $row['location'] . "</td><td>" . $row['description'] . "</td><td>";

        // check if the user is the owner of the event


        // find creator name from user table
        $sql3 = "SELECT * FROM user WHERE user_id = " . $row['creator_id'];
        $results3 = $conn->query($sql3);
        $row3 = $results3->fetch_assoc();
//        echo "<p>Created by: " . $row3['first_name'] . " " . $row3['last_name'] . "</p>";
        echo "" . $row3['display_name'] . "</td><td>";


        if($row['creator_id'] == $userid) {
            // put delete form
            echo "<form action='EditEvent.php' method='post'>";
            echo "<input type='hidden' name='edit_event_id' value='" . $row['event_id'] . "'>";
            echo "<input type='submit' name='edit' value='Edit Event'>";
            echo "</form>";
        } else {
            // check if user has already participated in event
            $sql2 = "SELECT * FROM user_participate_event WHERE event_id = ".$row['event_id']." AND user_id = ".$userid;
            $results2 = $conn->query($sql2);

            if ($results2->num_rows > 0) {
                echo "<form method='post'>";
                echo "<input type='hidden' name='participated_event_id' value='" . $row['event_id'] . "'>";
                echo "<input type='submit' value='Leave' name='participate'>";
                echo "</form>";
            } else {
                echo "<form method='post'>";
                echo "<input type='hidden' name='participate_event_id' value='" . $row['event_id'] . "'>";
                echo "<input type='submit' value='Participate' name='participate'>";
                echo "</form>";
            }
        }
        echo "</form>";
        echo "</div>";
    }
} else {
    echo "0 results";

}

?>


<?php
// get date from hidden input
// if post request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    if (isset($_POST['participate_event_id'])) {
        $event_id = $_POST['participate_event_id'];
        echo "participate event id: " . $event_id;
        $user_id = $userid;

        // connect to database
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("insert into user_participate_event(event_id, user_id) values(?, ?)");
            $stmt->bind_param("ii", $event_id, $user_id);
            $stmt->execute();
            echo "Participation Successful";
            $stmt->close();
            $conn->close();
        }
    } else if (isset($_POST['participated_event_id'])) {
        $event_id = $_POST['participated_event_id'];
        $user_id = $userid;

        // connect to database
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("delete from user_participate_event where event_id = ? and user_id = ?");
            $stmt->bind_param("ii", $event_id, $user_id);
            $stmt->execute();
            echo "Participation Removed";
            $stmt->close();
            $conn->close();
        }
    }



//    else if (isset($_POST['delete'])) {
//        $event_id = $_POST['event_id'];
//
//        // connect to database
//        $conn = getDatabaseConnection();
//        if ($conn->connect_error) {
//            die('Connection Failed : ' . $conn->connect_error);
//        } else {
//            $stmt = $conn->prepare("delete from event where event_id = ?");
//            $stmt->bind_param("i", $event_id);
//            $stmt->execute();
//            echo "Event Deleted";
//            $stmt->close();
//            $conn->close();
//        }
//    }

    // refresh page
    header("Refresh:0");

}


?>



