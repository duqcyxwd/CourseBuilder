var prerequisiteTable; // global declaration of table

// String to number conversion
var stringToYear = {
      '1st' : 1,
      '2nd' : 2,
      '3rd' : 3,
      '4th' : 4
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
  var maxCourseTaking = document.getElementById('max').value || 5;

  var page = TIMETABLE_URL;
  var params = { action: "timeTable", 
                 courseCompleted: selectedCourses, 
                 program: prerequisiteTable.getProgramName(),
                 max: maxCourseTaking
               };

  SetCookie("TimeTableInfo", params);

  window.location.href = TIMETABLE_URL;
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
    
    var allElectives = json[1];

    json = json[0];
    prerequisiteTable = new table(tableID, numOfYears, selectedProgram);

    var term, 
        courseLabel, 
        courseDetails,
        year = 0,
        name = 0,
        code = 1;
    
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


      // Loop through courses within this term
      for (var course = 0; course < json[termNumber].length; course++) {
        courseLabel = json[termNumber][course];
        courseDetails = courseLabel.split(/[ ,]+/);

        var listOfElectives = [];

        // if it's an elective, loop through all electives to find matching elective type
        if (courseDetails[name] === "Elective") {
          for (var i = 0; i < allElectives.length; i++) {
            if (allElectives[i][2] == courseDetails[code]) {
              listOfElectives.push(allElectives[i]);
            }
          }

          isElective = listOfElectives[0][1];
        }

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
 * that they last completed.
 * 
 * The selection is based on the time of year.
 * If Fall Term: On pattern selects everything
 * up to the fall term of year specified
 *
 * If Winter Term: On pattern selects everything
 * up to the Winter term of year specified
 */

function createYearDropdown() {

  var selectYearDropDown = document.getElementsByClassName('select-year');

  for (var i = 0; i < selectYearDropDown.length; i++) {
    selectYearDropDown[i].onclick = function() {

      if (prerequisiteTable != null) {
        var text = this.innerHTML.split(/[ ,]+/);
        var year = stringToYear[text[0]] - 1;

        var date = new Date();

        if (date.getMonth() < 4 || date.getMonth() > 10) { // winter
          prerequisiteTable.selectTerm(year * 2 - 2);
        } else { // fall
          prerequisiteTable.selectTerm(year * 2 - 1);
        }

        // updateProgramTitle(this.innerHTML);
        document.getElementById('year-select-subtitle').innerHTML = this.innerHTML;
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
