/**
 * course Class
 *
 * representation of a single course
 */
 
function course (obj, courseName, courseNumber, courseYear, courseTerm, htmlBody) {

  var name     = courseName,
  		number   = courseNumber,
  		body     = htmlBody,
  		element  = obj,
  		selected = false;

  this.year = courseYear;
  this.term = courseTerm;


  function init() {
  	// append innerText to element
  	element.innerHTML = body;
  	addListener();
  }


  function addListener() {
		element.onclick = function() {
	    toggleSelection();
	  }
  }


  function getJSON() {
    return {"coursename" : name, "coursenumber" : number };
  }


  function toggleSelection() {
  	toggleClass(element, 'highlight');
  	toggleClass(element, 'active');
  	selected = (selected) ? false : true;
  }


  function select() {
  	if (selected == false)
  		toggleSelection();
  }


  function deselect() {
  	if (selected == true)
  		toggleSelection();
  }

  // public methods
  this.init = init;
  this.getJSON = getJSON;
  this.toggleSelection = toggleSelection;
  this.select = select;
  this.deselect = deselect;

  this.init(); // initialize
}