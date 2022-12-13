<?php
session_start();
include "helper.php";
$user_id = 1; // this will come from session array, once it is connected to rest of the pages

?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Create Event</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./styles.css">

<?php

// list all credit cards of the user
$conn = getDatabaseConnection();
$sql = "SELECT * FROM user_has_credit_card WHERE user_id = $user_id";
$result = $conn->query($sql);

echo "<h2>Your Credit Cards</h2>";
// display credit cards as a table
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // fetch credit card info
        $card_id = $row['card_id'];
        $sql2 = "SELECT * FROM credit_card WHERE card_id = $card_id";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();

        // display credit card info
        echo "<table> <tr><th>Card Number</th><th>Card Type</th><th>Name on Card</th><th>Card Expiry Date</th><th>Remove</th></tr>";
        echo " <tr><td>" . $row2['card_number'] . "</td>" . "<td>" . $row2['card_type'] . "</td><td>" .
            $row2['name_on_card'] . "</td><td>" . $row2['due_date_month'] . "/" . $row2['due_date_year'] . "</td><td>";

        echo "<form method='post'>";
        echo "<input type='hidden' name='card_id' value='" . $card_id . "'>";
        echo "<input type='submit' value='Remove' name='remove'>";
        echo "</form>";
        echo "</td></tr>";

    }
    echo "</table>";
} else {
    echo "You have no credit cards";
}
?>
    <!--create form HTML-->
    <form action="" method="post" >
        <div class="container">
            <h1>Add credit card</h1>
            <p>Please fill in this form to add a credit card.</p>
            <hr>

            <label for="card_number"><b>Card Number</b></label>
            <input type="text" placeholder="Enter Card Number" name="card_number" required>
<!--card type drown down-->
            <label for="card_type"><b>Card Type</b></label>
            <select name="card_type" id="card_type">
                <option value="Visa">Visa</option>
                <option value="MasterCard">MasterCard</option>
                <option value="American Express">American Express</option>
            </select>



            <label for="name_on_card"><b>Name on Card</b></label>
            <input type="text" placeholder="Enter Name on Card" name="name_on_card" required>

            <!--            mont on card must be beteen 1 and 12 -->


            <label for="due_date_month"><b>Due Date Month</b></label>
            <input type="text" placeholder="Enter Due Date Month" name="due_date_month" required>

            <label for="due_date_year"><b>Due Date Year</b></label>
            <input type="text" placeholder="Enter Due Date Year" name="due_date_year" required>

<!--            ccv-->
            <label for="cvv"><b>CVV</b></label>
            <input type="text" placeholder="Enter CVV" name="cvv" required>


            <input type="submit" value="Submit" name="submit">
        </div>
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['card_id'])) {
        $card_id = $_POST['card_id'];
        echo "card id: " . $card_id;

        if ($conn->connect_error) {
            die('Connection Failed : ' . $conn->connect_error);
        } else {
            $sql = "DELETE FROM credit_card WHERE card_id = $card_id";
            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

    }
    // refresh page
     header("Refresh:0");

}

if (isset($_POST['submit'])) {
    echo "submit button was pressed";

    // get data from form
    $card_number = $_POST['card_number'];
    $card_type = $_POST['card_type'];
    $name_on_card = $_POST['name_on_card'];
    $due_date_month = $_POST['due_date_month'];
    $due_date_year = $_POST['due_date_year'];
    $cvv = $_POST['cvv'];



    // connect to database
    $conn = getDatabaseConnection();
    if($conn->connect_error){
        die('Connection Failed : '.$conn->connect_error);
    }else{

        // insert into credit card table
        // check if card is already in the database
        $sql = "SELECT * FROM credit_card WHERE card_number = $card_number";
        $result3 = $conn->query($sql);
        if ($result3->num_rows > 0) {
            echo "card already exists";

        } else {

            $sql = "INSERT INTO credit_card (card_number, card_type, name_on_card, due_date_month, due_date_year, cvv) 
            VALUES ('$card_number', '$card_type', '$name_on_card', '$due_date_month', '$due_date_year', '$cvv')";
            $result = $conn->query($sql);

            if ($result === TRUE) {
                echo "New card created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        // get card id
        $sql = "SELECT card_id FROM credit_card WHERE card_number = $card_number";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $card_id = $row['card_id'];


        // insert card id and user id into user_has_credit_card table
        // check if card is already in the database
        $sql = "SELECT * FROM user_has_credit_card WHERE card_id = $card_id and user_id = $user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // user already has this card
            echo "user already has this card";
        } else {


            $sql2 = "INSERT INTO user_has_credit_card (user_id, card_id) VALUES ($user_id, $card_id)";
            $result2 = $conn->query($sql2);

            if ($result2 === TRUE) {
                echo "Card added to the user successfully";
            } else {
                echo "Error: " . $sql2 . "<br>" . $conn->error;
            }
        }
    }
    header("Refresh:0");
}



