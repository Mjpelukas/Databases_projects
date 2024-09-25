<?php
require "db.php";
session_start();
if (isset($_POST["logout"])) {
    session_destroy();
    
   }

?>
<html>
<body>
    <form method=post action=login.php>
    <label for="username">username:</label>
    <input type="text" id="username" name="username"><br>
    <label for="password">password:</label>
    <input type="text" id="password" name="password"><br>
    <input type="submit" name="login" value="login">

 <?php
// user clicked the login button */
if ( isset($_POST["login"]) ) {

 //check the username and passwd, if correct, redirect to main.php page
    if (authenticate($_POST["username"], $_POST["password"]) ==1) {
        //sets username
        $_SESSION["username"]=$_POST["username"];
        //goes to main.php
        header("LOCATION:main.php");
    return;
    }else {
    echo '<p style="color:red">incorrect username and password</p>';
    }
}
?>
</form>
</body>
</html>