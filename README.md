CourseBuilder
=============



Course selection assistant which generates a schedule based on student input

###### Kevin Rosengren (std# 100848909): 
Front-end design for course selection, timetable and admin page. JavaScript used for dynamic front-end features.
###### Yongqinchuan Du (std# 100810219): 
Database implementation, timetable generator algorithm and server-side communication
###### Mitch Cail (std# 100871691): 
prerequists generator, registration, year-standing algorithm

###File Structure
#####Course Builder /
Main folder containing all of the files for the project
#####public /
Contains all of the CSS, HTML and JavaScript visible to the client (user)
#####css

⋅⋅⋅admin.css: contains all of the styling for the administrator page

⋅⋅⋅checkbox.css: styling for the “On Pattern” button on the index page

⋅⋅⋅dropdown.css: styling for the programs and year dropdown on the index page 

⋅⋅⋅main.css: styling for the header, footer and other components shared across all pages. ￼Also contains the styling for the course selection table and time table.

⋅⋅⋅noJS.css: styling for the dropdown menu in case JavaScript is disabled.
popup.css: styling for the pop-up menu when selecting an elective in the course table submit.css: styling for the submit button on the course selection page

⋅⋅⋅tableList.css: styling for the alternate tables in the sidebar on the timetable page

#####js /
⋅⋅⋅course.js: class that represents a single course on the selection table. Allows for deep customization such as adding functionality to each course. For example, the elective courses display a popup with all the courses related to that elective type. The data contained in the object can be converted into JSON format.

⋅⋅⋅homepage.js: contains all the functions only used on the index page such as creating the dropdown menu, the on pattern dropdown and generating the course selection table. Also adds listeners to each button including the submit. This file is also responsible for transmitting the courses selected to the server. (i.e. dbConnection.php file)

⋅⋅⋅table.js: class that represents the table that is displayed on the homepage. This class is responsible for properly formatting the table and creating all the course objects visible to the user. It can also translate the selected courses into JSON format or a comma separated string. Other methods include resetting selection as well as selecting entire terms.

⋅⋅⋅tableList.js: class that generates and populates the list of alternate timetables in the sidebar of the timetable page. Can dynamically add tables. Also responsible for updating the timetable when selecting an alternative.

⋅⋅⋅timetable.js: class that generates and populates the weekly timetable. Has the ability to dynamically add courses to the timetable.

⋅⋅⋅utilities.js: contains all methods that are shared among multiple pages. Methods include toggling classes, toggling DOM object visibility, as well as a general AJAX method which can make any type of AJAX requests with given parameters.

⋅⋅⋅about.php: generic about page. Contains information about CourseBuilder. admin.php: administrator login page. Asks for username and password and calls the

⋅⋅⋅adminlogin.php file to determine if the credentials are correct.

⋅⋅⋅error.php: friendly error page which displays information about what went wrong. All errors are directed to this page.
help.php: generic help page. Contains information about how to user the application.

⋅⋅⋅index.php: home page containing the program selection dropdown, and course selection table. Allows the user to select courses and hit submit. This page calls the dbConnection.php file when the user presses the submit button.

⋅⋅⋅timetable.php: page that displays the generated timetables based on the user input from

#####resources /

...the index.php page.
Contains all of the files that communicate with the server. Also contains files related to the database such as the schema and database connection. The page header and footer found on all pages are also located in this folder under templates.

#####data /
Contains all the files required to setup the database. (all schema files)

#####include /
constants.php: contains all constants shared among the php files such as database table and column names.
database.php: contains all of the database communication. Connects to the Course Builder database and performs queries.

#####library /
⋅⋅⋅adminlogin.php: page only visible to administrators. Allows the admin to view and update input files.

...dbConnection.php: behaves like the bridge between the course selection view and the timetable view. When a user hits submit on the home page, it callsthis file which handles the processes necessary to generate the
timetables.

...PreRequisite.class.php: contains the logic for determining if the user has the necessary prerequisites for taking a given course. timeTable.class.php: contains the algorithm which produces the timetables.

⋅⋅⋅utilities.php: contains general utility methods getting the current term and converting strings into arrays.

#####templates /
⋅⋅⋅footer.php: contains the HTML for generating the footers for each client page
⋅⋅⋅header.php: contains the HTML for generating the headers for each client page config.php: sets up the connection with the database and includes all the necessary files for displaying the page. Also defines the file path variables.
⋅⋅⋅install.php: used to create and populate the database for the first time. This only needs to run once and if successful, this App should function properly.


HOW THE APPLICATION DETERMINES WHAT CLASSES SOMEONE CAN TAKE

To determine a students year standing we used the requirements set by the University ( http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/ ). On the first page the student chooses the classes that he/she has already taken. When they hit 'submit' the JS sends an array of classes that have been taken to the backend, this is how we can determine year standing. The getYearStanding() function in database.php parses the given class array to determine what their year-standing is. 


HOW DOES THE APPLICATION DETERMINE HOW TO REGISTER FOR COURSES

Once a user has chosen the specific course schedual that they want they click the 'Register'. Once the that button is clicked the user accesses a Mutex lock to enter the registerForClasses() function. If the lock is already taken up than a message is displayed to "try to register again". Once the user has registered for classes the lock is released and other users can now register. 
registerForClasses() serves 2 purposes, it needs to check that all classes a user wants to register for are available(capacity > 0) and then it registers for those classes by decrementing the capacity by 1. If one of the classes that a student wants to take is full then a error message is returned. If a student cannot register for one of the classes than he will not be able to register for all other selected classes. They will need to remove the full class and try to register again. 
