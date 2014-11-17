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
