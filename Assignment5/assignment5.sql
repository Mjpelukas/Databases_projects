/*  
 * CS3424 Assignment5  
 * Author: Matthew Pelukas
 * Author: Jack Dedrick
 * Authors: Matthew Pelukas and Jack Dedrick
 * #I really wasn't sure how to format this
  */ 
  
select "1.";
  #1 List all departments that have more than 100 students. Display the output (department name and total student number) by descending order of students.  
  select dept_name, count(name) total_student from student 
  group by dept_name having total_student > 100 
  order by total_student DESC;

select "2."; 
  #2 List the bottom 5 departments with the smallest number of students.  Display the output (department name and total student number) by ascending order of students. Please note Some department may have 0 students.
select dept.dept_name dept_name, count(std.name) as total_student 
from department dept
left outer join student std 
on dept.dept_name = std.dept_name
 group by dept.dept_name 
order by total_student ASC;
   
  SELECT "3.";
/*
 * 3. List the dept name, number of instructors, number of courses number
 * of students for each department. Display the output by descending
 * order of number of student, then descending order number of
 * instructor, and then descending order of number of course.
 */

SELECT * FROM (
	SELECT d.dept_name, COUNT(s.ID) as 'total_student' 
	FROM department d 
	LEFT JOIN student s ON d.dept_name = s.dept_name
	GROUP BY d.dept_name) AS first
NATURAL JOIN (
	SELECT d.dept_name, COUNT(i.ID) as 'total_instructor' 
    FROM department d 
    LEFT JOIN instructor i ON d.dept_name = i.dept_name
	GROUP BY d.dept_name) AS second
NATURAL JOIN (
SELECT d.dept_name, COUNT(c.course_id) as 'total_course'
	FROM department d 
	LEFT JOIN course c ON d.dept_name = c.dept_name
	GROUP BY d.dept_name) AS third
ORDER BY total_student DESC, total_instructor DESC, total_course DESC;


select "4.";
/*
4. List the course id, title, and how many sections that it has been offered in 
history in descending order of sections, then ascending order of course title. 
 */
select 
	crs.Course_id course_id ,crs.Title title,
    count(std.sec_id) times
from 
	course crs left outer join section std 
on 
	crs.course_id=std.course_id 
group by crs. course_id
order by count(std.sec_id) DESC, crs.Title ASC;


select "5.";
# 5. List the total number of sections is being offered by each department in Spring 2008 ordered by department name.   
select dept.dept_name dept_name, count(spr.sec_id) total_section 
from department dept left outer join course crs on dept.dept_name = crs.dept_name 
left outer join 
	(select * from section sec
    where sec.semester = "Spring" AND sec.year = 2008) spr 
    on crs.course_id = spr.course_id
group by dept_name
order by dept.dept_name;

SELECT "6.";
/*
 * 6. Generate the transcript for student whose id is 1000 chronologically. For example: Spring 2002 is before Fall 2002.
 */
SELECT t.course_id, c.title, c.credits, IFNULL(t.grade, '') as 'grade', t.semester, t.year
 FROM takes t 
 LEFT JOIN course c ON t.course_id = c.course_id 
 WHERE t.ID = 1000 
 ORDER BY year ASC,
 semester DESC;



select "7.";
/*
 7. List the total teaching credit hours for instructors who taught in 
Spring 2008 in descending. You need to include all instructors in the output, even the 
ones who didn’t teach. Order the output by department , then total_credit_hours in 
descending order, then instructor name. 
*/   
select inst.dept_name dept_name, inst.id id, inst.name name,
 coalesce(SUM(sprtea.credits * sprtea.total_students), 0) as total_credit_hours
from instructor inst left outer join
	(select teaches.*, total_students, credits from teaches
	left outer join (select course.course_id, sec_id, total_students, credits from
		(select course_id, sec_id, semester, year, count(*) total_students from takes 
			where year = "2008" and semester= "Spring"
			group by course_id, sec_id) std_num
		inner join course on std_num.course_id = course.course_id) crs_info   
	on teaches.course_id= crs_info.course_id and teaches.year = "2008" and teaches.semester= "Spring"
  ) sprtea on inst.id = sprtea.id
group by inst.id
order by dept_name ASC,
total_credit_hours desc,
name desc;


select "8.";
/* 
8. Generate the total number of students that each instructor taught.  Sort 
by department, then by the student number in descending order, then by instructor 
name ascending.  If an instructor taught the same students multiple times, count it as 
one. Make sure to include the instructor that taught 0 student in the output.
 */

select inst.dept_name dept_name, inst.id id, inst.name name, coalesce(total_students, 0) as stu_number 
from instructor inst 
left outer join
	(select teaches.id, count(distinct takes.id) as total_students from teaches 
    left outer join takes on teaches.course_id = takes.course_id and teaches.sec_id = takes.sec_id
    and teaches.semester = takes.semester and teaches.year = takes.year
group by teaches.id) std_num on inst.id = std_num.id
order by dept_name, stu_number desc;



SELECT "9.";
/*
 * 9. List Math department's top 20 students based on GPAs. List student id, name, sum
 * of credits, overall GPA, and order the output by GPA from highest to lowest.
 */


SELECT student.id, student.name, SUM(course.credits) as 'total_credits', ROUND((SUM(gradepoint.point * course.credits)/sum(CASE WHEN takes.grade IS NOT NULL THEN course.credits END)), 2) AS 'GPA' 
FROM takes JOIN student ON takes.ID = student.ID JOIN gradepoint ON takes.grade = gradepoint.grade JOIN course ON takes.course_id = course.course_id 
WHERE student.dept_name = 'Math' 
GROUP BY student.ID 
ORDER BY GPA 
DESC LIMIT 20;


select "10.";
/*
10. List section information for classes offered in 2008 Spring, 
include dept_name, course_id, section_id, credits, course_title, capacity, actual, 
remaining, instructor name, building and room_number. Order the output by 
department, then course_id, sec_id.  
*/
select crs.dept_name as Dept, sprsec.course_id as Crse, sprsec.sec_id as Sec, credits as Cred, title as Title,
capacity as Cap, coalesce(total_students,0) as act, (capacity - coalesce(total_students, 0 )) as Rem,
coalesce(inst.name, "--TBD--") as Instructor, building, room_number
from (select distinct * from section where year = "2008" and semester = "Spring") sprsec
	inner join course crs on sprsec.course_id = crs.course_id
	left outer join (select course_id, sec_id, count(*) as total_students from takes where year = 2008 and semester = 'Spring' 
    group by course_id, sec_id) std_num
	on sprsec.course_id = std_num.course_id and sprsec.sec_id = std_num.sec_id
	left outer join teaches on sprsec.course_id = teaches.course_id and teaches.year = "2008" and teaches.semester= "Spring"
	left outer join instructor inst on inst.id = teaches.id  
order by crs.dept_name ASC,
sprsec.course_id ASC,
sprsec.sec_id ASC;
