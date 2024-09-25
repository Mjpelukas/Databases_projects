<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <title>Home</title>
    </head>
    <body>
<?php
require "db.php";
session_start();

 if (!isset($_SESSION["instructor_username"])) {
 header("LOCATION:login.php");
 }else {
    echo '<div class ="currentUserBar">';
    echo '<p align="right"> Welcome '. $_SESSION["instructor_username"].'</p>';
?>
 <form method=post action = login.php>
 <input type="submit" value="logout" name="logout"> </form> 
 </div> <br><br><br><br>
 <?php } ?>

 <p>Welcome To The Instructor Home Page</p>
    <h3>Exams And Your courses</h3>
    <table>
    <tr>
        <th>CourseID</th>
        <th>Course Title</th>
        <th>credit</th>
        <th>exam Name</th>
        <th>Open Time</th>
        <th>Close Time</th>
        <th>Total Points</th>
        </tr>
        <?php
        //gets the courses
        $exams = get_instructor_courses($_SESSION["instructor_username"]);
        foreach ($exams as $exam) {
            echo "<tr>";
            echo "<td>" . $exam[0] . "</td>";
            echo "<td>" . $exam[1] . "</td>";
            echo "<td>" . $exam[2] . "</td>";
            echo "<td>" . $exam[3] . "</td>";
            echo "<td>" . $exam[4] . "</td>";
            echo "<td>" . $exam[5] . "</td>";
            echo "<td>" . $exam[6] . "</td>";
            echo "</tr>";
        }?>
        </table>
        <form method= post action= instructorExamReview.php>
        <label>Course ID: </label> <input type=text name="cid">
        <label>Exam Name: </label> <input type=text name="eName"><br>
        <button type=submit name="examReview" value="score">Check Score</button>
        <button type=submit name="examReview" value="review">Review Exam</button>



    </body>
</html>
    
