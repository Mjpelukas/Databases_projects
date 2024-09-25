<?php
require "db.php";
session_start();
?>
<html>
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Take Exam</title>
    </head>

<body>
<?php
 if (!isset($_SESSION["student_username"])) {
 header("LOCATION:login.php");
 }else {
    echo '<div class ="currentUserBar">';
    echo '<p align="right"> Welcome '. $_SESSION["student_username"].'</p>';
?>
 <form method=post action = login.php>
 <input type="submit" value="logout" name="logout"> </form> 
 </div><br><br><br><br>
<?php
 $isAvailable = examTimeChecker($_POST["eid"]);
if($isAvailable == 1){
    echo "<h3> Not entirely sure how you got to this point, but it's too early to submit this exam.<h3>";

 }else{
    submitExam($_POST, $_SESSION["student_username"]);
 } 


 }//ends the inital else statement
 ?>

<form method="post" action="studentHome.php"> <button class="backButton" type="submit" value="back">Back To Home</button></form>
</body>
</html>