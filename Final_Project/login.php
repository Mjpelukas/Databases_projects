<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Login</title>
    </head>
    <body>
<?php
require "db.php";
session_start();
if (isset($_POST["logout"])) {
    session_destroy();    
}

if (!isset($_POST["passChange"]) ) {
?>
<h1> Login</h1>
    <form method=post action=login.php>
        <label for="username">username:</label>
        <input type="text" id="username" name="username"><br>
        <label for="password">password:</label>
        <input type="password" id="password" name="password"><br>
        <input type="radio" name="userType" value="student"><label>As Student</label>
        <input type="radio" name="userType" value="instructor"><label>As Instructor</label><br>
        <input type="submit" name="login" value="Login">
        <input type="submit" name="passChange" value="Go To Change Password">
</form>
    
<?php 
}else{

    ?>  
    <h1>Change Your Password Here</h1>
     <form method=post action=changePassword.php>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br>
        <label for="oldPassword">Current Password:</label>
        <input type="password" id="oldPassword" name="oldPassword"><br>
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword"><br>
        <input type="submit" name="changePass" value="Change Your Password">
        <input type="radio" name="userType" value="student"><label>As Student</label>
        <input type="radio" name="userType" value="instructor"><label>As Instructor</label>
        </form>
        <p> You can discard your progress without saving here</p>
<form method="post" action="login.php"> <button class="backButton" type="submit" value="back">Back To Login</button></form>
<?php
}
// user clicked the login button */
if ( isset($_POST["login"]) ) {
    if(isset($_POST["userType"])){
        $dbh = connectDB();
        if($_POST["userType"]=="instructor"){
            $authCheck = authenticateInstructor($_POST["username"], $_POST["password"]);
            //check the username and passwd, if correct, redirect to main.php page
            if ($authCheck == 69){
                echo '<p style="color:red">You need to change your password to finish setup</p>';
            }
            elseif ($authCheck ==1) {
                //sets username
                    $_SESSION["instructor_username"] = $_POST["username"];
                    //goes to instructorHome.php
                    header("LOCATION:instructorHome.php");

            }elseif ($authCheck == 0) {
            echo '<p style="color:red">incorrect username and/or password</p>';
            }
        }
        if($_POST["userType"]=="student"){
            $authCheck = authenticateStudent($_POST["username"], $_POST["password"]);
            //check the username and passwd, if correct, redirect to main.php page
            if ($authCheck == 69){
                echo '<p style="color:red">You need to change your password to finish setup</p>';
            } elseif ($authCheck ==1) {
                
                    $_SESSION["student_username"]=$_POST["username"];
                    //goes to instructorHome.php
                    header("LOCATION:studentHome.php");
                }elseif($authCheck == 0) {
                echo '<p style="color:red">incorrect username and/or password</p>';
                }
            return;
        }
    }else{
        echo "<h3> Please select a login type";
    }
}
?>
</body>
</html>
