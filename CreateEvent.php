<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">

<!--create form HTML-->
<form action="" method="post" >
    <div class="container">
        <h1>Create Event</h1>
        <p>Please fill in this form to create an event.</p>
        <hr>

        <label for="eventname"><b>Event Name</b></label>
        <input type="text" placeholder="Enter Event Name" name="eventname" required>

        <label for="startdate"><b>Start Date</b></label>
        <input type="date" placeholder="Enter Event Start Date" name="startdate" required>

        <label for="enddate"><b>End Date</b></label>
        <input type="date" placeholder="Enter Event End Date" name="enddate" required>

<!--        location-->
        <label for="location"><b>Location</b></label>
        <input type="text" placeholder="Enter Event Location" name="location" required>


        <input type="submit" value="Submit" name="submit">
    </div>
</form>

<?php

session_start();
include_once "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages

// get data from submit button
if(isset($_POST['submit'])) {
    echo "submit button was pressed";

    // get data from form
    $event_name = $_POST['eventname'];
    $start_date = $_POST['startdate'];
    $end_date = $_POST['enddate'];
    $location = $_POST['location'];

    // connect to database
    $conn = getDatabaseConnection();
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{
        $stmt = $conn->prepare("insert into event(event_name, start_date, end_date, location, creator_id)
        values(?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $event_name, $start_date, $end_date, $location, $user_id);
        $stmt->execute();
        echo "Event created successfully...";
        $stmt->close();
        $conn->close();
    }
}
?>

</html>



