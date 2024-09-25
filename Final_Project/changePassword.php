<?php

session_start();
require "db.php";


function changePassword($type, $user, $oldPassword, $newPassword){
    if($type == "instructor"){
        $x = "i";
    }
    if($type == "student"){
        $x = "s";
    }
    $query = "UPDATE ".$type." SET password = sha2(?, 256), initialSetup = 0 WHERE ".$x."Account = ? AND password = sha2(?, 256)";
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare($query);
        $statement->bindParam(1, $newPassword);
        $statement->bindParam(2, $user);
       
        $statement->bindParam(3, $oldPassword);
        $statement->execute();
        echo "<h2>Your Password Has Been Changed!</h2>";
        $dbh = null;
        return;
    } catch (PDOException $e) {
        print "Error" . $e->getMessage() . "<br/>";
        die();
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Change Password</title>
    </head>
    <body>
<?php

changePassword($_POST["userType"],$_POST["username"], $_POST["oldPassword"],$_POST["newPassword"]);
?>
<form method=post action=login.php>
<input type="submit" value="Go Home">
</form>
</body>
</html>