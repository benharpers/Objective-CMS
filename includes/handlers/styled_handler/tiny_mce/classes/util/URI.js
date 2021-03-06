/**
 * $Id: URI.js 366 2007-11-09 17:45:54Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright � 2004-2007, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	var each = tinymce.each;

	/**#@+
	 * @class This class handles parsing, modification and serialization of URI/URL strings.
	 * @member tinymce.util.URI
	 */
	tinymce.create('tinymce.util.URI', {
		/**
		 * Constucts a new URI instance.
		 *
		 * @constructor
		 * @param {String} u URI string to parse.
		 * @param {Object} s Optional settings object.
		 */
		URI : function(u, s) {
			var t = this, o, a, b;

			// Default settings
			s = t.settings = s || {};

			// Strange app protocol or local anchor
			if (/^(mailto|news|javascript|about):/i.test(u) || /^\s*#/.test(u)) {
				t.source = u;
				return;
			}

			// Absolute path with no host, fake host and protocol
			if (u.indexOf('/') === 0 && u.indexOf('//') !== 0)
				u = (s.base_uri ? s.base_uri.protocol || 'http' : 'http') + '://mce_host' + u;

			// Relative path
			if (u.indexOf('://') === -1 && u.indexOf('//') !== 0)
				u = (s.base_uri.protocol || 'http') + '://mce_host' + t.toAbsPath(s.base_uri.path, u);

			// Parse URL (Credits goes to Steave, http://blog.stevenlevithan.com/archives/parseuri)
			u = /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/.exec(u);
			each(["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"], function(v, i) {
				t[v] = u[i];
			});

			if (b = s.base_uri) {
				if (!t.protocol)
					t.protocol = b.protocol;

				if (!t.userInfo)
					t.userInfo = b.userInfo;

				if (!t.host || t.host == 'mce_host')
					t.host = b.host;

				if (!t.port)
					t.port = b.port;

				t.source = '';
			}

			//t.path = t.path || '/';
		},

		/**#@+
		 * @method
		 */

		/**
		 * Sets the internal path part of the URI.
		 *
		 * @param {string} p Path string to set.
		 */
		setPath : function(p) {
			var t = this;

			p = /^(.*?)\/?(\w+)?$/.exec(p);

			// Update path parts
			t.path = p[0];
			t.directory = p[1];
			t.file = p[2];

			// Rebuild source
			t.source = '';
			t.getURI();
		},

		/**
		 * Converts the specified URI into a relative URI based on the current URI instance location.
		 *
		 * @param {String} u URI to convert into a relative path/URI.
		 * @return {String} Relative URI from the point specified in the current URI instance.
		 */
		toRelative : function(u) {
			var t = this, o;

			u = new tinymce.util.URI(u, {base_uri : t});

			// Not on same domain/port or protocol
			if ((u.host != 'mce_host' && t.host != u.host && u.host) || t.port != u.port || t.protocol != u.protocol)
				return u.getURI();

			o = t.toRelPath(t.path, u.path);

			// Add query
			if (u.query)
				o += '?' + u.query;

			// Add anchor
			if (u.anchor)
				o += '#' + u.anchor;

			return o;
		},
	
		/**
		 * Converts the specified URI into a absolute URI based on the current URI instance location.
		 *
		 * @param {String} u URI to convert into a relative path/URI.
		 * @param {bool} nh No host and protocol prefix.
		 * @return {String} Absolute URI from the point specified in the current URI instance.
		 */
		toAbsolute : function(u, nh) {
			var u = new tinymce.util.URI(u, {base_uri : this});

			return u.getURI(this.host == u.host ? nh : 0);
		},

		/**
		 * Converts a absolute path into a relative path.
		 *
		 * @param {String} base Base point to convert the path from.
		 * @param {String} path Absolute path to convert into a relative path.
		 */
		toRelPath : function(base, path) {
			var items, bp = 0, out = '', i;

			// Split the paths
			base = base.substring(0, base.lastIndexOf('/'));
			base = base.split('/');
			items = path.split('/');

			if (base.length >= items.length) {
				for (i = 0; i < base.length; i++) {
					if (i >= items.length || base[i] != items[i]) {
						bp = i + 1;
						break;
					}
				}
			}

			if (base.length < items.length) {
				for (i = 0; i < items.length; i++) {
					if (i >= base.length || base[i] != items[i]) {
						bp = i + 1;
						break;
					}
				}
			}

			if (bp == 1)
				return path;

			for (i = 0; i < base.length - (bp - 1); i++)
				out += "../";

			for (i = bp - 1; i < items.length; i++) {
				if (i != bp - 1)
					out += "/" + items[i];
				else
					out += items[i];
			}

			return out;
		},

		/**
		 * Converts a relative path into a absolute path.
		 *
		 * @param {String} base Base point to convert the path from.
		 * @param {String} path Relative path to convert into an absolute path.
		 */
		toAbsPath : function(base, path) {
			var i, nb = 0, o = [];

			// Split paths
			base = base.split('/');
			path = path.split('/');

			// Remove empty chunks
			each(base, function(k) {
				if (k)
					o.push(k);
			});

			base = o;

			// Merge relURLParts chunks
			for (i = path.length - 1, o = []; i >= 0; i--) {
				// Ignore empty or .
				if (path[i].length == 0 || path[i] == ".")
					continue;

				// Is parent
				if (path[i] == '..') {
					nb++;
					continue;
				}

				// Move up
				if (nb > 0) {
					nb--;
					continue;
				}

				o.push(path[i]);
			}

			i = base.length - nb;

			// If /a/b/c or /
			if (i <= 0)
				return '/' + o.reverse().join('/');

			return '/' + base.slice(0, i).join('/') + '/' + o.reverse().join('/');
		},

		/**
		 * Returns the full URI of the internal structure.
		 *
		 * @param {bool} nh Optional no host and protocol part. Defaults to false.
		 */
		getURI : function(nh) {
			var s, t = this;

			// Rebuild source
			if (!t.source || nh) {
				s = '';

				if (!nh) {
					if (t.protocol)
						s += t.protocol + '://';

					if (t.userInfo)
						s += t.userInfo + '@';

					if (t.host)
						s += t.host;

					if (t.port)
						s += ':' + t.port;
				}

				if (t.path)
					s += t.path;

				if (t.query)
					s += '?' + t.query;

				if (t.anchor)
					s += '#' + t.anchor;

				t.source = s;
			}

			return t.source;
		}

		/**#@-*/
	});
})();
