
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Home</title>
    </head>
<?php
require "db.php";
session_start();

?>
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



 <p>Welcome To Your Home Page Student</p>

 <h3>Your Enrolled Courses</h3>

<table>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Credits</th>
    <th>Teacher</th>
    </tr>
    <?php
    //gets the courses
    $courses = get_student_courses($_SESSION["student_username"]);
    foreach ($courses as $row) {
    echo "<tr>";
    echo "<td>" . $row[0] . "</td>";
    echo "<td>" . $row[1] . "</td>";
    echo "<td>" . $row[2] . "</td>";
    echo "<td>" . $row[3] . "</td>";
    echo "</tr>";
    }
    echo "</table>";
    ?>
    <h3>Exams For Your Courses</h3>
    <table>
    <tr>
        <th>CourseID</th>
        <th>Exam Name</th>
        <th>Open Date</th>
        <th>Close Date</th>
        <th>Start Time</th>
        <th>End Time</th>
        <th>Score</th>
        </tr>
        <?php
        //gets the courses
        $exams = get_student_exams($_SESSION["student_username"]);
        foreach ($exams as $exam) {
            echo "<tr>";
            echo "<td>" . $exam[1] . "</td>";
            echo "<td>" . $exam[2] . "</td>";
            echo "<td>" . $exam[3] . "</td>";
            echo "<td>" . $exam[4] . "</td>";
            echo "<td>" . $exam[5] . "</td>";
            echo "<td>" . $exam[6] . "</td>";
            echo "<td>" . $exam[7] . "</td>";
            echo "</tr>";
            echo '<tr class = "exam_Options"><td>';
            //Makes exams unavailable if 

            $isAvailable = examTimeChecker($exam[0]);
            if($isAvailable == 2){
                echo '<span class="sorry"> Sorry This exam can no longer be taken<span>';
            }elseif($isAvailable == 1){
                echo "This exam isn't available yet";
            }else{
                echo '<form method="post" action="takeExam.php"><button type="submit" name="takeExam" value="'.$exam[0].'">Take This Exam</button></form>';
            } 
            echo '</td><td><form method="post" action="studentScoreBreakdown.php">';
            echo '<input type="hidden" name="eName" value="'.$exam[2].'">'; //passes ename
            echo '<input type="hidden" name="aid" value="'.$exam[8].'">'; //passes examid
            echo '<button type="submit" name="showScore" value="'.$exam[0].'">Show Your Score Breakdown</button></form>';
            echo "</tr>";
            echo "</form>";
        } //ends for each loop
        echo "</table>";

        $notIn = get_student_non_course($_SESSION["student_username"])
        ?>
        <h3>Courses you aren't in <h3>
        <table>
        <tr>
        <th>CourseID</th>
        <th>Course Title</th>
        <th>Credits</th>
        <th>Teacher ID</th>
        </tr>
        <?php
        foreach ($notIn as $nots) {
            echo "<tr>";
            echo "<td>" . $nots[0] . "</td>";
            echo "<td>" . $nots[1] . "</td>";
            echo "<td>" . $nots[2] . "</td>";
            echo "<td>" . $nots[3] . "</td>";
            echo "</tr>";
        }
        echo'</table>';
}//ends the else statement
 
?>


<p>Here you can register for a new course</p>

    <form method= post action= registerCourse.php>
    <label>Course ID: </label> <input type=text name="cid">
    <button type=submit name="register" value="register">Register New Courses</button>

</form>

</body>
</html>
