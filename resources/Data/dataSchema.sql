DROP TABLE IF EXISTS Prerequisite;
DROP TABLE IF EXISTS SpePrereq;
DROP TABLE IF EXISTS Classes;
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
