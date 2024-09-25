<?php
require "db.php";
session_start();
$eid = get_eid($_POST["eName"], $_POST["cid"]);
?>
<html>
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Exam Review</title>
    </head>

<body>
<?php
 if (!isset($_SESSION["instructor_username"])) {
 header("LOCATION:login.php");
 }else {
    echo '<div class ="currentUserBar">';
    echo '<p align="right"> Welcome '. $_SESSION["instructor_username"].'</p>';
?>
 <form method=post action = login.php>
 <input type="submit" value="logout" name="logout"> </form> 

 </div>
 <br><br>
 <?php
if(sizeof($eid) > 0){
echo "<h2> Exam: ".$eid[1]." </h2>";
 if($_POST["examReview"] == "score" ){
    ?>
<h2> Here is your Exams</h2>
<br>
<table>
    <tr>
        <th>Course ID</th>
        <th>Exam Name</th>
        <th>Accepted Attempts</th>
        <th>Min Score</th>
        <th>Max Score</th>
        <th>Average</th>
        </tr>
        <tr>
<?php
$aggdata = get_instructor_exam_info($eid[0]);
echo '<td>'.$_POST["cid"].'</td>';
echo '<td>'.$_POST["eName"].'</td>';

echo "<td>".$aggdata[0]."</td>";
echo "<td>".$aggdata[1]."</td>";
echo "<td>".$aggdata[2]."</td>";
echo "<td>".$aggdata[3]."</td>";

?>
</tr></table><br>


<h4>Student Scores<h4>
    <table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Score</th>
        </tr>
<?php
$students = get_instructor_exam_students($eid[0]);
foreach ($students as $sid){
    echo "<tr><td>".$sid[0]."</td>";
    $sData = get_instructor_exam_students_attempt($eid[0], $sid[0]);
    echo "<td>".$sData[0]."</td>";
    echo "<td>".$sData[1]."</td>";
    echo "<td>".$sData[2]."</td>";
    echo "<td>".$sData[3]."</td></tr>";
}
echo "</table></br>";




} //ends score if statement

if($_POST["examReview"] == "review" ){
    echo "<h2>Question Review<H2><br>";
    $examQuestions = getExamQuestions($eid[0]);
    $questionCount = sizeof($examQuestions);
    foreach ($examQuestions as $question) {
        echo '<h3>Question Number '.$question[1].': </h3>' ;//qNumber
        echo '<h4>'.$question[2].'</h4>'; // qDescription
        echo '<label> The correct choice is: '.$question[3].'</label><br><br>'; // qDescription
        $questionChoices = getReviewChoices($question[0]); //question[0] is the qid
        //[] = identifier, cDescription, correctChoice, points
        foreach ($questionChoices as $choice) {
            echo'<label>'.$choice[0].': '.$choice[1].'</label><br>';
        }
        echo '<br>';
    }
}
}else{ //if eid not found
    echo "<h2> You entered the Course ID or Exam Name wrong, please go back and try again</h3>"; //ends other if statement
}
}//end the first else else statement
?>

<br><br>
<form method="post" action="instructorHome.php"> <button class="backButton" type="submit" value="back">Back To Home</button></form>
</body>
</html>