/*
 * Jack Dedrick and Mathew Pelukas: CS3425 Course Project Phase One: autograder.sql
 */

# Auto Grader Trigger

Delimiter //
DROP Trigger if exists autograder;
Create Trigger autograder
after insert on student_answer
for each row
Begin
If NEW.correctChoice = NEW.answer
Then update exam_attempt set score = score + NEW.points 
	where attempt_id = NEW.attempt_id ;
End if;
End //
Delimiter ;

