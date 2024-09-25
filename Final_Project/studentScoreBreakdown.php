<?php
require "db.php";
session_start();
$eid = $_POST["showScore"];

$now = date('Y-m-d H:i:s');
?>
<html>
<head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Score Breakdown</title>
    </head>

<body>
<?php
 if (!isset($_SESSION["student_username"])) {
 header("LOCATION:login.php");
 }else {
    echo '<div class ="currentUserBar">';
    echo '<p align="right"> Welcome '. $_SESSION["student_username"].'</p>';

    echo' <form method=post action = login.php>';
    echo' <input type="submit" value="logout" name="logout"> </form>  <br><br>';
     echo'</div> <br><br><br>';
    
     if(!empty($_POST["aid"])){
        $aid = $_POST["aid"];

     echo "<h1> Exam: ".$_POST["eName"]." </h1>";
     ?>

<h2> Here is your Exam Score</h2>
<br>
<table>
    <tr>
        <th>Exam Name</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Score</th>
        </tr>
<?php
$exam = get_student_exam_specific($_SESSION["student_username"], $eid, $aid); {
echo "<td>" . $exam[1] . "</td>";
 echo "<td>" . $exam[2] . "</td>";
 echo "<td>" . $exam[3] . "</td>";
 echo "<td>" . $exam[4] . "</td>";
 echo "</tr>";
 echo"</table><br>";
}
?>
<table>
    <tr>
        <th>QNO</th>
        <th>Question Description</th>
        <th>Your Answer</th>
        <th>Correct Answer</th>
        <th>Score</th>
        </tr>
<?php
    $examQuestions = getExamQuestions($eid);
    $questionCount = sizeof($examQuestions);
    foreach ($examQuestions as $question) {
        echo '<td>Question '.$question[1].'</td>' ;//qNumber
        echo '<td>'.$question[2].'</td>'; // qDescription
        $answer = getStudentAnswer($question[0], $aid); //question[0] is the qid
        //[] = answer correct points
        echo '<td>'.$answer[0].'</td>'; // stu answer
        echo '<td>'.$answer[1].'</td>'; // correct
        if(strcmp($answer[0],$answer[1]) == 0){
            echo '<td>'.$answer[2].'</td>'; // points if correct
        }else{
            echo '<td> 0 </td>'; //if wrong
        }
        echo "</tr>";
    }
    
    echo"</table><br>";
        echo '<br>';
    }else{//end the if
     echo "<h1> You need to take the exam first</h3>";
    }
}//end the first else else statement
?>

<br><br>
<form method="post" action="studentHome.php"> <button class="backButton" type="submit" value="back">Back To Home</button></form>
</body>
</html>