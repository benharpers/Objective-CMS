var filesets_proto = {
	filesets : new Array,
	fileset_proto : {
		id : null, parent : this, div : null, http : httpObj(), count : 0,
		request : function() {
			this.http.parent = this;
			this.http.onreadystatechange = this.http.parent.result;
			this.http.open("GET","admin/index?action=get&get=fileset&fileset="+this.id+"&x="+Math.random(),true);
			this.http.send(null);
		},
		result : function() {
			if (this.readyState == 4) {
				while (this.parent.table.rows.length>1) {
					this.parent.table.removeChild(this.parent.table.rows[this.parent.table.rows.length-1]);
				}
				var thefiles = eval(this.responseText);
				this.parent.count = 0;
				for (thefile in thefiles) {
					var trow = this.parent.add_item(thefiles[thefile]);
					if (trow) this.parent.table.appendChild(trow);
				}
			}
		},
		add_item : function(thefile) {
			if (!thefile.file_id) return false;
			var trow = document.createElement("tr");
			var ticon = document.createElement("td");
			var ticon_img = document.createElement("img");
			var tinfo = document.createElement("td");
			var tsortup = document.createElement("td");
			var tsortup_img = document.createElement("img");
			var tsortdown = document.createElement("td");
			var tsortdown_img = document.createElement("img");
			var tremove = document.createElement("td");
			var tremove_img = document.createElement("img");
			var ttype = document.createElement("td");
			var tdesc = document.createElement("td");
			trow.id = thefile.file_id;
			this.count++;
			trow.className = (Math.round(this.count/2) != (this.count/2)) ? 'even' : 'odd';
			ticon.className = 'icon preview';
			ticon.style.backgroundImage = "url('../index?action=get&get=image&image="+thefile.file_id+"&width=16&height=16')";
			ticon_img.src = '../images/icon_spacer.png';
			tinfo.innerHTML = unescape(thefile.name);
			if (thefile['default'] == 1) tinfo.style['fontWeight'] = "bolder";
			tinfo.className = 'file_name';
			tinfo.onclick = function() { file_edit(this.parentNode); }
			tsortup.className = 'sort';
			tsortup_img.onclick = function() { file_sort(this.parentNode.parentNode,1); }
			tsortup_img.className = 'icon sort_up';
			tsortup_img.src = '../images/icon_spacer.png';
			tsortup_img.alt = 'Move Up';
			tsortdown.className = 'sort';
			tsortdown_img.onclick = function() { file_sort(this.parentNode.parentNode,2); }
			tsortdown_img.className = 'icon sort_down';
			tsortdown_img.src = '../images/icon_spacer.png';
			tsortdown_img.alt = 'Move Down';
			tremove.className = 'actions';
			tremove_img.onclick = function() { file_remove(this.parentNode.parentNode); }
			tremove_img.className = 'icon remove';
			tremove_img.src = '../images/icon_spacer.png';
			tremove_img.alt = 'Remove';
			ttype.className = 'type';
			ttype.innerHTML = unescape(thefile.type);
			tdesc.className = 'desc';
			tdesc.innerHTML = unescape(thefile.description);
			trow.appendChild(ticon);
			ticon.appendChild(ticon_img);
			trow.appendChild(tinfo);
			trow.appendChild(tsortup);
			tsortup.appendChild(tsortup_img);
			trow.appendChild(tsortdown);
			tsortdown.appendChild(tsortdown_img);
			trow.appendChild(tremove);
			tremove.appendChild(tremove_img);
			trow.appendChild(ttype);
			trow.appendChild(tdesc);
			return trow;
		}
	},
	init : function(group_id) {
		this.filesets[group_id] = new fileset;
		this.filesets[group_id].prototype = this.fileset_proto;
		this.filesets[group_id].prototype.id = group_id;
		this.filesets[group_id].prototype.table = document.getElementById('fileset'+group_id);
		this.filesets[group_id].prototype.parent = this;
		this.filesets[group_id].prototype.request();
		this.filesets[group_id].prototype.table.id = group_id
	},
	update : function(group_id) {
		this.filesets[group_id].request();
	}
}

function filesets() { }
filesets.prototype = filesets_proto;

function fileset() { }
fileset.prototype = filesets_proto.fileset_proto;

var filesets = new filesets;

function file_sort(item,direction) {
	var http_request = httpObj();
	http_request.item = item;
	http_request.onreadystatechange = function() { if (this.readyState == 4 && this.responseText != -1) filesets.filesets[this.responseText].request(); return false; }
	http_request.open("GET","admin/index?group_id="+item.parentNode.id+"&file_id="+item.id+"&action=sort&sort=fileset&fileset="+((direction == 1) ? 'up' : 'down')+"&"+Math.random(),true);
	http_request.send(null);
}

function file_remove(item) {
	var http_request = httpObj();
	http_request.item = item;
	http_request.onreadystatechange = function() { if (this.readyState == 4 && this.responseText != -1) filesets.filesets[this.responseText].request(); return false; }
	http_request.open("GET","admin/index?group_id="+item.parentNode.id+"&fileset="+item.id+"&action=remove&remove=fileset&"+Math.random(),true);
	http_request.send(null);
}

function file_edit(item) {
	return popup_editor('../edit/fileset_file_info?group_id='+item.parentNode.id+"&file_id="+item.id);
}

function fileset_update(group_id) {
	filesets.update(group_id);
	return true;
}

function go_reload() {
	window.location.href += '';
}