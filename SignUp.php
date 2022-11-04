<?php

$userExists = false;
$successfullSignup = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $conn = mysqli_connect("localhost", "root", "", "dbproject");
    session_start();


    $username = $_POST["username"];
    $password = $_POST["psw"];


    $checkUserNameExistQuery = "Select * from user where email='$username'";
    $result = mysqli_query($conn, $checkUserNameExistQuery);
    $count = mysqli_num_rows($result);


    $totalNumberOfUsersQuery = mysqli_query($conn, "Select * from user;");
    $currentNumberOfUsers = mysqli_num_rows($totalNumberOfUsersQuery);


    // check if user with same name exists
    if($count == 0) {

        $userid = $currentNumberOfUsers + 1;
        $newUserInsertionQuery = mysqli_query($conn,"Insert into user values('$username',$userid,'$password',null);" );
        $userExists=false;
        $successfullSignup = true;

    }
    else
    {
        $userExists=true;
    }

}

?>









<div class="container">

    <h1 class="text-center">Signup </h1>
    <form action="SignUp.php" method="post">

        <div class="form-group">
            <label for="username">Email</label>
            <input type="text" class="form-control" id="username"
                   name="username" aria-describedby="emailHelp">
        </div>



        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control"
                   id="password" name="psw">
        </div>
        </div>

        <button type="submit" class="btn">
            SignUp
        </button>
    </form>

        <a href="Login.php"> Go to the login page   </a>
</div>

<?php if ($userExists): ?>
    <em>!!!User with same name exists, Signup Failed!!!</em>
    <br>
<?php endif; ?>

<?php if ($successfullSignup): ?>
    <em>!!!You have succesfully signed up, please login!!!</em>
    <br>
<?php endif; ?>



