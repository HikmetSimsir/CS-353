<?php
session_start();
include_once "helper.php";
//$user_id = 1; // this will come from session array, once it is connected to rest of the pages

if (isset($_POST['edit_event_id'])) {
    $_SESSION['edit_event_id'] = $_POST['edit_event_id'];
}
$edit_event_id = $_SESSION['edit_event_id'];
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<h1>Edit Event</h1>


<?php
$isAuthor = $_SESSION['isAuthor'];
$isAdmin = $_SESSION['isAdmin'];
include "NavBar.php";
navBar($isAdmin, $isAuthor);
//echo "Edit Event ID: " . $edit_event_id . "<br>";
// display edit page for event
$conn = getDatabaseConnection();
$sql = "SELECT * FROM event WHERE event_id = " . $edit_event_id;
$results = $conn->query($sql);
$row = $results->fetch_assoc();
$user_id = $_SESSION['user_id'];


// check if the event is found
if ($results->num_rows == 0) {
    exit("Event not found");
}



// display event data in table
echo "Event Info: <br><table>";
echo "<tr><th>Event Name</th><th>Start Date</th><th>Start Time</th><th>End Time</th><th>Description</th></th><th>Location</th></tr>";

echo "<tr><td>" . $row['event_name'] . "</td><td>" . $row['start_date'] . "</td><td>" . $row['start_time'] . "</td><td>" . $row['end_time'] . "</td><td>" . $row['description'] . "</td><td>" . $row['location'] . "</td></tr>";

echo "</table>";



?>


<form action="" method="post" >
    <div class="container">

        <p>Edit Desired Properties of The Event.</p>
        <hr>

        <label for="eventname"><b>Event Name</b></label>
        <input type="text" placeholder="Enter Event Name" name="eventname" >

        <label for="startdate"><b>Start Date</b></label>
        <input type="date" placeholder="Enter Event Start Date" name="startdate" >

        <label for="starttime"><b>Start Time</b></label>
        <input type="time" placeholder="Enter Event Start Time" name="starttime" >

<!--        end time-->
        <label for="endtime"><b>End Time</b></label>
        <input type="time" placeholder="Enter Event End Time" name="endtime" >

        <label for="description"><b>Description</b></label>
        <input type="text" placeholder="Enter Event Description" name="description" >


        <!--        location-->
        <label for="location"><b>Location</b></label>
        <input type="text" placeholder="Enter Event Location" name="location" >


        <input type="submit" value="Edit" name="submit">
    </div>

</form>

<!--delete event button-->
<form action="" method="post">
    <input type="hidden" name="delete_event_id" value="<?php echo $edit_event_id; ?>">
    <input type="submit" name="delete" value="Delete Event">
</form>

<!---->
<?php
// check if user clicked on edit or delete button
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        echo "Edit button was pressed";
        // get data from form if they are set
        if (isset($_POST['eventname']) && !empty($_POST['eventname'])) {
            $event_name = $_POST['eventname'];
        } else {
            $event_name = $row['event_name'];
        }
        if (isset($_POST['startdate']) && !empty($_POST['startdate'])) {
            $start_date = $_POST['startdate'];
        } else {
            $start_date = $row['start_date'];
        }
        if (isset($_POST['starttime']) && !empty($_POST['starttime'])) {
            $start_time = $_POST['starttime'];
        } else {
            $start_time = $row['start_time'];
        }

        if (isset($_POST['endtime']) && !empty($_POST['endtime'])) {
            $end_time = $_POST['endtime'];
        } else {
            $end_time = $row['end_time'];
        }

        if (isset($_POST['description']) && !empty($_POST['description'])) {
            $description = $_POST['description'];
        } else {
            $description = $row['description'];
        }
        if (isset($_POST['location']) && !empty($_POST['location'])) {
            $location = $_POST['location'];
        } else {
            $location = $row['location'];
        }

        // connect to database
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("UPDATE event SET event_name = ?, start_date = ?, start_time = ?, end_time = ?, description = ?, location = ? WHERE event_id = ?");
            $stmt->bind_param("ssssssi", $event_name, $start_date, $start_time, $end_time, $description, $location, $edit_event_id);
            $stmt->execute();
            echo "Event Updated Successfully";
            $stmt->close();
            $conn->close();
        }
    } else if (isset($_POST['delete'])) {
        echo "Delete button was pressed";
        // connect to database
        $conn = getDatabaseConnection();
        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $stmt = $conn->prepare("DELETE FROM event WHERE event_id = ?");
            $stmt->bind_param("i", $edit_event_id);
            $stmt->execute();
            echo "Event Deleted Successfully";
            $stmt->close();
            $conn->close();
        }

        // redirect to display events page
        header("Location: DisplayEvents.php");

    }

    // refresh page
    header("Refresh:0");
}






