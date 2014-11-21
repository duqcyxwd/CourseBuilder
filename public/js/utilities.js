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
