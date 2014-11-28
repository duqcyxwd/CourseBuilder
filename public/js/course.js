/**
 * course Class
 *
 * representation of a single course
 */

function course (obj, courseName, courseNumber, courseYear, courseTerm, htmlBody, isElective, electives) {

  var name     = courseName,
  		number   = courseNumber,
  		body     = htmlBody,
  		element  = obj,
      selected = false,
      isElect  = false,
      year     = courseYear,
      term     = courseTerm,
      termNum  = 0,
      listOfElectives = [];


  /**
   * init
   *
   * initialize course object
   */

  function init() {
  	// append innerText to element
  	element.innerHTML = body;
  	addListener();
    setTermNumber();

    if (isElective)
      isElect = true;

    for (var i = electives.length - 1; i >= 0; i--) {
      if (electives[i][0] == number) {
        listOfElectives.push(electives[i][1]);
      }
    };
  }


  /**
   * setTermNumber
   *
   * set the term value
   */

  function setTermNumber() {
    if (term == 'FALL') {
      termNum = year * 2 - 2;
    } else { // WINTER
      termNum = year * 2 - 1;
    }
  }


  /**
   * addListener
   *
   * add onclick function to course item.
   * Selects course when clicked
   */

  function addListener() {
		element.onclick = function() {
	    toggleSelection();

      if (isElect && isSelected()) {
        displayElectivesList();
      }
	  }
  }


  /**
   * displayElectivesList
   *
   * if this course object is an elective, a
   * list of electives pop-up will appear
   * allowing the user to select an elective.
   */

  function displayElectivesList() { 

    var content = document.getElementById('light');
    content.innerHTML = ""; // reset content
    content.style.display='block';
    document.getElementById('fade').style.display='block';

    var header = document.createElement("div");
    header.className = "popupHeader";
    header.innerHTML = "Select Elective";

    var subheader = document.createElement("div");
    subheader.className = "popupSubHeader";
    subheader.innerHTML = "Please select the elective that you've completed";

    var electives = document.createElement("div");
    electives.className = "electivesList";

    for (var i = listOfElectives.length - 1; i >= 0; i--) {
      var item = document.createElement("div");
      item.className = "elective";
      var course = listOfElectives[i].split(" ");
      item.name = course[0];
      item.number = course[1];
      item.innerHTML = listOfElectives[i]; // TODO: may add description in the future

      item.onclick = function() {

        // update elective
        body = this.name + " " + this.number;
        updateCourse(this.name, this.number, body);

        // close pop-up
        document.getElementById('light').style.display = 'none';
        document.getElementById('fade').style.display = 'none';
      }

      electives.appendChild(item);
    };

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


  /**
   * getJSON
   *
   * get the course information in JSON format
   */

  function getJSON() {
    return {"coursename" : name, "coursenumber" : number };
  }


  /**
   * toggleSelection
   *
   * select or deselect the course based
   * on it's current state
   */
  function toggleSelection() {
  	toggleClass(element, 'highlight');
  	toggleClass(element, 'active');
  	selected = (selected) ? false : true;
  }


  /**
   * select
   *
   * set the item to selected
   */
  function select() {
  	if (selected == false)
  		toggleSelection();
  }


  /**
   * deselect
   *
   * deselect the courses item
   */
  function deselect() {
  	if (selected == true)
  		toggleSelection();
  }


  /**
   * isSelected
   *
   * return true if selected, 
   *        false otherwise
   */
  function isSelected() {
  	return selected;
  }


  /**
   * getName
   *
   * return the course name
   */

  function getName() {
    return name;
  }


  /**
   * getNumber
   *
   * return the course number
   */
  function getNumber() {
    return number;
  }


  /**
   * getTermNumber
   *
   * return the term number
   */
  function getTermNumber() {
    return termNum;
  }


  /**
   * updateCourse
   *
   * update the course details and layout
   * param newName: new course name
   * param newNumber: new coures number
   * param body: HTML to appear inside course obj
   */
  function updateCourse(newName, newNumber, body) {
    name = newName;
    number = newNumber;
    element.innerHTML = body;
  }

  // public methods
  this.init = init;
  this.getName = getName;
  this.getNumber = getNumber;
  this.getTermNumber = getTermNumber;
  this.getJSON = getJSON;
  this.toggleSelection = toggleSelection;
  this.select = select;
  this.deselect = deselect;
  this.isSelected = isSelected;

  this.init(); // initialize
}
