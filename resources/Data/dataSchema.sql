DROP TABLE IF EXISTS CourseCompleted;
DROP TABLE IF EXISTS Prerequisite;
DROP TABLE IF EXISTS SpePrereq;
DROP TABLE IF EXISTS Classes;
DROP TABLE IF EXISTS Students;
DROP TABLE IF EXISTS ProgramsRequirement;
DROP TABLE IF EXISTS Electives;
DROP TABLE IF EXISTS Courses;

--
-- Database: `courseBuilder`
--

-- --------------------------------------------------------

--
-- Table structure for table `Courses`
--

CREATE TABLE IF NOT EXISTS Courses(
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	CourseTitle varchar(40)  NOT NULL,
	PRIMARY KEY(Subject, CourseNumber)
);

--
-- Table structure for table `ProgramsRequirement`
--

CREATE TABLE IF NOT EXISTS ProgramsRequirement(
	AutoId integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Program varchar(30) NOT NULL,
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	YearRequirement tinyint(1)
	-- FOREIGN KEY (Subject, CourseNumber) references Courses (Subject,CourseNumber)
	-- PRIMARY KEY(Name, Subject, CourseNumber)
);

--
-- Table structure for table `Electives`
-- TODO set FOREIGN KEY!!!!
--

CREATE TABLE IF NOT EXISTS Electives(
	AutoId integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
	ElectiveType int(4)  NOT NULL,
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	ElectiveName varchar(60) NOT NULL,
	FOREIGN KEY (Subject, CourseNumber) references Courses (Subject,CourseNumber)
	-- PRIMARY KEY(Name, Subject, CourseNumber)
);

--
-- Table structure for table `Students`
--

CREATE TABLE IF NOT EXISTS Students(
	Name varchar(30) references ProgramsRequirement (Name),
	Email varchar(30),
	Student_number int(9) PRIMARY KEY NOT NULL,
	Program varchar(30) NOT NULL,
	User_Name varchar(20) NOT NULL,
	Password varchar(20) NOT NULL
);

-- INSERT INTO Students (Name, Email, Student_number, Program, User_Name, Password) values 
-- ("Chuan", "duq@gmail.com", '100810219', 'Software Engineering', 'duqcyxwd', '12345');



--
-- Table structure for table `CourseCompleted`
--


CREATE TABLE IF NOT EXISTS CourseCompleted(
	Student_number int(9) NOT NULL,
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	FOREIGN KEY (Subject, CourseNumber) references Courses (Subject, CourseNumber),
	FOREIGN KEY (Student_number) references Students (Student_number),
	PRIMARY KEY(Student_number, Subject, CourseNumber)
);

-- INSERT INTO CourseCompleted (Student_number, Subject, CourseNumber) values 
-- ("100810219", "SYSC", 3101);

--
-- Table structure for table `Prerequisite`
--
CREATE TABLE IF NOT EXISTS Prerequisite(
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	Requirement varchar(200),
	YearReq int(1),
	FOREIGN KEY (Subject, CourseNumber) references Courses (Subject, CourseNumber)

);

--
-- Table structure for table `Classes`
--
CREATE TABLE IF NOT EXISTS Classes(
	CourseId integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
	Subject varchar(10)  NOT NULL,
	CourseNumber int(4)  NOT NULL,
	Start_Time varchar(4),
	End_Time varchar(4),
	Days varchar(6),
	RoomCap varchar(6),
	Professor varchar(30),
	Type varchar(4),
	Section varchar(4),
	Term varchar(5) NOT NULL,
	FOREIGN KEY (Subject, CourseNumber) references Courses (Subject, CourseNumber)

);

--
-- Table structure for table `Administrators`
--

CREATE TABLE IF NOT EXISTS `Administrators` (
id integer PRIMARY KEY NOT NULL AUTO_INCREMENT,
Username varchar(16) NOT NULL,
Password varchar(16) NOT NULL
);


select * from Classes;
