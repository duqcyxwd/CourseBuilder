DROP TABLE IF EXISTS CourseCompleted;
DROP TABLE IF EXISTS Prerequisite;
DROP TABLE IF EXISTS Classes;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS Courses;
-- DROP TABLE IF EXISTS Classes;


CREATE TABLE IF NOT EXISTS Students(
	Name varchar(30),
	Email varchar(30),
	Student_number int(9) PRIMARY KEY NOT NULL,
	Program varchar(30) NOT NULL,
	User_Name varchar(20) NOT NULL,
	Password varchar(20) NOT NULL
);

insert into Students (Name, Email, Student_number, Program, User_Name, Password) values 
("Chuan", "duq@gmail.com", '100810219', 'Software Engineering', 'duqcyxwd', '12345');


CREATE TABLE IF NOT EXISTS Courses(
	Subject varchar(4)  NOT NULL,
	CourseNumber int(3)  NOT NULL,
	CourseTitle varchar(40)  NOT NULL,
	PRIMARY KEY(Subject, CourseNumber)

);

INSERT INTO Courses (Subject, CourseNumber, CourseTitle) values 
("SYSC", "3101", 'Intensive First Year Arabic'),
("SYSC", "3102", 'Intensive First Year Arabic Fake');

CREATE TABLE IF NOT EXISTS CourseCompleted(
	Student_number int(9) NOT NULL,
	Subject varchar(4)  NOT NULL,
	CourseNumber int(3)  NOT NULL,
	FOREIGN KEY (Subject, CourseNumber) references Courses (Subject, CourseNumber),
	FOREIGN KEY (Student_number) references Students (Student_number),
	PRIMARY KEY(Student_number, Subject, CourseNumber)
);

INSERT INTO CourseCompleted (Student_number, Subject, CourseNumber) values 
("100810219", "SYSC", 3101);

SELECT * FROM CourseCompleted;

CREATE TABLE IF NOT EXISTS Prerequisite(
	Subject varchar(4)  NOT NULL,
	CourseNumber int(3)  NOT NULL,
	RequiredCourseSubject varchar(4)  NOT NULL,
	RequiredCourseCourseNumber int(3)  NOT NULL,
	foreign key (Subject, CourseNumber) references Courses (Subject, CourseNumber),
	foreign key (RequiredCourseSubject, RequiredCourseCourseNumber) references Courses (Subject, CourseNumber),
	PRIMARY KEY(Subject, CourseNumber, RequiredCourseSubject, RequiredCourseCourseNumber)

);
INSERT INTO Prerequisite (Subject, CourseNumber, RequiredCourseSubject, RequiredCourseCourseNumber) values 
("SYSC", 3102, "SYSC", 3101);

SELECT * FROM Prerequisite;

CREATE TABLE IF NOT EXISTS Classes(
	CRN integer PRIMARY KEY,
	Subject varchar(4)  NOT NULL,
	CourseNumber int(3)  NOT NULL,
	Start_Time varchar(4) NOT NULL,
	End_Time varchar(4) NOT NULL,
	Days varchar(6) NOT NULL,
	Room varchar(6) NOT NULL,
	Professor varchar(30) NOT NULL,
	Available_seat int(3) NOT NULL,
	Type varchar(4) NOT NULL,
	Section varchar(4) NOT NULL,
	foreign key (Subject, CourseNumber) references Courses (Subject, CourseNumber)
);

insert into Classes (Subject, CourseNumber, Start_Time, End_Time, Days, Room, Professor, Available_seat, Type, Section) values
('SYSC', '3102', '1135', '1300', 'TR', 'A', 40, 'LEC', "A.A", 35);

select * from Classes;


CREATE TABLE IF NOT EXISTS ProgramsRequirement(
	Name varchar(30) NOT NULL,
	Subject varchar(4)  NOT NULL,
	CourseNumber int(3)  NOT NULL,
	isElective tinyint(1) NOT NULL, -- use 0 for true, else for false
	YearRequirement tinyint(1),
	foreign key (Subject,CourseNumber) references Courses (Subject,CourseNumber)
);
insert into ProgramsRequirement (Name, Subject, CourseNumber, isElective, YearRequirement) values
("Software Engineering", "SYSC", 3101, 1, 3);



