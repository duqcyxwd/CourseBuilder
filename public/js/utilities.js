var DB_CONNECTION_URL = "../resources/library/dbConnection.php";
// utilities

/**
 * toggleClass
 *
 * adds or removes specified class from a given element
 * param element   : html element to be modified
 * param className : name of the class to be toggled
**/

function toggleClass(element, className){
  if (!element || !className) { return; }

  var classString = element.className, 
  	nameIndex   = classString.indexOf(className);

  if (nameIndex == -1) {
    classString += ' ' + className;
  } else {
    classString = classString.substr(0, nameIndex) + classString.substr(nameIndex + className.length);
  }
  element.className = classString;
}


/**
 * selectProgram
 *
 * sets the given program object to active
 * param obj : object to be set active
**/

function selectProgram(obj) {
	toggleClass(obj, 'active');
}


/**
 * updateProgramtitle
 *
 * set the program header of the given title
 * param title : new header title
**/

function updateProgramTitle(title) {
  var titles = document.getElementsByClassName('selected-program');
  for (var i = 0; i < titles.length; i++)
    titles[i].innerHTML = title;
}


/**
 * toggleVisibility
 *
 * hide or show the element specified
 * param obj : element to be toggled
 */
function toggleVisibility(obj) {
  var e = document.getElementById(obj);
  if (e.style.display == 'none')
    e.style.display = 'block';
  else
    e.style.display = 'none';
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
  console.log(postRequest);

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
