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
	var timeTable = timeTableObj;

	function init() {

		initFrame(id);
	}


	function initFrame() {
		listFrame = document.getElementById(listID);
	}

	function appendTable(courses) {

		var item = document.createElement('div');

		var formattedBody = formatBody(courses);

		item.className = id + '-item';
		item.innerHTML = "<div class='item-header'>Timetable " + (courseList.length + 1) + "</div>"
								   + "<div class='item-body'>" + formattedBody + "</div>";

		// append course list and timeTable to item
		item.courses = courses;
		item.table = timeTable;
		
		addListener(item);
		listFrame.appendChild(item);
		
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

			// remove all items from the table
			this.table.clearTable();

			// unhighlight all other items
			var items = document.getElementsByClassName(id + '-item');
			for (var i = items.length - 1; i >= 0; i--) {
				items[i].id = '';
			};

			this.id = id + '-highlight';

			var courses = this.courses;

			// add new elements to the timetable
	  	for (var i = courses.length - 1; i >= 0; i--) {
	  		var info = courses[i][0] + " " + courses[i][1];
	  		var days = courses[i][2];
	  		var startTime = courses[i][3];
	  		var endTime = courses[i][4];

				timeTable.appendCourse(days, startTime, endTime, info);
	  	};
		}
	}

	this.appendTable = appendTable;

	init(); // initialize object on creation

}

