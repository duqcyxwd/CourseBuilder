/**
 * timetable Class
 *
 * generates and populates the list of possible
 * timetables on the timetables page.
 */

function TableList(id, timeTableObj) {

	var listID = id;
	var listFrame;
	var courseList = [];
	var selectedCourses = [];
	var timeTable = timeTableObj;


	/**
	 * init
	 *
	 * initialize the object
	 */

	function init() {

		initFrame(id);
	}


	/**
	 * initFrame
	 *
	 * set the listFrame to the given id. This
	 * object will be appended to this id.
	 */

	function initFrame() {
		listFrame = document.getElementById(listID);
	}


	/**
	 * appendTable
	 *
	 * append a new item to the tableList
	 *
	 * param courses: list of courses in the table
	 * param courseNames: full name of courses
	 */

	function appendTable(courses, courseNames) {

		var item = document.createElement('div');
		var formattedBody = formatBody(courses);

		item.className = id + '-item';
		item.innerHTML = "<div class='item-header'>Timetable " + (courseList.length + 1) + "</div>"
								   + "<div class='item-body'>" + formattedBody + "</div>";

		// append course list and timeTable to item
		for (var i = courses.length - 1; i >= 0; i--) {
			for (var j = courseNames.length - 1; j >= 0; j--) {
				if (courses[i][0] == courseNames[j][0]) {
					courses[i].push(courseNames[j][1]);
				}
			};
		};
		item.courses = courses;
		item.table = timeTable;

		addListener(item);
		listFrame.appendChild(item);
		
		if (courseList[0] == null) { // set first list to selected
			selectedCourses = courses;
		}

		courseList.push(courses);
	}


	/**
	 * formatBody
	 *
	 * format the body of the list item 
	 *
	 * param courses: list of courses that will
	 * 								be added to the table
	 */

	function formatBody(courses) {

		var formattedString = "";
		for (var i = 0; i < courses.length; i++) {
			if (formattedString.indexOf(courses[i][0]) == -1 ) {
				formattedString += courses[i][0] + ", ";
			}
		}

		return formattedString;
	}


	/**
	 * addListener
	 *
	 * add onclick functions to tableList
	 * items which highlight the item and
	 * update the table to the new set of
	 * courses
	 *
	 * param item: item to add listener to
	 */

	function addListener(item) {

		item.onclick = function() {

			// unhighlight all other items
			var items = document.getElementsByClassName(id + '-item');
			for (var i = items.length - 1; i >= 0; i--) {
			  items[i].id = '';
			};

			this.id = id + '-highlight';

			selectedCourses = this.courses;
			setTable(timeTable, this.courses);
		} 

	}


	/**
	 * getSelectedList
	 *
	 * returns string representation of
	 * semi-colon separated list of
	 * selected courses
	 */
	 
	function getSelectedList() {

		var courseString = "";
		for (var i = 0; i < selectedCourses.length; i++) {
			courseString += selectedCourses[i][0] + " " + selectedCourses[i][1] + ";";
		};

		return courseString;
	}


	/**
	 * clearList
	 *
	 * clear the tableList of all items.
	 * Used for updating the list with a
	 * new set of timetables
	 */

	function clearList() {
		courseList = [];
		listFrame.innerHTML = "";
	}

	this.appendTable = appendTable;
	this.getSelectedList = getSelectedList;
	this.clearList = clearList;

	init(); // initialize object on creation
}

