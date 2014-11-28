/**
 * getDBText
 *
 *
 *
 */
function getDBText(dbTableName, id) {

	var page = '../' + DB_CONNECTION_URL;

	var params = {
		action: 'getTableData',
		dbTable: dbTableName
	}

	AJAXRequest(function(response) {

		var text = document.getElementById(id);
		text.value = response;

	}, page, params);
}


/**
 * setDBData
 *
 *
 */
function setDBData(dbTableName, data) {

	var page = '../' + DB_CONNECTION_URL;

	var params = {
		action: 'updateDB',
		dbTable: dbTableName,
		dbData: data
	}

	AJAXRequest(function(response) {

		displayBannerMessage('messageBanner', response);

	}, page, params);
}

window.onload = function() {

	var selected = '';

	var tabs = document.getElementsByClassName('db-tabs');
	for (var i = 0; i < tabs.length; i++) {
		tabs[i].onclick = function() {
			getDBText(this.innerHTML, 'db-text');
			selected = this.innerHTML;
		}
	};

	var submitBtn = document.getElementById('db-submit');
	submitBtn.onclick = function() {
		var text = document.getElementById('db-text').value;
		setDBData(selected, text)
	}
}