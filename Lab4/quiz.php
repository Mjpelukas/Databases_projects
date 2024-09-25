
<html>
    <body>
<form action=quiz.php method=post>
    <span>Q1: The pace of this course</span><br>
    <input type="radio" id="Q1a" name="Q1" value="A">
    <label for="Q1a">A: is too slow</label><br>
    <input type="radio" id="Q1b" name="Q1" value="B">
    <label for="Q1b">B: is just right</label><br>
    <input type="radio" id="Q1c" name="Q1" value="C">
    <label for="Q1c">C: is too fast</label><br>
    <input type="radio" id="Q1d" name="Q1" value="D">
    <label for="Q1d">D: I don't know</label><br>

    <br><span>Q2: The feedback from the homework assignment grading</span> <br>
    <input type="radio" id="Q2a" name="Q2" value="A">
    <label for="Q2a">A: too few </label><br>
    <input type="radio" id="Q1b" name="Q2" value="B">
    <label for="Q2b">B: sufficient</label><br>
    <input type="radio" id="Q2c" name="Q2" value="C">
    <label for="Q2c">C: I don't know</label><br>

    <br><span> Q3: Any thing you like about the teaching of this course?</span><br>
    <textarea name="Q3"></textarea><br>

    <br><input type=submit name="submit" value="Submit">
</form>

<?php
if (isset($_POST["submit"])) {
 echo "Your answers are: <br>";
 foreach (array_keys($_POST) as $x) {
 if ($x!='submit')
 echo $x .":". $_POST[$x]. "<br>";
 }
 return;
}
?>


</body>
</html>