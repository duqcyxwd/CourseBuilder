/**
 * timetable Class
 *
 * generates and populates the list of possible
 * timetables on the timetables page.
 */

function TableList(id, timeTableObj) {

	var listID = id;
	var listFrame = document.createElement('div');
	var courseList = [];
	var timeTable = timeTableObj;

	function init() {

		initFrame(id);
	}


	function initFrame() {
		var list = document.getElementById(listID);
		list.appendChild(listFrame);
	}

	function appendTable(courses) {

		var item = document.createElement('div');
		item.className = 'sidebar-item';
		item.innerHTML = "<div class='item-header'>Option " + (courseList.length + 1) + "</div>"
								   + "<div class='item-body'>" + JSON.stringify(courses).substring(0,100) + "</div>";

		// append course list and timeTable to item
		item.courses = courses;
		item.table = timeTable;
		
		addListener(item);
		listFrame.appendChild(item);
		
		courseList.push(courses);
	}


	function addListener(item) {

		item.onclick = function() {

			// remove all items from the table
			this.table.clearTable();

			// unhighlight all other items
			var items = document.getElementsByClassName('sidebar-item');
			for (var i = items.length - 1; i >= 0; i--) {
				items[i].id = '';
			};

			this.id = 'sidebar-highlight';

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

