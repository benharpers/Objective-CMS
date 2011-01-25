var search_handler_request, isBusy = 0, oldval = null;

document.onkeydown = function(e) { if (e.keyCode == 224) isBusy = 1; }
document.onkeyup = function(e) { if (e.keyCode == 224) isBusy = 0; }

function search_handler_get(theevent,thenode,themodel) {
	var keyCode = theevent.which ? theevent.which : window.event.keyCode;
	if (keyCode == 8) return clearThisSearch(themodel);
	if (keyCode < 32 || keyCode > 192 || isBusy == 1) return;
	search_handler_request = httpObj();
	if (search_handler_request == null) {
		alert ("Your browser does not support AJAX!");
	} else {
		isBusy = 1;
		search_handler_request.nodelist = document.getElementById('search'+themodel);
		search_handler_request.node = thenode;
		search_handler_request.model = themodel;
		search_handler_request.onreadystatechange = search_handler_update;
		oldval = thenode.selectionStart > 0 ? thenode.value.substring(0,thenode.selectionStart) : thenode.value;
		search_handler_request.open("GET","index?action=find&find=search&search="+oldval+"&model="+themodel+"&x="+Math.random(),true);
		search_handler_request.send(null);
	}
	return false;
}

function search_handler_update() {
	if (this.readyState == 4) {
		var newnode;
		isBusy = 0;
		while (this.nodelist.childNodes.length) this.nodelist.removeChild(this.nodelist.lastChild);
		if (this.responseText) {
			var thelist = this.responseText.split("\x15");
			if (thelist.length > 1) {
				for (x=0; x<thelist.length; x++) {
					if ((x == 0 && thelist[0] == '-1') || thelist[x].length < 1) continue;
					newnode = document.createElementNS ? document.createElementNS("http://www.w3.org/1999/xhtml", "li") : document.createElement("li");
					newnode.setAttribute("onmousedown","selectSearchItem(this,'"+this.model+"'); return false;");
					newnode.appendChild(document.createTextNode(thelist[x]));
					if (x == 0) newnode.setAttribute('class','selected');
					this.nodelist.appendChild(newnode);
				}
				if (thelist[0] != -1) {
					this.node.value = thelist[0]+' ';
					this.node.selectionStart = 0;
					this.node.selectionEnd = this.node.value.length-1;
					this.node.selectionStart = oldval.length;
				}
				return true;
			}
		}
	}
	return false;
}

function clearThisSearch(themodel) {
	clearSearchList(document.getElementById('search'+themodel),themodel);
}

function clearSearchList(thelist,themodel) {
	while (thelist.childNodes.length) thelist.removeChild(thelist.lastChild);
}

function selectSearchItem(thenode,themodel) {
	var thefield = document.getElementById('input'+themodel);
	if (thefield) {
		thefield.value = thenode.innerHTML;
		thefield.focus();
		clearThisSearch(themodel);
	}
	return false;
}