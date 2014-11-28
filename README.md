CourseBuilder
=============

Course selection assistant which generates a schedule based on student input

Contributor: Kevin, Yongqinchuan, Mitch


HOW THE APPLICATION DETERMINES WHAT CLASSES SOMEONE CAN TAKE

To determine a students year standing we used the requirements set by the University ( http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/ ). On the first page the student chooses the classes that he/she has already taken. When they hit 'submit' the JS sends an array of classes that have been taken to the backend, this is how we can determine year standing. The getYearStanding() function in database.php parses the given class array to determine what their year-standing is. 


HOW DOES THE APPLICATION DETERMINE HOW TO REGISTER FOR COURSES

Once a user has chosen the specific course schedual that they want they click the 'Register'. Once the that button is clicked the user accesses a Mutex lock to enter the registerForClasses() function. If the lock is already taken up than a message is displayed to "try to register again". Once the user has registered for classes the lock is released and other users can now register. 
registerForClasses() serves 2 purposes, it needs to check that all classes a user wants to register for are available(capacity > 0) and then it registers for those classes by decrementing the capacity by 1. If one of the classes that a student wants to take is full then a error message is returned. If a student cannot register for one of the classes than he will not be able to register for all other selected classes. They will need to remove the full class and try to register again. 
