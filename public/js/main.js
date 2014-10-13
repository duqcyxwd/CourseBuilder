var CONSTANT_YEARS = 4;

function toggleClass(element, className){
  if (!element || !className) { return; }

  var classString = element.className, 
  	nameIndex   = classString.indexOf(className);

  if (nameIndex == -1) {
    classString += ' ' + className;
  } else {
    classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length);
  }
  element.className = classString;
}

function selectProgram(obj) {
	toggleClass(obj, 'active');
}

function objectToParameters(obj) {
  var retval = "";
  for (var key in obj)
    retval += key + "=" + obj[key] + "&";
  return retval.slice(0, -1);
}

// general AJAX Request
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

function generatePrerequisiteTable(tableId, prog) {
  var params = { action: "prereqTree", program: prog };
  var page = "../resources/library/dbConnection.php";

  AJAXRequest( function(result) {
    var retval = calculateTable(result);
    generate_table('course-table', retval);
    // document.getElementById(tableId).innerHTML = retval;
  }, page, params);

}

function calculateTable(result) {

  var json = JSON.parse(result);
  var tr, td;
  var body = document.createElement('tbody');

  var max = 0;
  json.forEach(function(obj) { 
    max = (max < obj.length) ? obj.length : max; 
  });
  console.log(json);

  for (var i = 0; i < max; i++) {
    tr = document.createElement('tr');
    for (var j = 0; j < json.length; j++) {
      td = document.createElement('td');
      td.innerHTML = (json[i][j]) ? json[i][j] : "ELECTIVE";
      tr.appendChild(td);
    };
    body.appendChild(tr);
  };

  body.id = "course-classes-table";
  return body;
}


function generate_table(tableId, bodyHTML) {

  var i, term, th, th2, body;
  var table = document.createElement('table');
  var header = table.createTHead();
  var row = header.insertRow(0);
  row.id = "course-header-year";

  for (i = 1; i <= CONSTANT_YEARS; i++) {
    th = document.createElement('th');
    th.innerHTML = "YEAR " + i;
    th.colSpan = '2';
    row.appendChild(th);
  };

  row = header.insertRow(1);
  row.id = "course-header-term";

  for (i = 0; i < CONSTANT_YEARS * 2; i++) {
    term = (i % 2 == 0) ? "FALL" : "WINTER";
    th = document.createElement('th');
    th.innerHTML = term;
    row.appendChild(th);
  }

  table.appendChild(bodyHTML);
  table.id = "schedule-table";

  document.getElementById(tableId).innerHTML = "";
  document.getElementById(tableId).appendChild(table);
}

// EventListeners
window.onload = function() {
  var dropdownMenu = document.getElementsByClassName("gen-tree");
  for (var i = 0; i < dropdownMenu.length; i++) {
    dropdownMenu[i].onclick = function() {
      generatePrerequisiteTable('course-classes-table', this.innerHTML);
    }
  }
}