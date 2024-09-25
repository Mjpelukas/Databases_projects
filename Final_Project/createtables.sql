/*
 * Jack Dedrick and Mathew Pelukas: CS3425 Course Project Phase One: createTables.sql
 */
#creates the tables

#DROP TABLEs tables first
DROP TABLE IF EXISTS student_answer;
DROP TABLE IF EXISTS exam_attempt;
DROP TABLE IF EXISTS choice;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS exam;
DROP TABLE IF EXISTS registers;
DROP TABLE IF EXISTS teaches;
DROP TABLE IF EXISTS instructor;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS course;




#creates tables
create table instructor( iAccount varchar(20) primary key, name varchar(20) NOT NULL , password char(64) NOT NULL,  initialSetup tinyint(0) default true );

create table course( id varchar(10) primary key, title varchar(20), credit numeric(3,2));

create table student( sAccount varchar(20) primary key, name varchar(20) NOT NULL , password char(64) NOT NULL, initialSetup tinyint(0) default true);


                        
create table teaches(iAccount varchar(20) not NULL, cid varchar(10) NOT NULL,
	primary key(iAccount, cid),
	constraint foreign key FK_Teahces_Instructor (iAccount) references instructor(iAccount) ON DELETE CASCADE ON UPDATE CASCADE,
	constraint foreign key FK_Teaches_Course (cid) references course(id) ON DELETE CASCADE ON UPDATE CASCADE);
    
create table registers(sAccount varchar(20) NOT NULL, cid varchar(10) NOT NULL, teacherID varchar(20) not NULL, 
	primary key(sAccount, cid, TeacherID),
	constraint foreign key FK_Registers_Student (sAccount) references student(sAccount) ON DELETE CASCADE ON UPDATE CASCADE,
	constraint foreign key FK_Registers_Course (cid) references teaches(cid) ON DELETE CASCADE ON UPDATE CASCADE,
    constraint foreign key FK_Registers_Instructor (teacherID) references teaches(iAccount) ON DELETE CASCADE ON UPDATE CASCADE);
create table exam( eid int AUTO_INCREMENT, eName varchar(30) NOT NULL, cid varchar(10) NOT NULL, creationTime DATETIME, openTime DATETIME, closeTime DATETIME, totalPoints int,
	PRIMARY KEY (eid),
	constraint foreign key FK_ExamCourse (cid) references course(id) ON DELETE CASCADE ON UPDATE CASCADE);
                    
create table question( qid int AUTO_INCREMENT,  eid int not null, qNumber int, qDescription varchar(500), correctChoice varchar(6) NOT NULL, points int NOT NULL,
	primary key(qid),
	constraint foreign key FK_Exam_Question (eid) references exam(eid) ON DELETE CASCADE ON UPDATE CASCADE);
                        
create table choice( identifier varchar(6) not null,  qid int Not NULL, cDescription varchar(500),
	primary key(identifier, qid),
	constraint foreign key FK_Exam_Choice (qid) references question(qid) ON DELETE CASCADE ON UPDATE CASCADE);

#Does not cascade exam so that it can be used for future grading
create table exam_attempt( attempt_id int AUTO_INCREMENT, exam_id int not null, sAccount varchar(20) NOT NULL, startTime DATETIME, endTime DATETIME, score int default 0,
	primary key (attempt_id),
	constraint foreign key FK_Exam_Attempt_Exam (exam_id) references exam(eid),
	constraint foreign key FK_Exam_Attempt_Student (sAccount) references student(sAccount) ON DELETE CASCADE ON UPDATE CASCADE); 
#Does not cascade question so that it can be used for future grading
create table student_answer(attempt_id int not null, qid int not null,  correctChoice varchar(6) NOT NULL, points int NOT NULL, answer varchar(6) not null,
	primary key(attempt_id, qid),
	constraint foreign key FK_Exam_Attempt_Attempt (attempt_id) references exam_attempt(attempt_id)ON DELETE CASCADE ON UPDATE CASCADE,
	constraint foreign key FK_Exam_Attempt_Question (qid) references question(qid));
    
grant select, insert, update, delete on instructor to 'cs3425gr'@'%';
grant select, insert, update, delete on instructor to 'cs3425gr'@'localhost';

grant select, insert, update, delete on course to 'cs3425gr'@'%';
grant select, insert, update, delete on course to 'cs3425gr'@'localhost';

grant select, insert, update, delete on student to 'cs3425gr'@'%';
grant select, insert, update, delete on student to 'cs3425gr'@'localhost';

grant select, insert, update, delete on registers to 'cs3425gr'@'%';
grant select, insert, update, delete on registers to 'cs3425gr'@'localhost';

grant select, insert, update, delete on exam to 'cs3425gr'@'%';
grant select, insert, update, delete on exam to 'cs3425gr'@'localhost';

grant select, insert, update, delete on teaches to 'cs3425gr'@'%';
grant select, insert, update, delete on teaches to 'cs3425gr'@'localhost';

grant select, insert, update, delete on exam_attempt to 'cs3425gr'@'%';
grant select, insert, update, delete on exam_attempt to 'cs3425gr'@'localhost';

grant select, insert, update, delete on student_answer to 'cs3425gr'@'%';
grant select, insert, update, delete on student_answer to 'cs3425gr'@'localhost';

grant select, insert, update, delete on question to 'cs3425gr'@'%';
grant select, insert, update, delete on question to 'cs3425gr'@'localhost';

grant select, insert, update, delete on choice to 'cs3425gr'@'%';
grant select, insert, update, delete on choice to 'cs3425gr'@'localhost';
