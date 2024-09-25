<?php

session_start();
require "db.php";
function register($user, $cid){
    try {
        $dbh = connectDB();
        $dbh->beginTransaction();
        //start getting the inital things
        $statement = $dbh->prepare("SELECT count(*) from registers where sAccount = ? AND cid = ?");
        $statement->bindParam(1, $user);
        $statement->bindParam(2, $cid);
        $statement->execute();
        $row = $statement->fetch();
        echo $row[0];
        if($row[0] != 0 ){
            echo '<h3> You already are enrolled in this course</h3>';
            $dbh->rollBack();
            $dbh = null;
            return;
         }
        $statement = $dbh->prepare("SELECT iAccount from teaches where cid = :cid");
        $statement->bindParam(":cid", $cid);
        $statement->execute();
        $row = $statement->fetch();
        if(sizeOf($row) == 0 ){
            echo '<h3> There are no teachers for this course curently</h3>';
            $dbh->rollBack();
            $dbh = null;
            return;
        }
        $statement = $dbh->prepare("INSERT INTO registers VALUES(:user, :cid, :tid)");
        $statement->bindParam(":user", $user);
        $statement->bindParam(":cid", $cid);
        $statement->bindParam(":tid", $row[0]);
        $statement->execute();
        $dbh->commit();
    $dbh = null;
    echo"<h3> You successfully registered</h3>";
    return;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
} ?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Register</title>
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
        <br><br>
        </div> <br><br><br><br>
        <?php
         register($_SESSION["student_username"], $_POST["cid"]);
        }//ends earlier elsev
        ?>
        <form method="post" action="studentHome.php"> <button class="backButton" type="submit" value="back">Back To Home</button></form>
</body>
</html>
