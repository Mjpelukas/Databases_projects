<?php
require "db.php";
session_start();
$eid = $_POST["takeExam"];
$eName = get_eName($eid);
$now = date('Y-m-d H:i:s');
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

echo' <form method=post action = login.php>';
echo' <input type="submit" value="logout" name="logout"> </form>  <br><br>';
 echo'</div> <br><br><br>';
 echo "<h1> Exam: ".$eName[0]." </h1>";
 ?>

<h2> Good Luck On Your Exam</h2>
<form action=examSubmit.php method=post>
<?php
 $isAvailable = examTimeChecker($eid);
 if($isAvailable == 2){
     echo '<h3> Unfortunately This Exam Is No Longer Available</h3>';
 }elseif($isAvailable == 1){ 
    echo '<h3> Not entirely sure how you got to this point, but the exam is not yet available to take.<h3>';

}else{
    $examQuestions = getExamQuestions($eid);
    $questionCount = sizeof($examQuestions);
    echo '<input type="hidden" name="eid" value="'.$eid.'">'; //passes examid
    echo '<input type="hidden" name="questionCount" value="'.$questionCount.'">';
    echo '<input type="hidden" name="startTime" value="'.$now.'">';
    foreach ($examQuestions as $question) {
        echo '<input type="hidden" name="qid_'.$question[1].'" value="'.$question[0].'">'; //qString is qid_number  then the value is the question id.
        echo '<h3>Question Number: '.$question[1].'</h3><br>' ;//qNumber
        echo '<p>'.$question[2].'</p>'; // qDescription
        $questionChoices = getQuestionChoices($question[0]); //question[0] is the qid

        foreach ($questionChoices as $choice) {
            echo'<input type="radio" name="'.$question[1].'" value="'.$choice[0].'">'; //question[1] is q number and choice[0] is identifier
            echo'<label>'.$choice[0].': '.$choice[1].'</label><br>';
        }
        echo '<br>';
    }
echo '<input type=submit name="submitExam" value="Submit Your Exam"></form>';

}
}//end the first else else statement
?>

<br><br>
<p> You can discard your progress without saving here</p>
<form method="post" action="studentHome.php"> <button class="backButton" type="submit" value="back">Back To Home</button></form>
</body>
</html>