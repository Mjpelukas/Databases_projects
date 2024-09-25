/*
 * Jack Dedrick and Mathew Pelukas: CS3425 Course Project Phase One: insertData.sql
 */

# Delete All Existing Data
	Delete From student_answer;
	Delete From choice;
	Delete From exam_attempt;
	Delete From question; 
	Delete From exam;
	Delete From teaches;
	Delete From registers;
	Delete From course;
  	Delete From instructor;
	Delete From student;





# Call Procedure Statements
    # Create Instructors: Bob and Joe
   	 # Bob
   	 Call create_instructor("T1", "Bob");
   	 # Joe
   	 Call create_instructor("T2", "Joe");

    # Create Students: Jack, John, Josh, Matthew, and Mark
   	 # Jack
   	 Call create_student("S1", "Jack");
   	 # John
   	 Call create_student("S2", "John");
   	 # Josh
   	 Call create_student("S3", "Josh");
   	 # Mathew
   	 Call create_student("S4", "Matthew");
   	 # Mark
   	 Call create_student("S5", "Mark");

    # Create Courses: Basic Math and Algebra
   	 # Basic Math
   	 Call create_course("C1", "Basic Math", 3);
   	 # Algebra
   	 Call create_course("C2", "Algebra", 3);

    # Assign Teachers: Bob to Basic Math and Joe to Algebra
   	 # Bob to Basic Math
   	 Call assign_teacher("T1", "C1");
   	 # Joe to Algebra
   	 Call assign_teacher("T2", "C2");



# Inset Other Data: Registration Data, Exams, Student Answers
    # Registration Data (sAccount, cid)
   	 Insert Into registers Values ("S1", "C1", "T1");
   	 Insert Into registers Values ("S2", "C1", "T1");
   	 Insert Into registers Values ("S3", "C1", "T1");
   	 Insert Into registers Values ("S4", "C2", "T2");
   	 Insert Into registers Values ("S5", "C2", "T2");
   	 Insert Into registers Values ("S1", "C2", "T2");
	 Insert Into registers Values ("S2", "C2", "T2");
	 Insert Into registers Values ("S3", "C2", "T2");

    # Exams
   	 #Basic Math Exam 1 (A) = CLOSED
   		Insert Into exam Values (1,"Basic Math Exam 1", "C1", "2020-10-1 00:00:00", "2020-10-15 00:00:00", "2020-10-25 23:59:59", 20);
   	 #Basic Algebra Exam 1 (B) = OPEN
   		Insert Into exam Values (2, "Algebra Exam 1", "C2", "2022-10-1 00:00:00", "2022-11-15 00:00:00", "2022-12-25 23:59:59", 20);

    #create table exam_attempt( attempt_id, exam_id int , sAccount , startTime TIME, endTime TIME, possibleScore int, score int,
   	Insert Into exam_attempt Values ( 55, 1, "S2", "2020-10-16 09:00:00", "2020-12-16 10:15:00",  0);
   	Insert Into exam_attempt Values ( 56, "1", "S3", "2020-10-22 8:30:00", "2020-10-22 09:15:00",  0);
         
	Insert Into exam_attempt Values ( 57,2 , "S2", "2022-11-19 09:00:00", "2022-11-19 10:15:00",  0);
   	Insert Into exam_attempt Values ( 58, 2, "S3", "2022-12-03 8:30:00", "2022-12-03 09:15:00",  0);

# question( qid int AUTO_INCREMENT,  eid int not null, qNumber int, qDescription varchar(500), correctChoice varchar(6) NOT NULL, points int NOT NULL,
#Basic Math EXAM 1 Questions
	Insert into question Values (1, 1, 1, "1 + 1 + ?", "C", 5);
		Insert into choice Values ("A", 1, "4");
		Insert into choice Values ("B", 1, "3");
		Insert Into choice Values ("C", 1, "2");
	Insert into question values (2, 1, 2, "2 + 3 = ?","B", 5);
		Insert into choice Values ("A", 2, "7");
		Insert into choice Values ("B", 2, "5");
		Insert Into choice Values ("C", 2, "6");
		Insert Into choice Values ("D", 2, "8");
	Insert into question values (3, 1, 3, "5 + 7 = ?", "D", 5);
		Insert into choice Values ("A", 3, "10");
		Insert Into choice Values ("B", 3, "9");
		Insert Into choice Values ("C", 3, "13");
		Insert into choice Values ("D", 3, "12");
	Insert into question Values (4, 1, 4, "10 + 17 = ?", "A", 5);
		Insert into choice Values("A", 4, "27");
		Insert into choice Values("B", 4, "17");
		Insert into choice Values("C", 4, "28");
	
#Algebra EXAM 1 Questions
	Insert into question values (5, 2, 1,"5 + X = 10" , "B", 5);
		Insert into choice Values ("A", 5, "2");
		Insert into choice Values ("B", 5, "5");
		Insert Into choice Values ("C", 5, "4");
		
	Insert into question values (6, 2 , 2, "6 + X = 11" , "D", 5);
		Insert into choice Values ("A", 6,"8");
		Insert into choice Values ("B", 6,"6");
		Insert Into choice Values ("C", 6,"2");
		Insert Into choice Values ("D", 6,"5");
	
	Insert into question values (7, 2, 3, "7 + X = 12" , "B", 5);
		Insert into choice Values ("A", 7, "6");
		Insert into choice Values ("B", 7, "5");
		Insert Into choice Values ("C", 7, "4");
		
	Insert into question values (8, 2, 4, "8 + X = 13", "D", 5);
		Insert into choice Values ("A", 8, "8");
		Insert into choice Values ("B", 8, "6");
		Insert Into choice Values ("C", 8, "4");
		Insert Into choice Values ("D", 8, "5");





    # Student Answers student_answer( attempt_id, qid, correctChoice, points, answer)
	Insert Into student_answer Values(55, 1, "C", 5, "C");
	Insert Into student_answer Values(55, 2, "B", 5, "B");
	Insert Into student_answer Values(55, 3, "D", 5, "D");
	Insert Into student_answer Values(55, 4, "A", 5, "A");

	Insert Into student_answer Values(56, 1, "C", 5, "C");
	Insert Into student_answer Values(56, 2, "B", 5, "B");
	Insert Into student_answer Values(56, 3, "D", 5, "C");
	Insert Into student_answer Values(56, 4, "A", 5, "D");

	Insert Into student_answer Values(57, 5, "B", 5, "B");
	Insert Into student_answer Values(57, 6, "D", 5, "D");
	Insert Into student_answer Values(57, 7, "B", 5, "B");
	Insert Into student_answer Values(57, 8, "D", 5, "D");

	Insert Into student_answer Values(58, 5, "B", 5, "B");
	Insert Into student_answer Values(58, 6, "D", 5, "B");
	Insert Into student_answer Values(58, 7, "B", 5, "B");
	Insert Into student_answer Values(58, 8, "D", 5, "D");
   	 
# Select Statements to Show All Data in All Tables
    Select "Instructor";
   	 Select * From instructor;
    Select "Course";
   	 Select * From course;
    Select "Student";
   	 Select * From student;

    Select "Registers";
   	 Select * From registers;
    Select "Exam";
   	 Select * From exam;
    Select "Teaches";
   	 Select * From teaches;
    Select "Exam_Attempt";
   	 Select * From exam_attempt;
    Select "Student_Answer";
   	 Select * From student_answer;
    Select "Question";
   	 Select * From question;
    Select "Choice";
   	 Select * From choice;



