/**
 * $Id: adapter.js 371 2007-11-10 20:09:06Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 *
 * This file contains all adapter logic needed to use jQuery as the base API for TinyMCE.
 */

// #if jquery_adapter

(function() {
	var is = tinymce.is;

	if (!window.jQuery)
		return alert("Load jQuery first!");

	// Patch in core NS functions
	tinymce.extend = $.extend;
	tinymce.extend(tinymce, {
		trim : $.trim,
		map : $.map,
		grep : function(a, f) {return $.grep(a, f || function(){return 1;});},
		inArray : function(a, v) {return $.inArray(v, a || []);}
	});

	// Patch in functions in various clases
	// Add a "#if !jquery" statement around each core API function you add below
	var patches = {
		'tinymce.dom.DOMUtils' : {
			hasClass : function(n, c) {
				if (is(n, 'string'))
					return $('#' + n).hasClass(c);

				return $(n).hasClass(c);
			},

			select : function(p, c) {
				return $(p, this.get(c) || this.doc);
			},
		},
	};

	// Patch functions after a class is created
	tinymce.onCreate = function(ty, c, p) {
		tinymce.extend(p, patches[c]);
	};
})();

// #endif
