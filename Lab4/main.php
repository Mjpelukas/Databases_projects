
<html>
<?php
session_start();

?>
<body>
<?php
 if (!isset($_SESSION["username"])) {
 header("LOCATION:login.php");
 }else {
 echo '<p align="right"> Welcome '. $_SESSION["username"].'</p>';
?>
 <form method=post action = login.php>
 <p align="right">
 <input type="submit" value="logout" name="logout">
 </p>
 </form> 
<?php
 }
?>
<p>Welcome to our online minibank</p>
<p>We can help you transfer the money or display your accounts</p>
<p>What would you like to do? Please click one of the buttons</p>
    <form method= post action=bankoperation.php>
    <button name="transfer" value="transfer">Transfer</button>
    <button name="accounts" value="accounts">Accounts</button>
</form>

</body>
</html>

