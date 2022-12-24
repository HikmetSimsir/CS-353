<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
<?php
    session_start();

    $isAuthor = $_SESSION['isAuthor'];
    $isAdmin = $_SESSION['isAdmin'];
    include "NavBar.php";
    navBar($isAdmin, $isAuthor);
    include_once "helper.php";
//    $user_id = 1; // this will come from session array, once it is connected to rest of the pages
    $user_id = $_SESSION['user_id'];
?>
<!--create form HTML-->
<form action="" method="post" >
    <div class="container">
        <h1>Create Event</h1>
        <p>Please fill in this form to create an event.</p>
        <hr>

        <label for="eventname"><b>Event Name</b></label>
        <input type="text" placeholder="Enter Event Name" name="eventname" required>

        <label for="startdate"><b>Event Date</b></label>
        <input type="date" placeholder="Enter Event Start Date" name="startdate" required>

<!--        start time-->
        <label for="starttime"><b>Start Time</b></label>
        <input type="time" placeholder="Enter Event Start Time" name="starttime" required>

<!--  end time-->
        <label for="endtime"><b>End Time</b></label>
        <input type="time" placeholder="Enter Event End Time" name="endtime" required>
<!--        description-->
        <label for="description"><b>Description</b></label>
        <input type="text" placeholder="Enter Event Description" name="description" required>


<!--        location-->
        <label for="location"><b>Location</b></label>
        <input type="text" placeholder="Enter Event Location" name="location" required>
        <input type="submit" value="Submit" name="submit">
    </div>
</form>

<?php


// get data from submit button
if(isset($_POST['submit'])) {
//    echo "submit button was pressed";

    // get data from form
    $event_name = $_POST['eventname'];
    $start_date = $_POST['startdate'];
    $start_time = $_POST['starttime'];
    $end_time = $_POST['endtime'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    // connect to database
    $conn = getDatabaseConnection();
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $stmt = $conn->prepare("insert into event(event_name, start_date, start_time, end_time, location, description, creator_id)
        values(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $event_name, $start_date, $start_time, $end_time, $location, $description, $user_id);
        $stmt->execute();

        // script to state event was created
        echo "<script type='text/javascript'>alert('Event Created!');</script>";

        $stmt->close();
        $conn->close();
    }
}
?>

</html>



