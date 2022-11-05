<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
</head>
<body>
<section id="ideas">
    <h1>Welcome to Book Application </h1><br>
    <form action="./Home.php" method="post">

        <p>
            <label>Email</label><br> <input type="email" name="uname" required>
        </p>
        <p>
            <label>Password</label><br> <input type="password" name="psw" required>
        </p>
        <p>
            <button>Log in</button>
        </p>
        <p>
            <a href="SignUp.php">Not a member? Sign Up</a>
        </p>
    </form>
</section>
<h1>
  <?php
  session_start();

  if (isset($_SESSION['login']) && $_SESSION['login'] == -1) echo "<h3>Id or password is wrong.<h3>";

  $_SESSION['login'] = -2;
  ?>
</h1>
</body>
</html>