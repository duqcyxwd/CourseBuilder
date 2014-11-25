var prerequisiteTable; // global declaration of table

// String to number conversion
var stringToYear = {
      'First'  : 1,
      'Second' : 2,
      'Third'  : 3
    };


/**
 * submitTable
 *
 * method which calls the server to generate
 * the list of possible timetables according
 * to the courses selected by the user.
 */

function submitTable() {

  if (prerequisiteTable == null) return;
  var selectedCourses = prerequisiteTable.getStringFormat();
  var page = TIMETABLE_URL;
  var params = { action: "timeTable", 
                 courseCompleted: selectedCourses, 
                 program: prerequisiteTable.getProgramName()
               };

  var form = document.getElementById("submit");
  form.setAttribute("action", page);

  SetCookie("TimeTableInfo", params);
  console.log(prerequisiteTable.getStringFormat());
  form.submit();
  // window.location.href = TIMETABLE_URL;
}


/**
 * createTable
 *
 * make AJAX request to get courses and create a table
 * containing all the information for the prerequisite tree
 * param tableID          : element ID to append table to
 * param selectedProgram  : program to be displayed
 * param numOfYears       : length of program (in years)
**/

function createTable(tableID, selectedProgram, numOfYears) {

  var page = DB_CONNECTION_URL,
      params = { action: "prereqTree", program: selectedProgram };

  // request 
  AJAXRequest( function(response) {

    console.log(response);
    var json = JSON.parse(response);
    prerequisiteTable = new table(tableID, numOfYears, selectedProgram);

    var term, 
        courseLabel, 
        courseDetails,
        year = 0,
        name = 0,
        code = 1;
    
    console.log(json);
    // Loop through terms
    for (var termNumber = 0; termNumber < json.length; termNumber++) {

      // increment year and alternate term
      if (termNumber % 2 == 0) {
        year++;
        term = 'FALL';
      } else {
        term = 'WINTER';
      }

      var isElective = false;
      var listOfElectives = [];


      // Loop through courses within this term
      for (var course = 0; course < json[termNumber].length; course++) {
        courseLabel = json[termNumber][course];
        courseDetails = courseLabel.split(/[ ,]+/);

        isElective = (courseDetails[name] === "Elective") ? true : false;

        prerequisiteTable.appendCourse(year, term, courseDetails[name], courseDetails[code], courseLabel, isElective, listOfElectives);
      }

    }
  }, page, params);
}


/**
 * createDropdownMenu
 *
 * generate the dropdown menu
 * which displays the programs
 * that the user can select from
 */

function createDropdownMenu() {
  
  var dropdownMenuItems = document.getElementsByClassName("gen-tree");

  for (var i = 0; i < dropdownMenuItems.length; i++) {
    dropdownMenuItems[i].onclick = function() {

      updateProgramTitle(this.innerHTML);

      var tableId    = 'course-table'; 
      var program    = this.innerHTML;
      var numOfYears = 4;

      createTable(tableId, program, numOfYears);
    }
  }
}


/**
 * createPatternCheckbox
 *
 * generate the checkbox which
 * allows the user to choose
 * what year he just finished
 */

function createPatternCheckbox() {

  var onPatternCheckbox = document.getElementById('checkboxInput');
  onPatternCheckbox.onclick = function() {
    toggleVisibility('year-select-dd');

    if (prerequisiteTable)
      prerequisiteTable.resetSelection();
  }
}


/**
 * createYearDropdown
 *
 * generate the dropdown menu which
 * allows the user to select the year
 * that they last completed
 */

function createYearDropdown() {

  var selectYearDropDown = document.getElementsByClassName('select-year');

  for (var i = 0; i < selectYearDropDown.length; i++) {
    selectYearDropDown[i].onclick = function() {

      if (prerequisiteTable != null) {
        var text = this.innerHTML.split(/[ ,]+/);
        var year = stringToYear[text[0]];

        prerequisiteTable.selectYear(year);
      }
    }
  }
}


/**
 * onLoad
 *
 * initialize dropdown menu. Add listener for each
 * dropdown option. When an option is selected, the
 * method updates the program title and generates the
 * prerequisite tree by making an AJAX request.
**/

window.onload = function() {

  createDropdownMenu();
  createPatternCheckbox();
  createYearDropdown();

  var submit = document.getElementById('submitButton');
  submit.onclick = function() {
    submitTable();
  }
}
