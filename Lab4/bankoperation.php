<html>
<style>
table, th, td {
 border: 1px solid black;
 border-collapse: collapse;
}
</style>

<body>
<?php
require "db.php";
session_start();
if (!isset($_SESSION['username'])) {
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

if (isset($_POST["accounts"])) {
    $accounts = get_accounts($_SESSION["username"]);
    ?> 


    <table>
    <tr>
    <th>Account</th>
    <th>Balance</th>
    </tr>
    <?php
    foreach ($accounts as $row) {
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "</tr>";
    }
    echo "<table>";
}
?>
<?php
if (isset($_POST["transfer"])) {
    $accounts = get_accounts($_SESSION["username"]);
    ?> 
    <form method=post action=bankoperation.php>
        <label for ="from_account">From account: </label>
        <input type="text" name="from_account" id="from_account"><br>
        <label for ="to_account">To account: </label>
        <input type="text" name="to_account" id="to_account"><br>
        <label for ="amount">Amount: </label>
        <input type="number" name="amount" id="amount"><br>
        <input type="submit" name="confirm" value="Confirm">
    <?php
    }
?>
<?php

    if (isset($_POST["confirm"])) {
    $from = $_POST["from_account"];
    $to = $_POST["to_account"];
    $amount = $_POST["amount"];
    $user = $_SESSION["username"];
    transfer($from, $to, $amount, $user);
    }
?>

</body>
</html>