/**
 * course Class
 *
 * representation of a single course
 */

function course (obj, courseName, courseNumber, courseYear, courseTerm, htmlBody, isPrerequisite, listOfElectives) {

  var name     = courseName,
  		number   = courseNumber,
  		body     = htmlBody,
  		element  = obj,
  		selected = false
      isPrereq = isPrerequisite;

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

      if (isPrereq && isSelected()) {
        // add code to show electives popup
        document.getElementById('light').style.display='block';
        document.getElementById('fade').style.display='block';

        // add innerHTML for list of electives
      }
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

  function isSelected() {
  	return selected;
  }

  function getName() {
    return name;
  }

  function getNumber() {
    return courseNumber;
  }

  // public methods
  this.init = init;
  this.getName = getName;
  this.getNumber = getNumber;
  this.getJSON = getJSON;
  this.toggleSelection = toggleSelection;
  this.select = select;
  this.deselect = deselect;
  this.isSelected = isSelected;

  this.init(); // initialize
}