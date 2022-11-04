<!Doctype Html>
<Html>
<Head>
    <Title>Welcome</Title>
</Head>

<H3>Welcome to Book Application </H3>
<Br>

<form action="./Home.php" method="post">

    <div class="container">
        <label for="uname"><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="uname" required>

        <label for="psw"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required>

        <button type="submit">Login</button>
    </div>

    <div class= "container">
        <a href="SignUp.php"> Not a member ? Sign Up    </a>
    </div>

</form>
<h1>
    <?php
    session_start();

    if (isset($_SESSION['login']) && $_SESSION['login'] == -1)
        echo "<h3>Id or password is wrong.<h3>";

    $_SESSION['login'] = -2;
    ?>
</h1>
</Html>




