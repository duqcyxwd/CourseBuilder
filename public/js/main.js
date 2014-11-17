var DB_CONNECTION_URL = "../resources/library/dbConnection.php";
var prerequisiteTable; // global declaration of table

var stringToYear = {
      'First'  : 1,
      'Second' : 2,
      'Third'  : 3,
      'Fourth' : 4,
    };

/**
 * onLoad
 *
 * initialize dropdown menu. Add listener for each
 * dropdown option. When an option is selected, the
 * method updates the program title and generates the
 * prerequisite tree by making an AJAX request.
**/

window.onload = function() {

  // select all program dropdown menu items
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


  var onPatternCheckbox = document.getElementById('checkboxInput');
  onPatternCheckbox.onclick = function() {
    toggleVisibility('year-select-dd');

    if (prerequisiteTable)
      prerequisiteTable.resetSelection();
  }


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

  var submit = document.getElementById('submit');
  submit.onclick = function() {
    // handle submission
  }
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

    var json = JSON.parse(response);

    prerequisiteTable = new table(tableID, numOfYears);

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

      // Loop through courses within this term
      for (var course = 0; course < json[termNumber].length; course++) {
        courseLabel = json[termNumber][course];
        courseDetails = courseLabel.split(/[ ,]+/);
        prerequisiteTable.appendCourse(year, term, courseDetails[name], courseDetails[code], courseLabel);
      }

    }
  }, page, params);
}


/**
 * AJAXRequest
 *
 * method for making AJAX requrests
 * param callback : function that will be called when the request is completed
 * param page     : url to requested php webpage
 * param params   : object containing parameters to be passed to the page
**/

function AJAXRequest(callback, page, params) {
  var httpRequest = new XMLHttpRequest();
  var postRequest = objectToParameters(params);

  httpRequest.onreadystatechange = function() {
    if (httpRequest.readyState === 4 && httpRequest.status === 200)
      callback(httpRequest.responseText);
  }

  httpRequest.open("post", page, true);
  httpRequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");
  httpRequest.send(postRequest);

}


/**
 * objectToParameters
 *
 * converts a given object into PHP parameters
 * This method is used to help make an AJAXRequest
 * param obj : object to be converted
 * returns string parameters of object
**/

function objectToParameters(obj) {
  var retval = "";
  for (var key in obj)
    retval += key + "=" + obj[key] + "&";
  return retval.slice(0, -1);
}
