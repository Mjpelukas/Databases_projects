<?php 
require "db.php";
session_start();

if (!isset($_SESSION["student_username"])) {
    header("LOCATION:login.php");
}

if(isset($_POST["takeExam"])){
    $_Session["takeID"] = $_POST["takeExam"];
    header("LOCATION:takeExam.php");
    return;
}

if(isset($_POST["showScore"])){
    $_Session["scoreID"] = $_POST["showScore"];
    header("LOCATION:studentScoreBreakdown.php");
    return;
}

die(); //if somebody snuck in the page is killed
?>