/**
 * table Class
 *
 * generates and populates a prerequisite table
 */

function table(id, numOfYears, progName) {

  var MAX_NUMBER_OF_ROWS = 6;

  var tableID = id;
  var programName = progName;
  var numOfYears = numOfYears;
  var table = document.createElement('table');
  var tableBody = document.createElement('tbody');
  var listOfCourses = [];


  // initialize table
  function init() {
    initTableFrame();
    initTable();
  }


  /**
   * initTableFrame
   *
   * create headers and empty table frame
  **/

  function initTableFrame() {

    var i, 
        th,
        body,
        term, 
        header = table.createTHead(),
        row = header.insertRow(0);

    row.id = "course-header-year";

    // create year headers
    for (i = 1; i <= numOfYears; i++) {
      th = document.createElement('th');
      th.innerHTML = "YEAR " + i;
      th.colSpan = '2';
      row.appendChild(th);
    };

    row = header.insertRow(1);
    row.id = "course-header-term";

    // create term headers
    for (i = 0; i < numOfYears * 2; i++) {
      term = (i % 2 == 0) ? "FALL" : "WINTER"; // alternate
      th = document.createElement('th');
      th.innerHTML = term;
      row.appendChild(th);
    }

    table.id = "schedule-table";

    // add frame to tableID
    document.getElementById(tableID).innerHTML = "";
    document.getElementById(tableID).appendChild(table);
  }


  /**
   * initTable
   *
   * create cells inside the table
  **/

  function initTable() {

    var i, j, tr, td;

    // Loop through rows
    for (i = 0; i < MAX_NUMBER_OF_ROWS; i++) {
      tr = document.createElement('tr');

      // Loop through columns
      for (j = 0; j < numOfYears * 2; j++) {
        td = document.createElement('td');
        td.className = 'cellShading term-' + j;
        tr.appendChild(td);
      };
      tableBody.appendChild(tr);
    };

    tableBody.id = "course-classes-table";
    table.appendChild(tableBody);
  }


  /**
   * appendCourse
   *
   * adds a new course element to the end of the
   * specified year and term
  **/
  function appendCourse(year, term, name, number, innerHTML, isElective, listOfElectives) {

    var listOfElectives = listOfElectives || [];
    // var isElective = isElective || false;
    
    var termNum;
    if (term == "FALL")
      termNum = year * 2 - 2; // term is zero indexed
    else if (term == "WINTER")
      termNum = year * 2 - 1;
    else
      return;


    var courseList = document.getElementsByClassName('term-' + termNum);

    // append child to empty field
    for (var i = 0; i < courseList.length; i++) {
      if (courseList[i].innerHTML === '') {
        listOfCourses.push(new course(courseList[i], name, number, year, term, innerHTML, isElective, listOfElectives));
        break;
      }
    }
  }

  function getCourses() {
    return listOfCourses;
  }


  function resetSelection() {
    for (var i = listOfCourses.length - 1; i >= 0; i--)
      listOfCourses[i].deselect();
  }


  function getSelectedCourses() {

    var retval = [];
    for (var i = listOfCourses.length - 1; i >= 0; i--)
      if (listOfCourses[i].selected)
        retval.push(listOfCourses[i]);

    return retval;
  }


  function selectYear(year) {
    resetSelection();

    for (var i = listOfCourses.length - 1; i >= 0; i--)
      if (listOfCourses[i].year <= year)
        listOfCourses[i].select();
  }


  function getJSON() {
    var json = [];

    for (var i = listOfCourses.length - 1; i >= 0; i--)
      if (listOfCourses[i].isSelected())
        json.push(listOfCourses[i].getJSON());

    return json;
  }

  function getStringFormat() {
    var stringFormat = "";

    for (var i = listOfCourses.length - 1; i >= 0; i--)
      if (listOfCourses[i].isSelected())
        stringFormat += listOfCourses[i].getName() + " " + listOfCourses[i].getNumber() + ",";

    // remove trailing comma
    return stringFormat.substring(0, stringFormat.length - 1);
  }

  function getProgramName() {
    return programName;
  }


  // public methods
  this.init            = init;
  this.getJSON         = getJSON;
  this.appendCourse    = appendCourse;
  this.selectYear      = selectYear;
  this.resetSelection  = resetSelection;
  this.getStringFormat = getStringFormat;
  this.getProgramName  = getProgramName;
  this.getSelectedCourses = getSelectedCourses;


  init(); // initialize table
}
