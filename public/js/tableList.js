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

	function init() {

		initFrame(id);
	}


	function initFrame() {
		listFrame = document.getElementById(listID);
	}

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


	function formatBody(courses) {

		var formattedString = "";
		for (var i = 0; i < courses.length; i++) {
			if (formattedString.indexOf(courses[i][0]) == -1 ) {
				formattedString += courses[i][0] + ", ";
			}
		}

		return formattedString;
	}


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

	function getSelectedList() {
		return selectedCourses || courseList[0];
	}

	function clearList() {
		courseList = [];
		listFrame.innerHTML = "";
	}

	this.appendTable = appendTable;
	this.getSelectedList = getSelectedList;
	this.clearList = clearList;

	init(); // initialize object on creation
}

