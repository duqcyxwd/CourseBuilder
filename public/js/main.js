function toggleClass(element, className){
    if (!element || !className){
        return;
    }

    var classString = element.className, 
    		nameIndex = classString.indexOf(className);

    if (nameIndex == -1) {
        classString += ' ' + className;
    }
    else {
        classString = classString.substr(0, nameIndex) + classString.substr(nameIndex+className.length);
    }
    element.className = classString;
}

function selectProgram(obj) {
	toggleClass(obj, 'active');
}

function something(obj) {
    console.log(obj);
}