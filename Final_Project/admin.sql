/*
 * Jack Dedrick and Mathew Pelukas: CS3425 Course Project Phase One: admin.sql
 */


#create instructor
delimiter // 
 
drop procedure if exists create_instructor // 
create procedure create_instructor(iAccount char(20), name char(20)) 
begin  
insert into instructor values (iAccount, name, sha2(concat(iAccount, '_temp'), 256), 1);  
end // 



#create student
drop procedure if exists create_student // 
create procedure create_student(sAccount char(20), name char(20)) 
begin  
insert into student values(sAccount, name, sha2(concat(sAccount, '_temp'), 256), 1);  
end // 


#create course

drop procedure if exists create_course // 
create procedure create_course(id varchar(10), title varchar(20), credit numeric(3,2)) 
begin 
insert into course values (id, title, credit);  
end // 

#assign teacher
drop procedure if exists assign_teacher // 
create procedure assign_teacher(iAccount varchar(20), cid varchar(10)) 
begin 
insert into teaches values (iAccount, cid);  
end // 

#create exam
drop procedure if exists create_exam //
create procedure create_exam(eName varchar(30), cid varchar(10), openTime DATETIME, closeTime DATETIME)
begin
insert into exam values (NULL, eName, cid, GETDATE(), openTime, closeTime, 0);
end //

#create exam question
drop procedure if exists create_exam_question //
create procedure create_exam_question(examid int, qNumber int, qDescription varchar(500), correctChoice varchar(6), pts int)
begin
insert into question values (NULL, eid, qNumber, qDescription, correctChoice, pts);
UPDATE exam SET points = points + pts WHERE eid = examid;
end //

#create exam choice
drop procedure if exists create_exam_question_choice //
create procedure create_exam_question_choice(identifier varchar(6), qid int, cDescription varchar(500))
begin
insert into choice values (identifier, qid, cDescription);
end //
	
delimiter ; 

