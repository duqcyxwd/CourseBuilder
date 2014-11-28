CourseBuilder
=============

Course selection assistant which generates a schedule based on student input

Contributor: Kevin, Yongqinchuan, Mitch


HOW THE APPLICATION DETERMINES WHAT CLASSES SOMEONE CAN TAKE

To determine a students year standing we used the requirements set by the University ( http://carleton.ca/engineering-design/current-students/undergrad-academic-support/status-vs-standing/ ). On the first page the student chooses the classes that he/she has already taken. When they hit 'submit' the JS sends an array of classes that have been taken to the backend, this is how we can determine year standing. The getYearStanding() function in database.php parses the given class array to determine what their year-standing is. 
