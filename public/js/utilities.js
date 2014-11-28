var DB_CONNECTION_URL = "../resources/library/dbConnection.php";
var TIMETABLE_URL = "timetable.php";
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
 * param title : new header name
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

/**
 * SetCookie
 *
 * Set the value to cookie
 * param obj : cookieName,cookieValue,minutes
**/

function SetCookie(cookieName, cookieValue, minutes) {
  var currentTime = new Date();
  var expiresTime = new Date();
  if (minutes==null || minutes==0) minutes=1000;
  expiresTime.setTime(currentTime.getTime() + 60000*minutes);
  var cookie = cookieName+"="+JSON.stringify(cookieValue);
  document.cookie = cookie + ";expires="+expiresTime.toGMTString();
}

/**
 * ReadCookie
 *
 * read the value to cookie
 * param obj : cookieName
**/

function ReadCookie(cookieName) {
  var result = document.cookie.match(new RegExp(cookieName + '=([^;]+)'));
  result && (result = JSON.parse(result[1]));
  return result;
}


/**
 * storeTables
 *
 * stores all tables in tableList object
 */

function storeTables(courseNames, tables, tableListObj) {

  tableListObj.clearList();

  for (var i = 0; i < tables.length; i++) {
    var table = tables[i];
    tableListObj.appendTable(tables[i], courseNames);
  }
}


function setTable (timeTable, courses) {
  // remove all items from the table
  timeTable.clearTable();

  // add new elements to the timetable
  for (var i = courses.length - 1; i >= 0; i--) {
    var info = courses[i][0] + " " + courses[i][1];
    var days = courses[i][2];
    var startTime = courses[i][3];
    var endTime = courses[i][4];
      var courseTitle = courses[i][5];

  timeTable.appendCourse(days, startTime, endTime, info, courseTitle);

  };
}


/**
 * listSelectedCourses
 *
 * list all courses selected in the
 * given DOM element
 *
 * param id: element ID
 * param childClass: className of child element to be created
 * param courses: array of courses to append to id
 */

function listSelectedCourses(id, childClass, courses, params, page) {

  var elem = document.getElementById(id);
  elem.innerHTML = ""; // clear list

  for (var i = 0; i < courses.length; i++) {
    if (courses[i][0].length > 2) { // skip empty strings
      var course = document.createElement('div');
      course.innerHTML = courses[i][0];
      course.className = childClass;
      course.allCourses = courses;

      course.onmouseover = function() {
        this.innerHTML += " (remove)";
      }

      course.onmouseout = function() {
        this.innerHTML = this.innerHTML.slice(0, -9);
      }

      course.onclick = function() {

        var tableCourses = "";

        for (var i = 0; i < this.allCourses.length; i++) {
          console.log(this.allCourses[i][0]);
          if (this.allCourses[i][0] !== course.innerHTML) {
            tableCourses += this.allCourses[i][0] + ",";
          }
        };

        tableCourses = tableCourses.replace(/,\s*$/, "")
   
        var customParams = {
            action: 'timeTable',
            TimeTableCourse: tableCourses,
            courseCompleted: params['courseCompleted'],
            program: params['program']
          }

        loadTimetableContent(customParams);      
      }

      elem.appendChild(course);
    }
  }
}

// CALLED IN TIMETABLE
function loadTimetableContent(customParams) {
  var timetable;
  var tableList;
  
  timetable = new Timetable('timetable');
  tableList = new TableList('alt-table', timetable);

  var timeTableInfo = ReadCookie("TimeTableInfo");


  if (!(typeof timeTableInfo === 'undefined' || timeTableInfo === null)) {
    var page = DB_CONNECTION_URL
    
    if (typeof customParams === 'undefined' || customParams === null) {
      params = timeTableInfo
    } else {
      params = customParams;
    }

    // request 
    AJAXRequest( function(response) {
        // alert(response);
        console.log(response);
        var json = JSON.parse(response);

        // add courses to sidebar
        listSelectedCourses('selected-courses', 'selected-course', json[0][0], 
                           params, page);

        // add timetables to sidebar
        storeTables(json[0][0], json[1], tableList);

        // display message banner
        displayBannerMessage('messageBanner', json[2]);

        // add courses to sidebar
        addCourses('add-course', json[3]);

        // add electives to sidebar
        addElectives('add-elective', json[4]);

        // setting the first table
        var firstTable = json[1][0];
        setTable(timetable, firstTable);
      
    }, page, params);
  }
}


/**
 *
 *
 *
 */
function displayBannerMessage(id, message) {

  if (message != '') {
    var banner = document.getElementById(id);
    banner.className = "displayBanner";
    banner.innerHTML = "Notice: " + message;
  }
}


/**
 * addElectives
 *
 * add list of electives to id and
 * display pop-up when user clicks
 * on the id.
 *
 * param id: object to add electives to
 * param electives: list of electives
 */

function addElectives(id, electives, callback) {

  var elem = document.getElementById(id);
  var header = "Select Elective";
  var subheader = "Please select the elective that you wish to add to your timetable";

  addPopupToElement(elem, header, subheader, electives, callback);
}

function addCourses(id, courses, callback) {

  var elem = document.getElementById(id);
  var header = "Select Course";
  var subheader = "Please select the course that you wish to add to your timetable";

  addPopupToElement(elem, header, subheader, courses, callback);
}



/**
 * addPopupToElement
 *
 * add popup with callback to a given element
 *
 */

function addPopupToElement(elem, headerText, subheaderText, listOfItems, callback) {

  elem.onclick = function() {

    var content = document.getElementById('light');
    content.innerHTML = ""; // reset content
    content.style.display='block';
    document.getElementById('fade').style.display='block';

    var header = document.createElement("div");
    header.className = "popupHeader";
    header.innerHTML = headerText;

    var subheader = document.createElement("div");
    subheader.className = "popupSubHeader";
    subheader.innerHTML = subheaderText;

    var electives = document.createElement("div");
    electives.className = "electivesList";

    // var items = elem.items;
    // console.log(elem.items);

    for (var i = listOfItems.length - 1; i >= 0; i--) {

      if (listOfItems[i].length < 2) continue; // ignore empty

      var item = document.createElement("div");
      item.className = "elective";
      var course = listOfItems[i];
      item.name = course[0];
      item.innerHTML = item.name + " (" + course[1] + ")";

      item.onclick = function() {

        // update elective
        body = course;
        
        if (callback != undefined) {
          callback(body);
        }

        // close pop-up
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
      }

      electives.appendChild(item);
    }

    var closeButton = document.createElement("div");
    closeButton.className = "closeButton";
    closeButton.innerHTML = "Close";
    closeButton.onclick = function() {
      document.getElementById('light').style.display = 'none';
      document.getElementById('fade').style.display = 'none';
    }

    // append elements
    content.appendChild(header);
    content.appendChild(subheader);
    content.appendChild(electives);
    content.appendChild(closeButton);
  }
}
