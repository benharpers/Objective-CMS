var minSize = 995;

if (window.innerWidth < minSize || window.outerHeight<(window.outerWidth/2)) {
	var winSize = { 'width':window.innerWidth, 'height':window.outerHeight };
	if (winSize.width < minSize) winSize.width = minSize+(winSize.width-window.innerWidth);
	if (winSize.height<(winSize.width/2)) winSize.height = Math.round(winSize.width/2);
	window.resizeTo(winSize.width, winSize.height);
}

var checkboxes = getElementsByClass('checkbox','label');
for (x=0; x<checkboxes.length; x++) {
	checkboxes[x].onclick = checkboxes[x].firstChild.click;
}

function href(url) {
	return (document.location.href = url) ? true : false;
}

function getElementsByClass(className, tagName) {
	var elements = document.getElementsByTagName(tagName ? tagName : '*');
	var matches = new Array;
	var y = 0;
	for (x=0; x < elements.length; x++) {
		if (elements[x].className && elements[x].className == className) {
			matches[y] = elements[x]; ++y;
		}
	}
	return matches;
}

function httpObj() {
	var theRequest = null;
	try {
		theRequest = new XMLHttpRequest();  // Firefox, Opera 8.0+, Safari
	} catch (e) {
		try {
			theRequest = new ActiveXObject("Msxml2.XMLHTTP");  // Internet Explorer 6+
		} catch (e) {
			theRequest = new ActiveXObject("Microsoft.XMLHTTP");  // Internet Explorer 5.5
		}
	}
	return theRequest;
}

Object.prototype.nextObject = function() {
	var n = this;
	do n = n.nextSibling;
	while (n && n.nodeType != 1);
	return n;
}

Object.prototype.previousObject = function() {
	var p = this;
	do p = p.previousSibling;
	while (p && p.nodeType != 1);
	return p;
}

Object.prototype.sendTo = function(url) {
	if (!((typeof url).toString().match(/string/i))) return;
	this.action = url;
	if (this.redirect) this.redirect.parentNode.removeChild(this.redirect);
	this.submit();
}

function popup_editor(popurl) {
	window.open(popurl,'popup_editor','width=420,height=290,scrollbars=0,status=0,location=0,bookmarks=0,toolbar=0,menubar=0,resizable=0,screenX='+((screen.width/2)-200)+',screenY='+((screen.height/2)-100)).moveTo(((screen.width/2)-200),((screen.height/2)-150));
	window.onunload = function() { if (popup_editor && popup_editor.close) popup_editor.close(); }
}