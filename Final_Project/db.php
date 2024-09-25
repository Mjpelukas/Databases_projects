<?php
function connectDB()
{
 $config = parse_ini_file("db.ini");
 $dbh = new PDO($config['dsn'], $config['username'], $config['password']);
 $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 return $dbh;
}
function authenticate($type, $user, $passwd) {
    try {
       $dbh = connectDB();
       $statement = $dbh->prepare("SELECT count(*), intialSetup FROM :type ".
       "where sAccount = :username and password = sha2(:passwd,256) ");
       $statement->bindParam(":type", $type);
       $statement->bindParam(":username", $user);
       $statement->bindParam(":passwd", $passwd);
       $result = $statement->execute();
       $row=$statement->fetch();
       $dbh=null;
       if ($row[1]== 1){
        return 69;
       }
       return $row[0];
    }catch (PDOException $e) {
       print "Error!" . $e->getMessage() . "<br/>";
       die();
    }
   }
//return number of rows matching the given user and passwd.
function authenticateStudent($user, $passwd) {
    try {
       $dbh = connectDB();
       $statement = $dbh->prepare("SELECT count(*), initialSetup FROM student ".
       "where sAccount = :username and password = sha2(:passwd,256) ");
       $statement->bindParam(":username", $user);
       $statement->bindParam(":passwd", $passwd);
       $result = $statement->execute();
       $row=$statement->fetch();
       $dbh=null;
       if ($row[1]== 1){
        return 69;
       }
       return $row[0];
    }catch (PDOException $e) {
       print "Error!" . $e->getMessage() . "<br/>";
       die();
    }
   }
   
   //return number of rows matching the given user and passwd.
   function authenticateInstructor($user, $passwd) {
       try {
          $dbh = connectDB();
          $statement = $dbh->prepare("SELECT count(*), initialSetup FROM instructor ".
          "where iAccount = :username and password = sha2(:passwd,256) ");
          $statement->bindParam(":username", $user);
          $statement->bindParam(":passwd", $passwd);
          $result = $statement->execute();
          $row=$statement->fetch();
          $dbh=null;
          if ($row[1]== 1){
            return 69;
           }
          return $row[0];
       }catch (PDOException $e) {
          print "Error!" . $e->getMessage() . "<br/>";
          die();
       }
      }
      function get_instructor_courses($user){
        //connect to database
        //retrieve the data and display
        try {
            $dbh = connectDB();
            $statement = $dbh->prepare("SELECT teaches.cid, course.title, course.credit, exam.eName, exam.openTime, exam.closeTime, exam.totalPoints FROM teaches"
            ." LEFT OUTER JOIN course ON teaches.cid = course.id LEFT OUTER JOIN exam on teaches.cid = exam.cid"
            ." WHERE iAccount = :username");
            $statement->bindParam(":username", $user);
            $statement->execute();
            return $statement->fetchAll();
            $dbh = null;
        } catch (PDOException $e) {
            print "Error!" . $e->getMessage() . "<br/>";
            die();
        }
    }
    function get_instructor_exam_info($eid){
            //connect to database
            //retrieve the data and display
            try {
                $dbh = connectDB();
                $statement = $dbh->prepare("Select count(*) as noAttempts, max(acScore) as maxScore, min(acScore) as minScore, AVG(acScore) as avgScore From"
                . "(Select max(score) as acScore from exam_attempt WHERE exam_id = :eid group by sAccount) as acScores");
                $statement->bindParam(":eid", $eid);
                $statement->execute();
                return $statement->fetch();
                $dbh = null;
            } catch (PDOException $e) {
                print "Error!" . $e->getMessage() . "<br/>";
                die();
            }
}

function get_instructor_exam_students($eid){
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("Select sAccount from exam_attempt where exam_id = :eid group by sAccount");

        $statement->bindParam(":eid", $eid);
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_instructor_exam_students_attempt($eid, $sid){
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("Select student.name, exam_attempt.startTime, exam_attempt.endTime, actScores.acScore FROM"
        ." (Select attempt_id, max(score) as acScore from exam_attempt WHERE exam_id = :eid AND sAccount = :sid group by attempt_id order by max(score) DESC, attempt_id DESC LIMIT 1) as actScores"
        ." JOIN exam_attempt on actScores.attempt_id = exam_attempt.attempt_id"
        ." join student on exam_attempt.sAccount = student.sAccount");
        $statement->bindParam(":eid", $eid);
        $statement->bindParam(":sid", $sid);
        $statement->execute();
        return $statement->fetch();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function get_student_courses($user){
    //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT registers.cid, course.title, course.credit, instructor.name FROM registers"
        ." LEFT OUTER JOIN course ON registers.cid = course.id LEFT OUTER JOIN instructor on registers.teacherID = instructor.iAccount"
        ." WHERE sAccount = :username");
        $statement->bindParam(":username", $user);
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

 function get_student_non_course($user){
  //connect to database
    //retrieve the data and display
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT tjoin.cid, tjoin.course_name, tjoin.credits, tjoin.teacherid FROM "
        ."(SELECT teaches.cid as cid, course.title as course_name, course.credit as credits, teaches.iAccount as teacherid FROM teaches join course ON teaches.cid = course.id) tjoin" 
        ." LEFT JOIN(SELECT registers.cid as cid  FROM registers WHERE sAccount = :username) hasCourse on hasCourse.cid= tjoin.cid WHERE hasCourse.cid is NULL");
        $statement->bindParam(":username", $user);
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
 }


function get_student_exams($user){
    //connect to database
    //retrieve the data and display
    //create table exam( eName varchar(30) NOT NULL, cid varchar(10) NOT NULL, creationDate Date, openDate Date, closeDate Date, totalPoints int,
    //create table exam_attempt( attempt_id int AUTO_INCREMENT, eName varchar(30) NOT NULL, cid varchar(10) NOT NULL, sAccount varchar(20) NOT NULL, startTime TIME, endTime TIME, possibleScore int, score int,
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("Select * From "
         ."(SELECT ejoin.eid, ejoin.cid, ejoin.eName, ejoin.openTime, ejoin.closeTime, eattempt.startTime, eattempt.endTime, eattempt.score, eattempt.attempt_id FROM "
        ."(SELECT exam.eid eid, exam.cid cid, exam.eName as eName, exam.openTime as openTime, exam.closeTime as closeTime, registers.sAccount as sAccount from exam join registers on exam.cid = registers.cid where sAccount = :user) ejoin"
        ." LEFT OUTER JOIN exam_attempt eattempt ON eattempt.exam_id = ejoin.eid AND eattempt.sAccount = :user2) please");

        $statement->bindParam(":user", $user);
        $statement->bindParam(":user2", $user);
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
         die();
    }    
}
function get_student_exam_specific($user, $eid, $aid){
    //connect to database
    //retrieve the data and display
    //create table exam( eName varchar(30) NOT NULL, cid varchar(10) NOT NULL, creationDate Date, openDate Date, closeDate Date, totalPoints int,
    //create table exam_attempt( attempt_id int AUTO_INCREMENT, eName varchar(30) NOT NULL, cid varchar(10) NOT NULL, sAccount varchar(20) NOT NULL, startTime TIME, endTime TIME, possibleScore int, score int,
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT ejoin.eid, ejoin.eName, eattempt.startTime, eattempt.endTime, eattempt.score FROM "
        ."(SELECT exam.eid eid, exam.cid cid, exam.eName as eName, exam.openTime as openTime, exam.closeTime as closeTime, registers.sAccount from exam join registers on exam.cid = registers.cid where Saccount = :user) ejoin"
        ." LEFT OUTER JOIN exam_attempt eattempt ON eattempt.exam_id = ejoin.eid WHERE ejoin.eid = :eid AND eattempt.sAccount = :user2 AND attempt_id = :aid");

        $statement->bindParam(":user", $user);
        $statement->bindParam(":eid", $eid);
        $statement->bindParam(":user2", $user);
        $statement->bindParam(":aid", $aid);
        $statement->execute();
        return $statement->fetch();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
         die();
    }    
}
//TODO change back to course id and exam name if not allowed to be this way
function examTimeChecker($eid){
    try {
        $dbh = connectDB();
        $sqlstring = "select openTime, closeTime from exam where eid =:examID";
        $statement = $dbh->prepare($sqlstring);
        $statement->bindParam(":examID", $eid);
        $statement->execute();
        $x = $statement->fetch();
        $start_time = strtotime($x[0]);
        $end_time = strtotime($x[1]);
        $now = strtotime(date('Y-m-d H:i:s'));
        if ($now > $end_time) {
            return 2;
        } else if ($now < $start_time) {
            return 1;
        } else {
            return 0;
        }

    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function get_eid($eName, $cid){
    //connect to database
      //retrieve the data and display
      try {
          $dbh = connectDB();
          $statement = $dbh->prepare("select eid, eName from exam where eName = :eName and cid = :cid");
          $statement->bindParam(":eName", $eName);
          $statement->bindParam(":cid", $cid);
          $statement->execute();
          return $statement->fetch();
          $dbh = null;
      } catch (PDOException $e) {
          print "Error!" . $e->getMessage() . "<br/>";
          die();
      }
   }
   function get_eName($eid){
    //connect to database
      //retrieve the data and display
      try {
          $dbh = connectDB();
          $statement = $dbh->prepare("select eName from exam where eid = :eid");
          $statement->bindParam(":eid", $eid);
          $statement->execute();
          return $statement->fetch();
          $dbh = null;
      } catch (PDOException $e) {
          print "Error!" . $e->getMessage() . "<br/>";
          die();
      }
   }
          //gets exam questions for take exam
function getExamQuestions($eid){
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT qid, qNumber, qDescription, correctChoice FROM question WHERE eid = :eid ORDER BY qNumber ASC");
        $statement->bindParam(":eid", $eid); //eid = examid
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
 }
 //create table question( qid int AUTO_INCREMENT,  eid int , qNumber int, qDescription, correctChoice,  NOT NULL, points int NOT NULL,
 function getQuestionChoices($qid){
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT identifier, cDescription FROM choice WHERE qid = :qid ORDER BY identifier ASC");
        $statement->bindParam(":qid", $qid); //qid = questionid
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

function getReviewChoices($qid){
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT identifier, cDescription FROM choice WHERE qid = :qid ORDER BY identifier ASC");
        $statement->bindParam(":qid", $qid); //qid = questionid
        $statement->execute();
        return $statement->fetchAll();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}

//get student answer
function getStudentAnswer($qid, $aid){
    try {
        $dbh = connectDB();
        $statement = $dbh->prepare("SELECT answer, correctChoice, points FROM student_answer WHERE qid = :qid AND attempt_id = :aid ");
        $statement->bindParam(":qid", $qid); //qid = questionid
        $statement->bindParam(":aid", $aid); //qid = questionid
        $statement->execute();
        return $statement->fetch();
        $dbh = null;
    } catch (PDOException $e) {
        print "Error!" . $e->getMessage() . "<br/>";
        die();
    }
}
function submitExam($examAttempt, $user)
{
    $now = date('Y-m-d H:i:s');
    try {
        $dbh = connectDB();
        $dbh->beginTransaction();
        //start getting the inital things
        $statement = $dbh->prepare("INSERT INTO exam_attempt(exam_id, sAccount , startTime, endTime, score) Values(:eid, :user, :startTime, :endTime, 0)");
        $statement->bindParam(":eid",$examAttempt["eid"]);
        $statement->bindParam(":user",$user);
        $statement->bindParam(":startTime",$examAttempt["startTime"]);
        $statement->bindParam(":endTime",$now);
        $result = $statement->execute();
        $aid = $dbh->lastInsertID("attempt_id") ;
        for ($i = 1; $i <= $examAttempt["questionCount"]; $i++){
            $qString = "qid_".strval($i);
            $statement = $dbh->prepare("SELECT correctChoice, points FROM question WHERE qid = :qid");
            $statement->bindParam(":qid", $examAttempt[$qString]);
            $result = $statement->execute();
            $row = $statement->fetch();

            $statement = $dbh->prepare("INSERT INTO student_answer VALUES(:aid, :qid, :correct, :points, :answer)");
            $statement->bindParam(":aid", $aid);
            $statement->bindParam(":qid", $examAttempt[$qString]);
            $statement->bindParam(":correct", $row[0]);
            $statement->bindParam(":points", $row[1]);
            $statement->bindParam(":answer", $examAttempt[strval($i)]); //should be the answer
            $result = $statement->execute();
    
        }
        echo '<h3>Your Exam was Successfully Submitted</h3>';
        $dbh->commit();
    } catch (Exception $e) {
        echo "<h1> Submission failed, all questions must be answered</h1>";
        $dbh->rollBack();
    }
    $dbh=null;
    return;
}

//SELECT ejoin.cid, ejoin.eName, ejoin.openDate, ejoin.closeDate, eattempt.startTime, eattempt.endTime, eattempt.score FROM (SELECT exam.cid cid, exam.eName as eName, exam.openDate as openDate, exam.closeDate as closeDate, registers.Saccount from exam join registers on exam.cid = registers.cid where Saccount = S1) ejoin LEFT OUTER JOIN exam_attempt eattempt ON eattempt.eName = ejoin.eName AND eattempt.cid = ejoin.cid;

//SELECT ejoin.eid, ejoin.eName, eattempt.startTime, eattempt.endTime, eattempt.score FROM (SELECT exam.eid eid, exam.cid cid, exam.eName as eName, exam.openTime as openTime, exam.closeTime as closeTime, registers.Saccount from exam join registers on exam.cid = registers.cid where Saccount = "S3") ejoin LEFT OUTER JOIN exam_attempt eattempt ON eattempt.exam_id = ejoin.eid WHERE ejoin.eid = 2);



/*Select student.name, exam_attempt.startTime, exam_attempt.endTime, actScores.acScore FROM
        (Select attempt_id, max(score) as acScore from exam_attempt WHERE exam_id = 2 AND sAccount = "S2" group by attempt_id order by max(score) DESC, attempt_id DESC LIMIT 1) as actScores
        JOIN exam_attempt on actScores.attempt_id = exam_attempt.attempt_id
        join student on exam_attempt.sAccount = student.sAccount;


*/
/*Select student.name, exam_attempt.startTime, exam_attempt.endTime, actScores.acScore FROM
 (Select attempt_id, max(score) as acScore from exam_attempt WHERE exam_id = 2 AND sAccount = "S2" group by attempt_id order by max(score) DESC, attempt_id DESC LIMIT 1) as actScores
 JOIN exam_attempt on actScores.attempt_id = exam_attempt.attempt_id
 join student on exam_attempt.sAccount = student.sAccount;
*/





        ?>