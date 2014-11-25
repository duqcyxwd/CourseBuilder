/**
 * timetable Class
 *
 * generates and populates a weekly timetable
 */

function Timetable(id) {

	var DAYS_IN_A_WEEK = 7;
	var TOTAL_ROWS = 25; // 30 minute blocks
	var TOTAL_COLS = 8;

	var weekdays = {
			1 : 'Sunday',
			2 : 'Monday',
			3 : 'Tuesday',
			4 : 'Wednesday',
			5 : 'Thursday',
			6 : 'Friday',
			7 : 'Saturday'
		};

	var day = {
		'M' : 2,
		'T'	: 3,
		'W'	: 4,
		'R'	: 5,
		'F'	: 6,
	};


	var tableID = id;
	var table = document.createElement('table');
	var tableBody = document.createElement('tbody');

	function init() {

		initFrame();
		initTable();
		fillTimeColumn();
	}


	/**
	 * initTableFrame
	 *
	 * create headers and empty table frame
	**/

	function initFrame() {

		var i,
				th,
				body,
				header = table.createTHead();
				row = header.insertRow(0);

		table.cellSpacing = 0;

		row.id = 'timetable-header-weekdays';

		// create empty time header
		th = document.createElement('th');
		th.innerHTML = ' ';
		row.appendChild(th);

		// create weekday header
		for (var key in weekdays) {
		  if (weekdays.hasOwnProperty(key)) {
		    th = document.createElement('th');
				th.innerHTML = weekdays[key];

				row.appendChild(th);
		  }
		}

		// add frame toe tableID
		document.getElementById(tableID).innerHTML = "";
		document.getElementById(tableID).appendChild(table);
	}

	/**
	 * initTableFrame
	 *
	 * create empty cells for timetable
	**/

	function initTable() {

		var i, j, tr, td;

		// loop through rows
		for (i = 0; i <= TOTAL_ROWS; i++) {
			tr = document.createElement('tr');

			// loop through columns
			for (j = 0; j < TOTAL_COLS; j++) {
				td = document.createElement('td');
				td.className = 'cellShading-2';
				td.id = 'weekday-' + j;

				if (j == 0) // first row
					td.className += ' time';

				tr.appendChild(td);
			};
			tableBody.appendChild(tr);
		};

		tableBody.id = 'timetable-slots';
		table.appendChild(tableBody);
	}


	/**
	 * fillTimeColumn
	 *
	 * fill first column with day times
	**/

	function fillTimeColumn() {

		var times = document.getElementsByClassName('time'),
				currTime = 8,
				amPm = '';

		for (var i = 0; i < times.length; i++) {
			if (i % 2 == 0) {
				amPm = (currTime < 12) ? ' am' : ' pm'
				times[i].innerHTML = (currTime % 12 || 12) + amPm;
				currTime += 1;

			}
		}
	}


	/**
	 * clearTable
	 *
	 * remove all elements from the table
	 *
	 */

	function clearTable() {
		table.innerHTML = "";
		tableBody.innerHTML = "";
		init();
	}


	/**
	 * appendCourse
	 *
	 * adds a new course to the timetable
	 * parm days			: string of days. (format: MTWRF)
	 * parm startTime	: string of time. (format: DD:HH:MM)
	 */

	function appendCourse(days, startTime, endTime, courseInfo) {

		var startHour = Math.floor(parseInt(startTime)/100),
		 		startMin  = parseInt(startTime)%100,
		 		endHour		= Math.floor(parseInt(endTime)/100),
		 		endMin		= Math.floor(parseInt(endTime)%100);

		var startRow = (startHour - 7) * 2 + (startMin - 5) / 30;
		var endRow = (endHour - 7) * 2 + (endMin + 5) / 30 - 1;

		var diffTime = endRow - startRow;

		// get days
		var daysArray = days.split("");

		var row,
				newCell,
				prevClass;


		// loop through days string
		for (var i = 0; i < daysArray.length; i++) {

			row = document.getElementsByTagName('tr');
			prevClass = row[1].firstChild.className; // get td class
			
			newCell = document.createElement('td');
			newCell.rowSpan   = diffTime;
			newCell.className = prevClass;
			newCell.innerHTML = courseInfo;
			newCell.style.backgroundColor = "#00A5FB";

			// get the first cell
			var rows = row[startRow].cells;
			var firstRow;

			// loop to find first cell that will be replaced
			for (var k = 0; k < rows.length; k++) {
				if (rows[k].id == ('weekday-' + day[daysArray[i]])) {
					firstRow = rows[k];
					break;
				}
			}

			// remove all rows that will be replace by new course
			for (var j = 1; j < diffTime; j++) {

				rows = row[startRow + j].cells;
				for (var k = 0; k < rows.length; k++) {
					if (rows[k].id == ('weekday-' + day[daysArray[i]])) {
						row[startRow + j].deleteCell(k);
						break;
					}
				}
			}

			firstRow.parentNode.replaceChild(newCell, firstRow);
		}
	}


	this.appendCourse = appendCourse;
	this.clearTable = clearTable;

	init();
}



var timetable;
var tableList;
window.onload = function() {
	timetable = new Timetable('timetable');
	tableList = new TableList('sidebar', timetable);

	// TODO: move this (for testing)
	var sols = '[[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1A","W",1435,1725]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1A","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L2A","F",835,1125]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1A","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L2B","F",835,1125]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1A","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L6A","T",1135,1425]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1A","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L6B","T",1135,1425]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1B","W",1435,1725]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1B","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L2A","F",835,1125]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1B","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L2B","F",835,1125]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1B","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L6A","T",1135,1425]],[["ECOR 1010","D","F",1335,1425],["ECOR 1010","D1","F",1435,1625],["MATH 1004","H","MW",1005,1125],["MATH 1004","H1","M",1535,1625],["MATH 1104","H","MW",1305,1425],["MATH 1104","H1","M",1435,1525],["CHEM 1101","C","TR",1005,1125],["CHEM 1101","C1","R",1435,1725],["PHYS 1004","B","MW",1805,1925],["PHYS 1004","L1B","W",1435,1725],["PHYS 1004","C","TR",1135,1255],["PHYS 1004","L6B","T",1135,1425]]]';
  storeTables(JSON.parse(sols), tableList);

	var timeTableInfo = ReadCookie("TimeTableInfo");


	if (!(typeof timeTableInfo === 'undefined' || timeTableInfo === null)){
		var page = DB_CONNECTION_URL,
		    params = timeTableInfo

		// request 
		AJAXRequest( function(response) {
		  var json = JSON.parse(response);

		  // Loop through terms
		  for (var solution = 0; solution < json.length; solution++) {
		  	var courseArray = json[solution];
		  	for (var i = courseArray.length - 1; i >= 0; i--) {
		  		var info = courseArray[i][0] + " " + courseArray[i][1];
		  		var days = courseArray[i][2];
		  		var startTime = courseArray[i][3];
		  		var endTime = courseArray[i][4];

					timetable.appendCourse(days, startTime, endTime, info);
		  	};
		  }
		}, page, params);
	}
}
