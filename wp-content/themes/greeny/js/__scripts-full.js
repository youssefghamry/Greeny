/* ATTENTION! This file was generated automatically! Don't change it!!!
----------------------------------------------------------------------- */
/**
 * Makes "skip to content" link work correctly in IE9, Chrome, and Opera
 * for better accessibility.
 *
 * @link http://www.nczonline.net/blog/2013/01/15/fixing-skip-to-content-links/
 */

( function() {
	"use strict";

	var ua = navigator.userAgent.toLowerCase();

	if ( ( ua.indexOf( 'webkit' ) > -1 || ua.indexOf( 'opera' ) > -1 || ua.indexOf( 'msie' ) > -1 ) &&
		document.getElementById && window.addEventListener ) {

		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.nodeName ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
} )();
/**
 * Javascript utilities
 *
 * @package GREENY
 * @since GREENY 1.0
 */

/* global jQuery:false */
/* global GREENY_STORAGE:false */

/* Global variables manipulations
---------------------------------------------------------------- */

(function(){
	"use strict";

	var $window   = jQuery( window ),
		$document = jQuery( document ),
		$adminbar = jQuery( '#wpadminbar' ),
		$body     = jQuery( 'body' );

	// Global variables storage
	if (typeof GREENY_STORAGE == 'undefined') {
		window.GREENY_STORAGE = {};
	}

	// Get global variable
	window.greeny_storage_get = function(var_name) {
		return greeny_isset( GREENY_STORAGE[var_name] ) ? GREENY_STORAGE[var_name] : '';
	};

	// Set global variable
	window.greeny_storage_set = function(var_name, value) {
		GREENY_STORAGE[var_name] = value;
	};

	// Inc/Dec global variable with specified value
	window.greeny_storage_inc = function(var_name) {
		var value                  = arguments[1] === undefined ? 1 : arguments[1];
		GREENY_STORAGE[var_name] += value;
	};

	// Concatenate global variable with specified value
	window.greeny_storage_concat = function(var_name, value) {
		GREENY_STORAGE[var_name] += '' + value;
	};

	// Get global array element
	window.greeny_storage_get_array = function(var_name, key) {
		return greeny_isset( GREENY_STORAGE[var_name][key] ) ? GREENY_STORAGE[var_name][key] : '';
	};

	// Set global array element
	window.greeny_storage_set_array = function(var_name, key, value) {
		if ( ! greeny_isset( GREENY_STORAGE[var_name] )) {
			GREENY_STORAGE[var_name] = {};
		}
		GREENY_STORAGE[var_name][key] = value;
	};

	// Inc/Dec global array element with specified value
	window.greeny_storage_inc_array = function(var_name, key) {
		var value                       = arguments[2] === undefined ? 1 : arguments[2];
		GREENY_STORAGE[var_name][key] += value;
	};

	// Concatenate global array element with specified value
	window.greeny_storage_concat_array = function(var_name, key, value) {
		GREENY_STORAGE[var_name][key] += '' + value;
	};

	/* PHP-style functions
	---------------------------------------------------------------- */
	window.greeny_isset = function(obj) {
		return typeof(obj) != 'undefined';
	};

	window.greeny_empty = function(obj) {
		return typeof(obj) == 'undefined' || (typeof(obj) == 'object' && obj === null) || (typeof(obj) == 'array' && obj.length === 0) || (typeof(obj) == 'string' && greeny_alltrim( obj ) === '') || obj === 0;
	};

	window.greeny_is_array = function(obj)  {
		return typeof(obj) == 'array';
	};

	window.greeny_is_object = function(obj)  {
		return typeof(obj) == 'object';
	};

	window.greeny_clone_object = function(obj) {
		if (obj === null || typeof(obj) != 'object') {
			return obj;
		}
		var temp = {};
		for (var key in obj) {
			temp[key] = greeny_clone_object( obj[key] );
		}
		return temp;
	};

	window.greeny_merge_objects = function(obj1, obj2)  {
		for (var i in obj2) {
			obj1[i] = obj2[i];
		}
		return obj1;
	};

	window.greeny_array_merge = function(a1, a2) {
		for (var i in a2) {
			a1[i] = a2[i];
		}
		return a1;
	};

	window.greeny_array_first_key = function(arr) {
		var rez = null;
		for (var i in arr) {
			rez = i;
			break;
		}
		return rez;
	};

	window.greeny_array_first_value = function(arr) {
		var rez = null;
		for (var i in arr) {
			rez = arr[i];
			break;
		}
		return rez;
	};

	// Generates a storable representation of a value
	window.greeny_serialize = function(mixed_val) {
		var obj_to_array = arguments.length == 1 || argument[1] === true;

		switch (typeof(mixed_val)) {

			case "number":
				if (isNaN( mixed_val ) || ! isFinite( mixed_val )) {
					return false;
				} else {
					return (Math.floor( mixed_val ) == mixed_val ? "i" : "d") + ":" + mixed_val + ";";
				}

			case "string":
				return "s:" + mixed_val.length + ":\"" + mixed_val + "\";";

			case "boolean":
				return "b:" + (mixed_val ? "1" : "0") + ";";

			case "object":
				if (mixed_val == null) {
					return "N;";
				} else if (mixed_val instanceof Array) {
					var idxobj = { idx: -1 };
					var map    = [];
					for (var i = 0; i < mixed_val.length; i++) {
						idxobj.idx++;
						var ser = greeny_serialize( mixed_val[i] );
						if (ser) {
							map.push( greeny_serialize( idxobj.idx ) + ser );
						}
					}
					return "a:" + mixed_val.length + ":{" + map.join( "" ) + "}";
				} else {
					var class_name = greeny_get_class( mixed_val );
					if (class_name == undefined) {
						return false;
					}
					var props = new Array();
					for (var prop in mixed_val) {
						var ser = greeny_serialize( mixed_val[prop] );
						if (ser) {
							props.push( greeny_serialize( prop ) + ser );
						}
					}
					if (obj_to_array) {
						return "a:" + props.length + ":{" + props.join( "" ) + "}";
					} else {
						return "O:" + class_name.length + ":\"" + class_name + "\":" + props.length + ":{" + props.join( "" ) + "}";
					}
				}

			case "undefined":
				return "N;";
		}
		return false;
	};

	// Encode / Decode Unicode string to / from single-byte characters
	( function( $ ) {
		var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
		a256 = '',
		r64 = [256],
		r256 = [256],
		i = 0;

		var UTF8 = {

			/**
			 * Encode multi-byte Unicode string into utf-8 multiple single-byte characters
			 * (BMP / basic multilingual plane only)
			 *
			 * Chars in range U+0080 - U+07FF are encoded in 2 chars, U+0800 - U+FFFF in 3 chars
			 *
			 * @param {String} strUni Unicode string to be encoded as UTF-8
			 * @returns {String} encoded string
			 */
			encode: function(strUni) {
				// use regular expressions & String.replace callback function for better efficiency than procedural approaches
				var strUtf = strUni
								.replace(
									/[\u0080-\u07ff]/g, // U+0080 - U+07FF => 2 bytes 110yyyyy, 10zzzzzz
									function(c) {
										var cc = c.charCodeAt(0);
										return String.fromCharCode(0xc0 | cc >> 6, 0x80 | cc & 0x3f);
									}
								)
								.replace(
									/[\u0800-\uffff]/g, // U+0800 - U+FFFF => 3 bytes 1110xxxx, 10yyyyyy, 10zzzzzz
									function(c) {
										var cc = c.charCodeAt(0);
										return String.fromCharCode(0xe0 | cc >> 12, 0x80 | cc >> 6 & 0x3F, 0x80 | cc & 0x3f);
									}
								);
				return strUtf;
			},

			/**
			* Decode utf-8 encoded string back into multi-byte Unicode characters
			*
			* @param {String} strUtf UTF-8 string to be decoded back to Unicode
			* @returns {String} decoded string
			*/
			decode: function(strUtf) {
				// note: decode 3-byte chars first as decoded 2-byte strings could appear to be 3-byte char!
				var strUni = strUtf
								.replace(
									/[\u00e0-\u00ef][\u0080-\u00bf][\u0080-\u00bf]/g, // 3-byte chars
									function(c) { // (note parentheses for precence)
										var cc = ((c.charCodeAt(0) & 0x0f) << 12) | ((c.charCodeAt(1) & 0x3f) << 6) | (c.charCodeAt(2) & 0x3f);
										return String.fromCharCode(cc);
									}
								)
								.replace(
									/[\u00c0-\u00df][\u0080-\u00bf]/g, // 2-byte chars
									function(c) { // (note parentheses for precence)
										var cc = (c.charCodeAt(0) & 0x1f) << 6 | c.charCodeAt(1) & 0x3f;
										return String.fromCharCode(cc);
									}
								);
				return strUni;
			}
		};

		while( i < 256 ) {
			var c = String.fromCharCode(i);
			a256 += c;
			r256[i] = i;
			r64[i] = b64.indexOf(c);
			++i;
		}

		function code(s, discard, alpha, beta, w1, w2) {
			s = String(s);
			var buffer = 0,
				i = 0,
				length = s.length,
				result = '',
				bitsInBuffer = 0;

			while (i < length) {
				var c = s.charCodeAt(i);
				c = c < 256 ? alpha[c] : -1;

				buffer = (buffer << w1) + c;
				bitsInBuffer += w1;

				while (bitsInBuffer >= w2) {
					bitsInBuffer -= w2;
					var tmp = buffer >> bitsInBuffer;
					result += beta.charAt(tmp);
					buffer ^= tmp << bitsInBuffer;
				}
				++i;
			}
			if ( ! discard && bitsInBuffer > 0) {
				result += beta.charAt(buffer << (w2 - bitsInBuffer));
			}
			return result;
		}

		var Plugin = $.greeny_encoder = function(dir, input, encode) {
			return input ? Plugin[dir](input, encode) : dir ? null : this;
		};

		Plugin.btoa = Plugin.encode = function(plain, utf8encode) {
			plain = Plugin.raw === false || Plugin.utf8encode || utf8encode
						? UTF8.encode(plain)
						: plain;
			plain = code(plain, false, r256, b64, 8, 6);
			return plain + '===='.slice((plain.length % 4) || 4);
		};

		Plugin.atob = Plugin.decode = function(coded, utf8decode) {
			coded = String(coded).split('=');
			var i = coded.length;
			do {
				--i;
				coded[i] = code(coded[i], true, r64, a256, 6, 8);
			} while (i > 0);
			coded = coded.join('');
			return Plugin.raw === false || Plugin.utf8decode || utf8decode
					? UTF8.decode(coded)
					: coded;
		};
	}(jQuery) );

	// Returns the name of the class of an object
	window.greeny_get_class = function(obj) {
		if (obj instanceof Object && ! (obj instanceof Array) && ! (obj instanceof Function) && obj.constructor) {
			var arr = obj.constructor.toString().match( /function\s*(\w+)/ );
			if (arr && arr.length == 2) {
				return arr[1];
			}
		}
		return false;
	};


	/* Timing functions
	---------------------------------------------------------------- */
	window.greeny_debounce = function(func, wait) {
		var timeout;
		return function () {
			var context = this, args = arguments;
			var later = function later() {
				timeout = null;
				func.apply(context, args);
			};
			var callNow = !timeout;
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
			if (callNow) {
				func.apply(context, args);
			}
		};
	};

	window.greeny_throttle = function(func, wait, debounce) {
		var timeout;
		return function () {
			var context = this, args = arguments;
			var throttler = function () {
				timeout = null;
				func.apply(context, args);
			};
			if (debounce) clearTimeout(timeout);
			if (debounce || !timeout) timeout = setTimeout(throttler, wait);
		};
	};


	/* Mutation observers
	---------------------------------------------------------------- */
	var greeny_observers = {};

	// Create mutations observer
	window.greeny_create_observer = function( id, obj, callback, args ) {
		if ( typeof window.trx_addons_create_observer != 'undefined' ) {
			return trx_addons_create_observer( id, obj, callback, args );
		} else if ( typeof window.MutationObserver !== 'undefined' && obj.length > 0 ) {
			if ( typeof greeny_observers[ id ] == 'undefined' ) {
				var defa = {
						attributes: false,
						childList: true,
						subtree: true
					};
				if ( args ) {
					defa = greeny_object_merge( defa, args );
				}
				greeny_observers[ id ] = {
					observer: new MutationObserver( callback ),
					obj: obj.get(0)
				};
				greeny_observers[ id ].observer.observe( greeny_observers[ id ].obj, defa );
			}
			return true;
		}
		return false;
	};

	// Remove mutations observer
	window.greeny_remove_observer = function( id ) {
		if ( typeof window.trx_addons_remove_observer != 'undefined' ) {
			return trx_addons_remove_observer( id );
		} else if ( typeof window.MutationObserver !== 'undefined' ) {
			if ( typeof greeny_observers[ id ] !== 'undefined' ) {
				greeny_observers[ id ].observer.disconnect(
					greeny_observers[ id ].obj
				);
				delete greeny_observers[ id ];
			}
			return true;
		}
		return false;
	};


	/* Wordpress-style functions
	---------------------------------------------------------------- */

	var filters = {};

	// Add filter's handler
	window.greeny_add_filter = function( filter, callback, priority ) {
		if ( typeof window.trx_addons_add_filter != 'undefined' ) {
			trx_addons_add_filter( filter, callback, priority );
		} else if ( typeof wp != 'undefined' && typeof wp.hooks != 'undefined' ) {
			wp.hooks.addFilter( filter, 'greeny', callback, priority == undefined ? 10 : priority );
		} else {
			if ( ! filters[filter] ) filters[filter] = {};
			if ( ! filters[filter][priority] ) filters[filter][priority] = [];
			filters[filter][priority].push( callback );
		}
	};

	// Apply filter's handlers
	window.greeny_apply_filters = function( filter, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 ) {
		if ( typeof window.trx_addons_apply_filters != 'undefined' ) {
			arg1 = trx_addons_apply_filters( filter, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 );
		} else if ( typeof wp != 'undefined' && typeof wp.hooks != 'undefined' && typeof wp.hooks.applyFilters != 'undefined' ) {
			arg1 = wp.hooks.applyFilters( filter, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 );
		} else if ( typeof filters[filter] == 'object' ) {
			var keys = Object.keys(filters[filter]).sort();
			for (var i=0; i < keys.length; i++ ) {
				for (var j=0; j < filters[filter][keys[i]].length; j++ ) {
					if ( typeof filters[filter][keys[i]][j] == 'function' ) {
						arg1 = filters[filter][keys[i]][j](arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9);
					}
				}
			}
		}
		return arg1;
	};

	// Add action's handler
	window.greeny_add_action = function( action, callback, priority ) {
		if ( typeof window.trx_addons_add_action != 'undefined' ) {
			trx_addons_add_action( action, callback, priority );
		} else if ( typeof wp != 'undefined' && typeof wp.hooks != 'undefined' ) {
			wp.hooks.addAction( action, 'greeny', callback, priority == undefined ? 10 : priority );
		} else {
			greeny_add_filter( action, callback, priority );
		}
	};

	// Do action's handlers
	window.greeny_do_action = function( action, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 ) {
		if ( typeof window.trx_addons_do_action != 'undefined' ) {
			trx_addons_do_action( action, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 );
		} else if ( typeof wp != 'undefined' && typeof wp.hooks != 'undefined' && typeof wp.hooks.doActions != 'undefined' ) {
			wp.hooks.doActions( action, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 );
		} else {
			greeny_apply_filters( action, arg1, arg2, arg3, arg4, arg5, arg6, arg7, arg8, arg9 );
		}
	};


	/* String functions
	---------------------------------------------------------------- */

	window.greeny_in_list = function(str, list) {
		var delim  = arguments[2] !== undefined ? arguments[2] : '|';
		var icase  = arguments[3] !== undefined ? arguments[3] : true;
		var retval = false;
		if (icase) {
			if (typeof(str) == 'string') {
				str = str.toLowerCase();
			}
			list = list.toLowerCase();
		}
		var parts = list.split( delim );
		for (var i = 0; i < parts.length; i++) {
			if (parts[i] == str) {
				retval = true;
				break;
			}
		}
		return retval;
	};

	window.greeny_alltrim = function(str) {
		var dir      = arguments[1] !== undefined ? arguments[1] : 'a';
		var rez      = '';
		var i, start = 0, end = str.length - 1;
		if (dir == 'a' || dir == 'l') {
			for (i = 0; i < str.length; i++) {
				if (str.substr( i, 1 ) != ' ') {
					start = i;
					break;
				}
			}
		}
		if (dir == 'a' || dir == 'r') {
			for (i = str.length - 1; i >= 0; i--) {
				if (str.substr( i, 1 ) != ' ') {
					end = i;
					break;
				}
			}
		}
		return str.substring( start, end + 1 );
	};

	window.greeny_ltrim = function(str) {
		return greeny_alltrim( str, 'l' );
	};

	window.greeny_rtrim = function(str) {
		return greeny_alltrim( str, 'r' );
	};

	window.greeny_padl = function(str, len) {
		var ch  = arguments[2] !== undefined ? arguments[2] : ' ';
		var rez = str.substr( 0, len );
		if (rez.length < len) {
			for (var i = 0; i < len - str.length; i++) {
				rez += ch;
			}
		}
		return rez;
	};

	window.greeny_padr = function(str, len) {
		var ch  = arguments[2] !== undefined ? arguments[2] : ' ';
		var rez = str.substr( 0, len );
		if (rez.length < len) {
			for (var i = 0; i < len - str.length; i++) {
				rez = ch + rez;
			}
		}
		return rez;
	};

	window.greeny_padc = function(str, len) {
		var ch  = arguments[2] !== undefined ? arguments[2] : ' ';
		var rez = str.substr( 0, len );
		if (rez.length < len) {
			for (var i = 0; i < Math.floor( (len - str.length) / 2 ); i++) {
				rez = ch + rez + ch;
			}
		}
		return rez + (rez.length < len ? ch : '');
	};

	window.greeny_replicate = function(str, num) {
		var rez = '';
		for (var i = 0; i < num; i++) {
			rez += str;
		}
		return rez;
	};

	window.greeny_prepare_macros = function(str) {
		return str
			.replace( /\{\{/g, "<i>" )
			.replace( /\}\}/g, "</i>" )
			.replace( /\(\(/g, "<b>" )
			.replace( /\)\)/g, "</b>" )
			.replace( /\|\|/g, "<br>" );
	};


	/* Numbers functions
	---------------------------------------------------------------- */

	// Round number to specified precision.
	// For example: num=1.12345, prec=2,  rounded=1.12
	//              num=12345,   prec=-2, rounded=12300
	window.greeny_round_number = function(num) {
		var precision = arguments[1] !== undefined ? arguments[1] : 0;
		var p         = Math.pow( 10, precision );
		return Math.round( num * p ) / p;
	};

	// Clear number from any characters and append it with 0 to desired precision
	// For example: num=test1.12dd, prec=3, cleared=1.120
	window.greeny_clear_number = function(num) {
		var precision = arguments[1] !== undefined ? arguments[1] : 0;
		var defa      = arguments[2] !== undefined ? arguments[2] : 0;
		var res       = '';
		var decimals  = -1;
		num           = "" + num;
		if (num == "") {
			num = "" + defa;
		}
		for (var i = 0; i < num.length; i++) {
			if (decimals == 0) {
				break;
			} else if (decimals > 0) {
				decimals--;
			}
			var ch = num.substr( i,1 );
			if (ch == '.') {
				if (precision > 0) {
					res += ch;
				}
				decimals = precision;
			} else if ((ch >= 0 && ch <= 9) || (ch == '-' && i == 0)) {
				res += ch;
			}
		}
		if (precision > 0 && decimals != 0) {
			if (decimals == -1) {
				res     += '.';
				decimals = precision;
			}
			for (i = decimals; i > 0; i--) {
				res += '0';
			}
		}
		//if (isNaN(res)) res = clearNumber(defa, precision, defa);
		return res;
	};

	// Convert number from decimal to hex
	window.greeny_dec2hex = function(n) {
		return Number( n ).toString( 16 );
	};

	// Convert number from hex to decimal
	window.greeny_hex2dec = function(hex) {
		return parseInt( hex,16 );
	};


	/* Array manipulations
	---------------------------------------------------------------- */

	window.greeny_in_array = function(val, thearray)  {
		var rez = false;
		for (var i = 0; i < thearray.length - 1; i++) {
			if (thearray[i] == val) {
				rez = true;
				break;
			}
		}
		return rez;
	};

	window.greeny_sort_array = function(thearray)  {
		var caseSensitive = arguments[1] !== undefined ? arguments[1] : false;
		var tmp           = '';
		for (var x = 0; x < thearray.length - 1; x++) {
			for (var y = (x + 1); y < thearray.length; y++) {
				if (caseSensitive) {
					if (thearray[x] > thearray[y]) {
						tmp         = thearray[x];
						thearray[x] = thearray[y];
						thearray[y] = tmp;
					}
				} else {
					if (thearray[x].toLowerCase() > thearray[y].toLowerCase()) {
						tmp         = thearray[x];
						thearray[x] = thearray[y];
						thearray[y] = tmp;
					}
				}
			}
		}
		return thearray;
	};

	/* Date manipulations
	---------------------------------------------------------------- */

	// Return array[Year, Month, Day, Hours, Minutes, Seconds]
	// from string: Year[-/.]Month[-/.]Day[T ]Hours:Minutes:Seconds
	window.greeny_parse_date = function(dt) {
		dt      = dt.replace( /\//g, '-' ).replace( /\./g, '-' ).replace( /T/g, ' ' ).split( '+' )[0];
		var dt2 = dt.split( ' ' );
		var d   = dt2[0].split( '-' );
		var t   = dt2[1].split( ':' );
		d.push( t[0], t[1], t[2] );
		return d;
	};

	// Return difference string between two dates
	window.greeny_get_date_difference = function(dt1) {
		var dt2        = arguments[1] !== undefined ? arguments[1] : '';
		var short_date = arguments[2] !== undefined ? arguments[2] : true;
		var sec        = arguments[3] !== undefined ? arguments[3] : false;
		var a1         = greeny_parse_date( dt1 );
		dt1            = Date.UTC( a1[0], a1[1], a1[2], a1[3], a1[4], a1[5] );
		if (dt2 == '') {
			dt2    = new Date();
			var a2 = [dt2.getFullYear(), dt2.getMonth() + 1, dt2.getDate(), dt2.getHours(), dt2.getMinutes(), dt2.getSeconds()];
		} else {
			var a2 = greeny_parse_date( dt2 );
		}
		dt2         = Date.UTC( a2[0], a2[1], a2[2], a2[3], a2[4], a2[5] );
		var diff    = Math.round( (dt2 - dt1) / 1000 );
		var days    = Math.floor( diff / (24 * 3600) );
		diff       -= days * 24 * 3600;
		var hours   = Math.floor( diff / 3600 );
		diff       -= hours * 3600;
		var minutes = Math.floor( diff / 60 );
		diff       -= minutes * 60;
		var rez     = '';
		if (days > 0) {
			rez += (rez !== '' ? ' ' : '') + days + ' day' + (days > 1 ? 's' : '');
		}
		if (( ! short_date || rez == '') && hours > 0) {
			rez += (rez !== '' ? ' ' : '') + hours + ' hour' + (hours > 1 ? 's' : '');
		}
		if (( ! short_date || rez == '') && minutes > 0) {
			rez += (rez !== '' ? ' ' : '') + minutes + ' minute' + (minutes > 1 ? 's' : '');
		}
		if (sec || rez == '') {
			rez += rez !== '' || sec ? (' ' + diff + ' second' + (diff > 1 ? 's' : '')) : 'less then minute';
		}
		return rez;
	};

	/* Colors functions
	---------------------------------------------------------------- */

	window.greeny_hex2rgb = function(hex) {
		hex = parseInt( ((hex.indexOf( '#' ) > -1) ? hex.substring( 1 ) : hex), 16 );
		return {r: hex >> 16, g: (hex & 0x00FF00) >> 8, b: (hex & 0x0000FF)};
	};

	window.greeny_hex2rgba = function(hex, alpha) {
		var rgb = greeny_hex2rgb( hex );
		return 'rgba(' + rgb.r + ',' + rgb.g + ',' + rgb.b + ',' + alpha + ')';
	};

	window.greeny_rgb2hex = function(color) {
		var aRGB;
		color = color.replace( /\s/g,"" ).toLowerCase();
		if (color == 'rgba(0,0,0,0)' || color == 'rgba(0%,0%,0%,0%)') {
			color = 'transparent';
		}
		if (color.indexOf( 'rgba(' ) == 0) {
			aRGB = color.match( /^rgba\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i );
		} else {
			aRGB = color.match( /^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i );
		}

		if (aRGB) {
			color = '';
			for (var i = 1; i <= 3; i++) {
				color += Math.round( (aRGB[i][aRGB[i].length - 1] == "%" ? 2.55 : 1) * parseInt( aRGB[i] ) ).toString( 16 ).replace( /^(.)$/,'0$1' );
			}
		} else {
			color = color.replace( /^#?([\da-f])([\da-f])([\da-f])$/i, '$1$1$2$2$3$3' );
		}
		return (color.substr( 0,1 ) != '#' ? '#' : '') + color;
	};

	window.greeny_components2hex = function(r,g,b) {
		return '#' +
			Number( r ).toString( 16 ).toUpperCase().replace( /^(.)$/,'0$1' ) +
			Number( g ).toString( 16 ).toUpperCase().replace( /^(.)$/,'0$1' ) +
			Number( b ).toString( 16 ).toUpperCase().replace( /^(.)$/,'0$1' );
	};

	window.greeny_rgb2components = function(color) {
		color       = greeny_rgb2hex( color );
		var matches = color.match( /^#?([\dabcdef]{2})([\dabcdef]{2})([\dabcdef]{2})$/i );
		if ( ! matches) {
			return false;
		}
		for (var i = 1, rgb = new Array( 3 ); i <= 3; i++) {
			rgb[i - 1] = parseInt( matches[i],16 );
		}
		return rgb;
	};

	window.greeny_hex2hsb = function(hex) {
		var h   = arguments[1] !== undefined ? arguments[1] : 0;
		var s   = arguments[2] !== undefined ? arguments[2] : 0;
		var b   = arguments[3] !== undefined ? arguments[3] : 0;
		var hsb = greeny_rgb2hsb( greeny_hex2rgb( hex ) );
		hsb.h   = Math.min( 359, Math.max( 0, hsb.h + h ) );
		hsb.s   = Math.min( 100, Math.max( 0, hsb.s + s ) );
		hsb.b   = Math.min( 100, Math.max( 0, hsb.b + b ) );
		return hsb;
	};

	window.greeny_hsb2hex = function(hsb) {
		var rgb = greeny_hsb2rgb( hsb );
		return greeny_components2hex( rgb.r, rgb.g, rgb.b );
	};

	window.greeny_rgb2hsb = function(rgb) {
		var hsb = {};
		hsb.b   = Math.max( Math.max( rgb.r,rgb.g ),rgb.b );
		hsb.s   = (hsb.b <= 0) ? 0 : Math.round( 100 * (hsb.b - Math.min( Math.min( rgb.r,rgb.g ),rgb.b )) / hsb.b );
		hsb.b   = Math.round( (hsb.b / 255) * 100 );
		if ((rgb.r == rgb.g) && (rgb.g == rgb.b)) {
			hsb.h = 0;
		} else if (rgb.r >= rgb.g && rgb.g >= rgb.b) {
			hsb.h = 60 * (rgb.g - rgb.b) / (rgb.r - rgb.b);
		} else if (rgb.g >= rgb.r && rgb.r >= rgb.b) {
			hsb.h = 60 + 60 * (rgb.g - rgb.r) / (rgb.g - rgb.b);
		} else if (rgb.g >= rgb.b && rgb.b >= rgb.r) {
			hsb.h = 120 + 60 * (rgb.b - rgb.r) / (rgb.g - rgb.r);
		} else if (rgb.b >= rgb.g && rgb.g >= rgb.r) {
			hsb.h = 180 + 60 * (rgb.b - rgb.g) / (rgb.b - rgb.r);
		} else if (rgb.b >= rgb.r && rgb.r >= rgb.g) {
			hsb.h = 240 + 60 * (rgb.r - rgb.g) / (rgb.b - rgb.g);
		} else if (rgb.r >= rgb.b && rgb.b >= rgb.g) {
			hsb.h = 300 + 60 * (rgb.r - rgb.b) / (rgb.r - rgb.g);
		} else {
			hsb.h = 0;
		}
		hsb.h = Math.round( hsb.h );
		return hsb;
	};

	window.greeny_hsb2rgb = function(hsb) {
		var rgb = {};
		var h   = Math.round( hsb.h );
		var s   = Math.round( hsb.s * 255 / 100 );
		var v   = Math.round( hsb.b * 255 / 100 );
		if (s == 0) {
			rgb.r = rgb.g = rgb.b = v;
		} else {
			var t1 = v;
			var t2 = (255 - s) * v / 255;
			var t3 = (t1 - t2) * (h % 60) / 60;
			if (h == 360) {
				h = 0;
			}
			if (h < 60) {
				rgb.r = t1;	rgb.b = t2;   rgb.g = t2 + t3; } else if (h < 120) {
				rgb.g = t1; rgb.b = t2;	rgb.r = t1 - t3; } else if (h < 180) {
					rgb.g = t1; rgb.r = t2;	rgb.b = t2 + t3; } else if (h < 240) {
					rgb.b = t1; rgb.r = t2;	rgb.g = t1 - t3; } else if (h < 300) {
							rgb.b = t1; rgb.g = t2;	rgb.r = t2 + t3; } else if (h < 360) {
							rgb.r = t1; rgb.g = t2;	rgb.b = t1 - t3; } else {
								rgb.r = 0;  rgb.g = 0;	rgb.b = 0;	 }
		}
		return { r:Math.round( rgb.r ), g:Math.round( rgb.g ), b:Math.round( rgb.b ) };
	};

	window.greeny_color_picker = function(){
		var id         = arguments[0] !== undefined ? arguments[0] : "iColorPicker" + Math.round( Math.random() * 1000 );
		var colors     = arguments[1] !== undefined ? arguments[1] :
		'#f00,#ff0,#0f0,#0ff,#00f,#f0f,#fff,#ebebeb,#e1e1e1,#d7d7d7,#cccccc,#c2c2c2,#b7b7b7,#acacac,#a0a0a0,#959595,'
		+ '#ee1d24,#fff100,#00a650,#00aeef,#2f3192,#ed008c,#898989,#7d7d7d,#707070,#626262,#555,#464646,#363636,#262626,#111,#000,'
		+ '#f7977a,#fbad82,#fdc68c,#fff799,#c6df9c,#a4d49d,#81ca9d,#7bcdc9,#6ccff7,#7ca6d8,#8293ca,#8881be,#a286bd,#bc8cbf,#f49bc1,#f5999d,'
		+ '#f16c4d,#f68e54,#fbaf5a,#fff467,#acd372,#7dc473,#39b778,#16bcb4,#00bff3,#438ccb,#5573b7,#5e5ca7,#855fa8,#a763a9,#ef6ea8,#f16d7e,'
		+ '#ee1d24,#f16522,#f7941d,#fff100,#8fc63d,#37b44a,#00a650,#00a99e,#00aeef,#0072bc,#0054a5,#2f3192,#652c91,#91278f,#ed008c,#ee105a,'
		+ '#9d0a0f,#a1410d,#a36209,#aba000,#588528,#197b30,#007236,#00736a,#0076a4,#004a80,#003370,#1d1363,#450e61,#62055f,#9e005c,#9d0039,'
		+ '#790000,#7b3000,#7c4900,#827a00,#3e6617,#045f20,#005824,#005951,#005b7e,#003562,#002056,#0c004b,#30004a,#4b0048,#7a0045,#7a0026';
		var colorsList = colors.split( ',' );
		var tbl        = '<table class="colorPickerTable"><thead>';
		for (var i = 0; i < colorsList.length; i++) {
			if (i % 16 == 0) {
				tbl += (i > 0 ? '</tr>' : '') + '<tr>';
			}
			tbl += '<td style="background-color:' + colorsList[i] + '">&nbsp;</td>';
		}
		tbl += '</tr></thead><tbody>'
			+ '<tr style="height:60px;">'
			+ '<td colspan="8" id="' + id + '_colorPreview" style="vertical-align:middle;text-align:center;border:1px solid #000;background:#fff;">'
			+ '<input style="width:55px;color:#000;border:1px solid rgb(0, 0, 0);padding:5px;background-color:#fff;font:11px Arial, Helvetica, sans-serif;" maxlength="7" />'
			+ '<a href="#" id="' + id + '_moreColors" class="iColorPicker_moreColors"></a>'
			+ '</td>'
			+ '<td colspan="8" id="' + id + '_colorOriginal" style="vertical-align:middle;text-align:center;border:1px solid #000;background:#fff;">'
			+ '<input style="width:55px;color:#000;border:1px solid rgb(0, 0, 0);padding:5px;background-color:#fff;font:11px Arial, Helvetica, sans-serif;" readonly="readonly" />'
			+ '</td>'
			+ '</tr></tbody></table>';

		jQuery( document.createElement( "div" ) )
			.attr( "id", id )
			.css( 'display','none' )
			.html( tbl )
			.appendTo( "body" )
			.addClass( "iColorPickerTable" )
			.on( 'mouseover', 'thead td', function(){
					var aaa = greeny_rgb2hex( jQuery( this ).css( 'background-color' ) );
					jQuery( '#' + id + '_colorPreview' ).css( 'background',aaa );
					jQuery( '#' + id + '_colorPreview input' ).val( aaa );
				}
			)
			.on( 'keypress', '#' + id + '_colorPreview input', function(key){
					var aaa = jQuery( this ).val();
					if (aaa.length < 7 && ((key.which >= 48 && key.which <= 57) || (key.which >= 97 && key.which <= 102) || (key.which === 35 || aaa.length === 0))) {
						aaa += String.fromCharCode( key.which );
					} else if (key.which == 8 && aaa.length > 0) {
						aaa = aaa.substring( 0, aaa.length - 1 );
					} else if (key.which === 13 && (aaa.length === 4 || aaa.length === 7)) {
						var fld  = jQuery( '#' + id ).data( 'field' );
						var func = jQuery( '#' + id ).data( 'func' );
						if (func !== null && func != 'undefined') {
							func( fld, aaa );
						} else {
							fld.val( aaa ).css( 'backgroundColor', aaa ).trigger( 'change' );
						}
						jQuery( '#' + id + '_Bg' ).fadeOut( 500 );
						jQuery( '#' + id ).fadeOut( 500 );

					} else {
						key.preventDefault();
						return false;
					}
					if (aaa.substr( 0,1 ) === '#' && (aaa.length === 4 || aaa.length === 7)) {
						jQuery( '#' + id + '_colorPreview' ).css( 'background',aaa );
					}
					return true;
				}
			)
			.on( 'click', 'thead td', function(e){
					var fld  = jQuery( '#' + id ).data( 'field' );
					var func = jQuery( '#' + id ).data( 'func' );
					var aaa  = greeny_rgb2hex( jQuery( this ).css( 'background-color' ) );
					if (func !== null && func != 'undefined') {
						func( fld, aaa );
					} else {
						fld.val( aaa ).css( 'backgroundColor', aaa ).trigger( 'change' );
					}
					jQuery( '#' + id + '_Bg' ).fadeOut( 500 );
					jQuery( '#' + id ).fadeOut( 500 );
					e.preventDefault();
					return false;
				}
			)
			.on( 'click', 'tbody .iColorPicker_moreColors', function(e){
					var thead = jQuery( this ).parents( 'table' ).find( 'thead' );
					var out   = '';
					if (thead.hasClass( 'more_colors' )) {
						for (var i = 0; i < colorsList.length; i++) {
							if (i % 16 == 0) {
								out += (i > 0 ? '</tr>' : '') + '<tr>';
							}
							out += '<td style="background-color:' + colorsList[i] + '">&nbsp;</td>';
						}
						thead.removeClass( 'more_colors' ).empty().html( out + '</tr>' );
						jQuery( '#' + id + '_colorPreview' ).attr( 'colspan', 8 );
						jQuery( '#' + id + '_colorOriginal' ).attr( 'colspan', 8 );
					} else {
						var rgb = [0,0,0], i = 0, j = -1;	// Set j=-1 or j=0 - show 2 different colors layouts
						while (rgb[0] < 0xF || rgb[1] < 0xF || rgb[2] < 0xF) {
							if (i % 18 === 0) {
								out += (i > 0 ? '</tr>' : '') + '<tr>';
							}
							i++;
							out    += '<td style="background-color:' + greeny_components2hex( rgb[0] * 16 + rgb[0],rgb[1] * 16 + rgb[1],rgb[2] * 16 + rgb[2] ) + '">&nbsp;</td>';
							rgb[2] += 3;
							if (rgb[2] > 0xF) {
								rgb[1] += 3;
								if (rgb[1] > (j === 0 ? 6 : 0xF)) {
									rgb[0] += 3;
									if (rgb[0] > 0xF) {
										if (j === 0) {
											j      = 1;
											rgb[0] = 0;
											rgb[1] = 9;
											rgb[2] = 0;
										} else {
											break;
										}
									} else {
										rgb[1] = (j < 1 ? 0 : 9);
										rgb[2] = 0;
									}
								} else {
									rgb[2] = 0;
								}
							}
						}
						thead.addClass( 'more_colors' ).empty().html( out + '<td  style="background-color:#ffffff" colspan="8">&nbsp;</td></tr>' );
						jQuery( '#' + id + '_colorPreview' ).attr( 'colspan', 9 );
						jQuery( '#' + id + '_colorOriginal' ).attr( 'colspan', 9 );
					}
					jQuery( '#' + id + ' table.colorPickerTable thead td' )
					.css(
						{
							'width':'12px',
							'height':'14px',
							'border':'1px solid #000',
							'cursor':'pointer'
						}
					);
					e.preventDefault();
					return false;
				}
			);
		jQuery( document.createElement( "div" ) )
			.attr( "id", id + "_Bg" )
			.on( 'click', function(e) {
					jQuery( "#" + id + "_Bg" ).fadeOut( 500 );
					jQuery( "#" + id ).fadeOut( 500 );
					e.preventDefault();
					return false;
				}
			)
			.appendTo( "body" );
		jQuery( '#' + id + ' table.colorPickerTable thead td' )
			.css(
				{
					'width':'12px',
					'height':'14px',
					'border':'1px solid #000',
					'cursor':'pointer'
				}
			);
		jQuery( '#' + id + ' table.colorPickerTable' )
			.css( {'border-collapse':'collapse'} );
		jQuery( '#' + id )
			.css(
				{
					'border':'1px solid #ccc',
					'background':'#333',
					'padding':'5px',
					'color':'#fff'
				}
			);
		jQuery( '#' + id + '_colorPreview' )
			.css( {'height':'50px'} );
		return id;
	};

	window.greeny_color_picker_show = function(id, fld, func) {
		if (id === null || id === '') {
			id = jQuery( '.iColorPickerTable' ).attr( 'id' );
		}
		var eICP = fld.offset();
		var w    = jQuery( '#' + id ).width();
		var h    = jQuery( '#' + id ).height();
		var l    = eICP.left + w < $window.width() - 10 ? eICP.left : $window.width() - 10 - w;
		var t    = eICP.top + fld.outerHeight() + h < $window.scrollTop() + $window.height() - 10 ? eICP.top + fld.outerHeight() : eICP.top - h - 13;
		jQuery( "#" + id )
			.data( {field: fld, func: func} )
			.css(
				{
					'top':t + "px",
					'left':l + "px",
					'position':'absolute',
					'z-index':999999
				}
			)
			.fadeIn( 500 );
		jQuery( "#" + id + "_Bg" )
			.css(
				{
					'position':'fixed',
					'z-index':999998,
					'top':0,
					'left':0,
					'width':'100%',
					'height':'100%'
				}
			)
			.fadeIn( 500 );
		var def = fld.val().substr( 0, 1 ) == '#' ? fld.val() : greeny_rgb2hex( fld.css( 'backgroundColor' ) );
		jQuery( '#' + id + '_colorPreview input,#' + id + '_colorOriginal input' ).val( def );
		jQuery( '#' + id + '_colorPreview,#' + id + '_colorOriginal' ).css( 'background',def );
	};

	/* CSS functions
	---------------------------------------------------------------- */

	// Return font-family string from the font parameters
	window.greeny_get_load_fonts_family_string = function( name, family ) {
		var parts = [ name ];
		if ( greeny_alltrim( family ) != '' ) {
			parts = parts.concat( family.split( ',' ) );
		}
		for( var i = 0; i < parts.length; i++ ) {
			parts[ i ] = greeny_alltrim( parts[ i ] );
			if ( parts[ i ].indexOf( '"' ) < 0 && parts[ i ].indexOf( ' ' ) >= 0 ) {
				parts[ i ] = '"' + parts[ i ] + '"';
			}
		}
		return parts.join(',');
	};

	// Return class by prefix from classes string
	window.greeny_get_class_by_prefix = function(classes, prefix) {
		var rez = '';
		if ( classes ) {
			classes = classes.split(' ');
			for (var i=0; i < classes.length; i++) {
				if (classes[i].indexOf(prefix) >= 0) {
					rez = classes[i].replace(/[\s]+/g, '');			// Remove \t\r\n and spaces from the new class
					break;
				}
			}
		}
		return rez;
	};

	// Replace class by prefix with new value
	window.greeny_chg_class_by_prefix = function(classes, prefix, new_value) {
		var chg = false;
		if ( ! classes ) classes = '';
		classes = classes.replace(/[\s]+/g, ' ').split(' ');	// Replace groups \t\r\n and spaces with the single space
		new_value = new_value.replace(/[\s]+/g, '');			// Remove \t\r\n and spaces from the new class
		if ( typeof prefix == 'string' ) {
			prefix = [prefix];
		}
		for (var i=0; i < classes.length; i++) {
			for (var j = 0; j < prefix.length; j++ ) {
				if (classes[i].indexOf( prefix[j] ) >= 0) {
					classes[i] = new_value;
					chg = true;
					break;
				}
			}
			if ( chg ) break;
		}
		if ( ! chg && new_value ) {
			if (classes.length == 1 && classes[0] === '')
				classes[0] = new_value;
			else
				classes.push( new_value );
		}
		return classes.join(' ');
	};

	/* Cookies manipulations
	---------------------------------------------------------------- */

	window.greeny_get_cookie = function(name) {
		var defa  = arguments[1] !== undefined ? arguments[1] : null;
		var start = document.cookie.indexOf( name + '=' );
		var len   = start + name.length + 1;
		if (( ! start) && (name != document.cookie.substring( 0, name.length ))) {
			return defa;
		}
		if (start == -1) {
			return defa;
		}
		var end = document.cookie.indexOf( ';', len );
		if (end == -1) {
			end = document.cookie.length;
		}
		return unescape( document.cookie.substring( len, end ) );
	};

	window.greeny_set_cookie = function(name, value) {
		var expires  = arguments[2] !== undefined ? arguments[2] : 0;
		var path     = arguments[3] !== undefined ? arguments[3] : '/';
		var domain   = arguments[4] !== undefined ? arguments[4] : '';
		var secure   = arguments[5] !== undefined ? arguments[5] : '';
		var samesite = arguments[6] !== undefined ? arguments[6] : 'strict';	// strict | lax | none
		var today    = new Date();
		today.setTime( today.getTime() );
		var expires_date = new Date( today.getTime() + (expires * 1) );
		document.cookie  = encodeURIComponent(name) + '='
				+ encodeURIComponent( value )
				+ (expires ? ';expires=' + expires_date.toGMTString() : '')
				+ (path ? ';path=' + path : '')
				+ (domain ? ';domain=' + domain : '')
				+ (secure ? ';secure' : '')
				+ (samesite  ? ';samesite=' + samesite : '');
	};

	window.greeny_del_cookie = function(name) {
		var path     = arguments[1] !== undefined ? arguments[1] : '/';
		var domain   = arguments[2] !== undefined ? arguments[2] : '';
		var secure   = arguments[3] !== undefined ? arguments[3] : '';
		var samesite = arguments[4] !== undefined ? arguments[4] : 'strict';	// strict | lax | none
		if ( greeny_get_cookie( name ) ) {
			document.cookie = name + '='
				+ ';expires=Thu, 01-Jan-1970 00:00:01 GMT'
				+ (path ? ';path=' + path : '')
				+ (domain ? ';domain=' + domain : '')
				+ (secure ? ';secure' : '')
				+ (samesite  ? ';samesite=' + samesite : '');
		}
	};

	/* Local storage manipulations
	---------------------------------------------------------------- */

	window.greeny_is_local_storage_exists = function() {
		try {
			return 'localStorage' in window && window['localStorage'] !== null;
		} catch (e) {
			return false;
		}		
	};
	
	window.greeny_get_storage = function(name) {
		var defa = arguments[1] !== undefined ? arguments[1] : null;
		var val = null;
		if (greeny_is_local_storage_exists()) {
			val = window['localStorage'].getItem(name);
			if (val === null) val = defa;
		} else {
			val = greeny_get_cookie(name, defa);
		}
		return val;
	};
	
	window.greeny_set_storage = function(name, value) {
		if (greeny_is_local_storage_exists())
			window['localStorage'].setItem(name, value);
		else 
			greeny_set_cookie(name, value, 365 * 24 * 60 * 60 * 1000);   // 1 year
	};
	
	window.greeny_del_storage = function(name) {
		if (greeny_is_local_storage_exists())
			window['localStorage'].removeItem(name);
		else 
			greeny_del_cookie(name);
	};
	
	window.greeny_clear_storage = function() {
		if (greeny_is_local_storage_exists())
			window['localStorage'].clear();
	};

	/* ListBox and ComboBox manipulations
	---------------------------------------------------------------- */

	window.greeny_clear_listbox = function(box) {
		for (var i = box.options.length - 1; i >= 0; i--) {
			box.options[i] = null;
		}
	};

	window.greeny_add_listbox_item = function(box, val, text) {
		var item   = new Option();
		item.value = val;
		item.text  = text;
		box.options.add( item );
	};

	window.greeny_del_listbox_item_by_value = function(box, val) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].value == val) {
				box.options[i] = null;
				break;
			}
		}
	};

	window.greeny_del_listbox_item_by_text = function(box, txt) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].text == txt) {
				box.options[i] = null;
				break;
			}
		}
	};

	window.greeny_find_listbox_item_by_value = function(box, val) {
		var idx = -1;
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].value == val) {
				idx = i;
				break;
			}
		}
		return idx;
	};

	window.greeny_find_listbox_item_by_text = function(box, txt) {
		var idx = -1;
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].text == txt) {
				idx = i;
				break;
			}
		}
		return idx;
	};

	window.greeny_select_listbox_item_by_value = function(box, val) {
		for (var i = 0; i < box.options.length; i++) {
			box.options[i].selected = (val == box.options[i].value);
		}
	};

	window.greeny_select_listbox_item_by_text = function(box, txt) {
		for (var i = 0; i < box.options.length; i++) {
			box.options[i].selected = (txt == box.options[i].text);
		}
	};

	window.greeny_get_listbox_values = function(box) {
		var delim = arguments[1] !== undefined ? arguments[1] : ',';
		var str   = '';
		for (var i = 0; i < box.options.length; i++) {
			str += (str ? delim : '') + box.options[i].value;
		}
		return str;
	};

	window.greeny_get_listbox_texts = function(box) {
		var delim = arguments[1] !== undefined ? arguments[1] : ',';
		var str   = '';
		for (var i = 0; i < box.options.length; i++) {
			str += (str ? delim : '') + box.options[i].text;
		}
		return str;
	};

	window.greeny_sort_listbox = function(box)  {
		var temp_opts = new Array(),
			temp      = new Option(),
			i, x, y;
		for (i = 0; i < box.options.length; i++) {
			temp_opts[i] = box.options[i].clone();
		}
		for (x = 0; x < temp_opts.length - 1; x++) {
			for (y = (x + 1); y < temp_opts.length; y++) {
				if (temp_opts[x].text > temp_opts[y].text) {
					temp         = temp_opts[x];
					temp_opts[x] = temp_opts[y];
					temp_opts[y] = temp;
				}
			}
		}
		for (i = 0; i < box.options.length; i++) {
			box.options[i] = temp_opts[i].clone();
		}
	};

	window.greeny_get_listbox_selected_index = function(box) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].selected) {
				return i;
			}
		}
		return -1;
	};

	window.greeny_get_listbox_selected_value = function(box) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].selected) {
				return box.options[i].value;
			}
		}
		return null;
	};

	window.greeny_get_listbox_selected_text = function(box) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].selected) {
				return box.options[i].text;
			}
		}
		return null;
	};

	window.greeny_get_listbox_selected_option = function(box) {
		for (var i = 0; i < box.options.length; i++) {
			if (box.options[i].selected) {
				return box.options[i];
			}
		}
		return null;
	};

	/* Radio buttons manipulations
	---------------------------------------------------------------- */

	window.greeny_get_radio_value = function(radioGroupObj) {
		for (var i = 0; i < radioGroupObj.length; i++) {
			if (radioGroupObj[i].checked) {
				return radioGroupObj[i].value;
			}
		}
		return null;
	};

	window.greeny_set_radio_checked_by_num = function(radioGroupObj, num) {
		for (var i = 0; i < radioGroupObj.length; i++) {
			if (radioGroupObj[i].checked && i != num) {
				radioGroupObj[i].checked = false;
			} else if (i == num) {
				radioGroupObj[i].checked = true;
			}
		}
	};

	window.greeny_set_radio_checked_by_value = function(radioGroupObj, val) {
		for (var i = 0; i < radioGroupObj.length; i++) {
			if (radioGroupObj[i].checked && radioGroupObj[i].value != val) {
				radioGroupObj[i].checked = false;
			} else if (radioGroupObj[i].value == val) {
				radioGroupObj[i].checked = true;
			}
		}
	};

	/* Form manipulations
	---------------------------------------------------------------- */

	/*
	// Usage example:
	var error = greeny_form_validate(jQuery(form_selector), {		// -------- Options ---------
		error_message_show: true,									// Display or not error message
		error_message_time: 5000,									// Time to display error message
		error_message_class: 'message_box message_box_error',		// Class, appended to error message block
		error_message_text: 'Global error text',					// Global error message text (if don't write message in checked field)
		error_fields_class: 'error_field',							// Class, appended to error fields
		exit_after_first_error: false,								// Cancel validation and exit after first error
		rules: [
			{
				field: 'author',																// Checking field name
				min_length: { value: 1,	 message: 'The author name can\'t be empty' },			// Min character count (0 - don't check), message - if error occurs
				max_length: { value: 60, message: 'Too long author name'}						// Max character count (0 - don't check), message - if error occurs
			},
			{
				field: 'email',
				min_length: { value: 7,	 message: 'Too short (or empty) email address' },
				max_length: { value: 60, message: 'Too long email address'},
				mask: { value: '^([a-zA-Z0-9_\\-]+\\.)*[a-zA-Z0-9_\\-]+@[a-zA-Z0-9_\\-]+(\\.[a-zA-Z0-9_\\-]+)*\\.[a-zA-Z]{2,6}$', message: 'Invalid email address'}
			},
			{
				field: 'comment',
				min_length: { value: 1,	 message: 'The comment text can\'t be empty' },
				max_length: { value: 200, message: 'Too long comment'}
			},
			{
				field: 'pwd1',
				min_length: { value: 5,	 message: 'The password can\'t be less then 5 characters' },
				max_length: { value: 20, message: 'Too long password'}
			},
			{
				field: 'pwd2',
				equal_to: { value: 'pwd1',	 message: 'The passwords in both fields must be equals' }
			}
		]
	});
	*/

	window.greeny_form_validate = function(form, opt) {
		var error_msg = '';
		form.find( ":input" ).each(
			function() {
				if (error_msg !== '' && opt.exit_after_first_error) {
					return;
				}
				for (var i = 0; i < opt.rules.length; i++) {
					if (jQuery( this ).attr( "name" ) == opt.rules[i].field) {
						var val   = jQuery( this ).val();
						var error = false;
						if (typeof(opt.rules[i].min_length) == 'object') {
							if (opt.rules[i].min_length.value > 0 && val.length < opt.rules[i].min_length.value) {
								if (error_msg == '') {
									jQuery( this ).get( 0 ).focus();
								}
								error_msg += '<p class="error_item">' + (typeof(opt.rules[i].min_length.message) != 'undefined' ? opt.rules[i].min_length.message : opt.error_message_text ) + '</p>';
								error      = true;
							}
						}
						if (( ! error || ! opt.exit_after_first_error) && typeof(opt.rules[i].max_length) == 'object') {
							if (opt.rules[i].max_length.value > 0 && val.length > opt.rules[i].max_length.value) {
								if (error_msg == '') {
									jQuery( this ).get( 0 ).focus();
								}
								error_msg += '<p class="error_item">' + (typeof(opt.rules[i].max_length.message) != 'undefined' ? opt.rules[i].max_length.message : opt.error_message_text ) + '</p>';
								error      = true;
							}
						}
						if (( ! error || ! opt.exit_after_first_error) && typeof(opt.rules[i].mask) == 'object') {
							if (opt.rules[i].mask.value !== '') {
								var regexp = new RegExp( opt.rules[i].mask.value );
								if ( ! regexp.test( val )) {
									if (error_msg == '') {
										jQuery( this ).get( 0 ).focus();
									}
									error_msg += '<p class="error_item">' + (typeof(opt.rules[i].mask.message) != 'undefined' ? opt.rules[i].mask.message : opt.error_message_text ) + '</p>';
									error      = true;
								}
							}
						}
						if (( ! error || ! opt.exit_after_first_error) && typeof(opt.rules[i].state) == 'object') {
							if (opt.rules[i].state.value == 'checked' && ! jQuery( this ).get( 0 ).checked) {
								if (error_msg == '') {
									jQuery( this ).get( 0 ).focus();
								}
								error_msg += '<p class="error_item">' + (typeof(opt.rules[i].state.message) != 'undefined' ? opt.rules[i].state.message : opt.error_message_text ) + '</p>';
								error      = true;
							}
						}
						if (( ! error || ! opt.exit_after_first_error) && typeof(opt.rules[i].equal_to) == 'object') {
							if (opt.rules[i].equal_to.value !== '' && val != jQuery( jQuery( this ).get( 0 ).form[opt.rules[i].equal_to.value] ).val()) {
								if (error_msg == '') {
									jQuery( this ).get( 0 ).focus();
								}
								error_msg += '<p class="error_item">' + (typeof(opt.rules[i].equal_to.message) != 'undefined' ? opt.rules[i].equal_to.message : opt.error_message_text ) + '</p>';
								error      = true;
							}
						}
						if (opt.error_fields_class !== '') {
							jQuery( this ).toggleClass( opt.error_fields_class, error );
						}
					}
				}
			}
		);
		if (error_msg !== '' && opt.error_message_show) {
			var error_message_box = form.find( ".result" );
			if (error_message_box.length == 0) {
				error_message_box = form.parent().find( ".result" );
			}
			if (error_message_box.length == 0) {
				form.append( '<div class="result"></div>' );
				error_message_box = form.find( ".result" );
			}
			if (opt.error_message_class) {
				error_message_box.toggleClass( opt.error_message_class, true );
			}
			error_message_box.html( error_msg ).fadeIn();
			setTimeout( function() { error_message_box.fadeOut(); }, opt.error_message_time );
		}
		return error_msg !== '';
	};

	/* Document manipulations
	---------------------------------------------------------------- */

	// Animated scroll to selected id
	window.greeny_document_animate_to = function(id) {
		var speed    = arguments.length > 1 ? arguments[1] : -1;
		var callback = arguments.length > 2 ? arguments[2] : undefined;
		var oft = ! isNaN( id ) ? Number( id ) : 0,
			oft2 = -1;
		if (isNaN( id )) {
			if ( id.substring(0, 1) != '#' && id.substring(0, 1) != '.' ) {
				id = '#' + id;
			}
			var obj = jQuery( id ).eq( 0 );
			if (obj.length === 0) {
				return;
			}
			oft = obj.offset().top;
			oft2 = Math.max( 0, oft - greeny_fixed_rows_height() );
		}
		if (speed < 0) {
			speed = Math.min( 1000, Math.max( 300, Math.round( Math.abs( ( oft2 < 0 ? oft : oft2 ) - $window.scrollTop() ) / $window.height() * 300 ) ) );
		}
		if (oft2 >= 0) {
			setTimeout(
				function() {
					if (isNaN( id )) {
						oft = obj.offset().top;
					}
					oft2 = Math.max( 0, oft - greeny_fixed_rows_height() );
					jQuery( 'body,html' ).stop( true ).animate( {scrollTop: oft2}, Math.floor( speed / 2 ), 'linear', callback );
				}, Math.floor( speed / 2 )
			);
		} else {
			oft2 = oft;
		}
		if ( speed > 0 ) {
			jQuery( 'body,html' ).stop( true ).animate( { scrollTop: oft2 }, speed, 'linear', callback );
		} else {
			jQuery( 'body,html' ).stop( true ).scrollTop( oft2 );
			if ( callback ) {
				callback( id, speed );
			}
		}
	};

	// Detect window width, height and scroll
	var _window_width = $window.width(),
		_window_height = $window.height(),
		_window_scroll_top = $window.scrollTop(),
		_window_scroll_left = $window.scrollLeft();
	$window.on( 'resize', function() {
		_window_width = $window.width();
		_window_height = $window.height();
		_window_scroll_top = $window.scrollTop();
		_window_scroll_left = $window.scrollLeft();	
	} );
	$window.on( 'scroll', function() {
		_window_scroll_top = $window.scrollTop();
		_window_scroll_left = $window.scrollLeft();	
	} );
	window.greeny_window_width = function( val ) {
		if ( val ) _window_width = val;
		return _window_width;
	};
	window.greeny_window_height = function( val ) {
		if ( val ) _window_height = val;
		return _window_height;
	};
	window.greeny_window_scroll_top = function() {
		return _window_scroll_top;
	};
	window.greeny_window_scroll_left = function() {
		return _window_scroll_left;
	};

	// Detect document height
	var	_document_height;
	var _document_height_first_run = true;
	var _update_document_height = function( e ) {
		if ( typeof window.trx_addons_document_height == 'undefined' ) {
			if ( _document_height_first_run && e && e.namespace == 'init_hidden_elements' ) {
				_document_height_first_run = false;
				return; 
			}
			_document_height = $document.height();
		}
	};
	$document.ready( _update_document_height );
	$document.on( 'action.init_hidden_elements action.got_ajax_response',    // Maybe need for ' action.sc_layouts_row_fixed_on action.sc_layouts_row_fixed_off'
				_update_document_height
				);
	$window.on( 'resize', _update_document_height );
	window.greeny_document_height = function() {
		return typeof window.trx_addons_document_height != 'undefined' ? trx_addons_document_height() : _document_height;
	};

	// Detect adminbar height (if present and fixed)
	var _adminbar_height = 0;
	var _update_adminbar_height = function() {
		if ( typeof window.trx_addons_adminbar_height == 'undefined' ) {
			_adminbar_height = greeny_adminbar_height_calc();
			document.querySelector('html').style.setProperty( '--fixed-rows-height', _adminbar_height + 'px' );
		}
	};
	$document.ready( _update_adminbar_height );
	$window.on( 'resize', _update_adminbar_height );
	window.greeny_adminbar_height_calc = function() {
		return greeny_apply_filters( 'greeny_filter_adminbar_height',
										$adminbar.length === 0
											|| $adminbar.css( 'display' ) == 'none'
											|| $adminbar.css( 'position' ) == 'absolute'
												? 0
												: $adminbar.height()
									);
	};
	window.greeny_adminbar_height = function() {
		return typeof window.trx_addons_adminbar_height != 'undefined' ? trx_addons_adminbar_height() : _adminbar_height;
	};

	// Detect fixed rows height
	window.greeny_fixed_rows_height = function() {
		var with_admin_bar  = arguments.length > 0 ? arguments[0] : true,
			with_fixed_rows = arguments.length > 1 ? arguments[1] : true;
		return typeof trx_addons_fixed_rows_height != 'undefined'
					? trx_addons_fixed_rows_height( with_admin_bar, with_fixed_rows )
					: ( with_admin_bar ? greeny_adminbar_height() : 0 );
	};

	// Change browser address without reload page
	window.greeny_document_set_location = function(curLoc){
		try {
			history.pushState( null, null, curLoc );
			return;
		} catch (e) {
		}
		location.href = curLoc;
	};

	// Add/Change arguments to the url address
	window.greeny_add_to_url = function(loc, prm) {
		var ignore_empty = arguments[2] !== undefined ? arguments[2] : true;
		var q            = loc.indexOf( '?' );
		var attr         = {};
		if (q > 0) {
			var qq    = loc.substr( q + 1 ).split( '&' );
			var parts = '';
			for (var i = 0; i < qq.length; i++) {
				var parts      = qq[i].split( '=' );
				attr[parts[0]] = parts.length > 1 ? parts[1] : '';
			}
		}
		for (var p in prm) {
			attr[p] = prm[p];
		}
		loc   = (q > 0 ? loc.substr( 0, q ) : loc) + '?';
		var i = 0;
		for (p in attr) {
			if (ignore_empty && attr[p] == '') {
				continue;
			}
			loc += (i++ > 0 ? '&' : '') + p + '=' + attr[p];
		}
		return loc;
	};

	// Check if url is page-inner (local) link
	window.greeny_is_local_link = function(url) {
		var rez = url !== undefined;
		if (rez) {
			var url_pos = url.indexOf( '#' );
			if (url_pos == 0 && url.length == 1) {
				rez = false;
			} else {
				if (url_pos < 0) {
					url_pos = url.length;
				}
				var loc     = window.location.href;
				var loc_pos = loc.indexOf( '#' );
				if (loc_pos > 0) {
					loc = loc.substring( 0, loc_pos );
				}
				rez = url_pos == 0;
				if ( ! rez) {
					rez = loc == url.substring( 0, url_pos );
				}
			}
		}
		return rez;
	};

	// Check if the specified path is url
	window.greeny_is_url = function( url ) {
		return url.indexOf( '//' ) === 0 || url.indexOf( '://' ) > 0;
	};


	/* Browsers detection
	---------------------------------------------------------------- */

	window.greeny_browser_is_mobile = function() {
		var check = false;
		(function(a){if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test( a ) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test( a.substr( 0,4 ) )) {
				check = true}
		})( navigator.userAgent || navigator.vendor || window.opera );
		return check;
	};

	window.greeny_browser_is_ios = function() {
		return navigator.userAgent.match( /iPad|iPhone|iPod/i ) != null;
	};

	window.greeny_is_retina = function() {
		var mediaQuery = '(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)';
		return (window.devicePixelRatio > 1) || (window.matchMedia && window.matchMedia( mediaQuery ).matches);
	};

	/* File functions
	---------------------------------------------------------------- */

	window.greeny_get_file_name = function(path) {
		path    = path.replace( /\\/g, '/' );
		var pos = path.lastIndexOf( '/' );
		if (pos >= 0) {
			path = path.substr( pos + 1 );
		}
		return path;
	};

	window.greeny_get_file_ext = function(path) {
		var pos = path.lastIndexOf( '.' );
		path    = pos >= 0 ? path.substr( pos + 1 ) : '';
		return path;
	};

	/* Image functions
	---------------------------------------------------------------- */

	// Return true, if all images in the specified container are loaded
	window.greeny_is_images_loaded = function(cont) {
		var complete = true;
		cont.find( 'img' ).each(
			function() {
				// If any of previous images is not loaded - skip rest
				if ( ! complete) {
					return;
				}
				var img = jQuery( this ).get( 0 );
				if (typeof img.complete == 'boolean') {
					// See if "complete" property is available
					complete = img.complete;
				} else if (typeof img.naturalWidth == 'number' && typeof img.naturalHeight == 'number') {
					// See if "naturalWidth" and "naturalHeight" properties are available
					complete = ! (this.naturalWidth == 0 && this.naturalHeight == 0);
				}
			}
		);
		return complete;
	};

	// Call function when all images in the specified container are loaded
	window.greeny_when_images_loaded = function(cont, callback, max_delay) {
		if (max_delay === undefined) {
			max_delay = 3000;
		}
		if (max_delay <= 0 || greeny_is_images_loaded( cont )) {
			callback();
		} else {
			setTimeout(
				function(){
					greeny_when_images_loaded( cont, callback, max_delay - 100 );
				}, 100
			);
		}
	};

	/* Debug functions
	---------------------------------------------------------------- */
	window.greeny_debug_object = function(obj) {
		var recursive   = arguments[1] ? arguments[1] : 0;			// Show inner objects (arrays) in depth
		var showMethods = arguments[2] ? arguments[2] : false;		// Show object's methods
		var level       = arguments[3] ? arguments[3] : 0;				// Nesting level (for internal usage only)
		var dispStr     = "";
		var addStr      = "";
		var curStr      = "";
		if (level > 0) {
			dispStr += (obj === null ? "null" : typeof(obj)) + "\n";
			addStr   = greeny_replicate( ' ', level * 2 );
		}
		if (obj !== null && (typeof(obj) == 'object' || typeof(obj) == 'array')) {
			for (var prop in obj) {
				if ( ! showMethods && typeof(obj[prop]) == 'function') {	// || prop=='innerHTML' || prop=='outerHTML' || prop=='innerText' || prop=='outerText')
					continue;
				}
				if (level < recursive && (typeof(obj[prop]) == 'object' || typeof(obj[prop]) == 'array') && obj[prop] != obj) {
					dispStr += addStr + prop + '=' + greeny_debug_object( obj[prop], recursive, showMethods, level + 1 );
				} else {
					try {
						curStr = "" + obj[prop];
					} catch (e) {
						curStr = "--- Not evaluate ---";
					}
					dispStr += addStr + prop + '=' + (typeof(obj[prop]) == 'string' ? '"' : '') + curStr + (typeof(obj[prop]) == 'string' ? '"' : '') + "\n";
				}
			}
		} else if (typeof(obj) != 'function') {
			dispStr += addStr + (typeof(obj) == 'string' ? '"' : '') + obj + (typeof(obj) == 'string' ? '"' : '') + "\n";
		}

		return dispStr;	//decodeURI(dispStr);
	};

	window.greeny_debug_log = function(s, clr) {
		if (GREENY_STORAGE['user_logged_in']) {
			if (jQuery( '#debug_log' ).length == 0) {
				$body.append( '<div id="debug_log"><span id="debug_log_close">x</span><pre id="debug_log_content"></pre></div>' );
				jQuery( "#debug_log_close" ).on(
					'click', function(e) {
						jQuery( '#debug_log' ).hide();
						e.preventDefault();
						return false;
					}
				);
			}
			if (clr) {
				jQuery( '#debug_log_content' ).empty();
			}
			jQuery( '#debug_log_content' ).prepend( s + ' ' );
			jQuery( '#debug_log' ).show();
		}
	};

	window.dcl === undefined && (window.dcl = function(s) { console.log( s ); });
	window.dco === undefined && (window.dco = function(s,r) { console.log( greeny_debug_object( s,r ) ); });
	window.dcs === undefined && (window.dcs = function() { console.trace(); });
	window.dal === undefined && (window.dal = function(s) { if (GREENY_STORAGE['user_logged_in']) {
			alert( s );
	} });
	window.dao === undefined && (window.dao = function(s,r) { if (GREENY_STORAGE['user_logged_in']) {
			alert( greeny_debug_object( s,r ) );
	} });
	window.ddl === undefined && (window.ddl = function(s,c) { greeny_debug_log( s,c ); });
	window.ddo === undefined && (window.ddo = function(s,r,c) { greeny_debug_log( greeny_debug_object( s,r ),c ); });

})();
/* global jQuery:false */
/* global GREENY_STORAGE:false */

jQuery( document ).ready( function() {

	"use strict";

	var ready_busy = true;

	var theme_init_counter      = 0;

	var $window                 = jQuery( window ),
		$document               = jQuery( document ),
		$body                   = jQuery( 'body' ),
		$body_wrap              = jQuery( '.body_wrap' ),
		$page_wrap              = jQuery( '.page_wrap' ),
		$header                 = jQuery( '.top_panel' ),
		_header_height          = $header.length === 0 ? 0 : $header.height(),
		$footer                 = jQuery( '.footer_wrap' ),
		_footer_height          = $footer.length === 0 ? 0 : $footer.height(),
		$menu_side_wrap         = jQuery( '.menu_side_wrap' ),
		$menu_side_logo         = $menu_side_wrap.find( '.sc_layouts_logo' );

	var $page_content_wrap,
		$content,
		$sidebar,
		// Single post parts
		$single_nav_links_fixed,
		$single_post_info_fixed,
		$single_post_scrollers,
		$stretch_width;

	// Update links after the new post added
	$document.on( 'action.new_post_added', update_jquery_links );
	$document.on( 'action.got_ajax_response', update_jquery_links );
	$document.on( 'action.init_hidden_elements', update_jquery_links );
	var first_run = true;
	function update_jquery_links(e) {
		if ( first_run && e && e.namespace == 'init_hidden_elements' ) {
			first_run = false;
			return; 
		}
		$page_content_wrap      = jQuery( '.page_content_wrap' );
		$content                = jQuery( '.content' );
		$sidebar                = jQuery( '.sidebar:not(.sidebar_fixed_placeholder)' );
		// Single post parts
		$single_nav_links_fixed = jQuery( '.nav-links-single.nav-links-fixed' );
		$single_post_info_fixed = jQuery( '.post_info_vertical.post_info_vertical_fixed' );
		$single_post_scrollers  = jQuery( '.nav-links-single-scroll' );
		$stretch_width          = jQuery( '.trx-stretch-width' );
	}
	update_jquery_links();


	// Init actions
	//-----------------------------------

	// Init intersection observer
	greeny_intersection_observer_init();

	// Init all other actions
	greeny_init_actions();

	function greeny_init_actions() {

		if (GREENY_STORAGE['vc_edit_mode'] && jQuery( '.vc_empty-placeholder' ).length === 0 && theme_init_counter++ < 30) {
			setTimeout( greeny_init_actions, 200 );
			return;
		}

		// Resize handlers
		$window.on( 'resize', function() {
			greeny_resize_actions();
		} );

		// Scroll handlers
		GREENY_STORAGE['scroll_busy'] = true;
		$window.on( 'scroll', function() {
			if (window.requestAnimationFrame) {
				if ( ! GREENY_STORAGE['scroll_busy']) {
					window.requestAnimationFrame( function() {
						greeny_scroll_actions();
					});
					GREENY_STORAGE['scroll_busy'] = true;
				}
			} else {
				greeny_scroll_actions();
			}
		} );

		// First call to init core actions
		greeny_ready_actions();
		greeny_resize_actions();
		greeny_scroll_actions();

		// Wait when logo is loaded
		if ( $body.hasClass( 'menu_side_present' ) ) {
			if ( $menu_side_logo.length > 0 && ! greeny_is_images_loaded( $menu_side_logo ) ) {
				greeny_when_images_loaded(
					$menu_side_logo, function() {
						greeny_stretch_sidemenu();
					}
				);
			}
		}
	}   // greeny_init_actions


	// Theme first load actions
	//==============================================
	function greeny_ready_actions() {

		// Add scheme class and js support
		//------------------------------------
		document.documentElement.className = document.documentElement.className.replace( /\bno-js\b/,'js' );
		if (document.documentElement.className.indexOf( GREENY_STORAGE['site_scheme'] ) == -1) {
			document.documentElement.className += ' ' + GREENY_STORAGE['site_scheme'];
		}

		// Skip to content link
		//------------------------------------
		jQuery( 'a.greeny_skip_link:not(.inited)' )
			.addClass( 'inited' )
			.on( 'focus', function() {
				if ( ! $body.hasClass( 'show_outline' ) ) {
					$body.addClass( 'show_outline' );
				}
			} )
			.on( 'click', function() {
				var id = jQuery(this).attr('href');
				jQuery(id).focus();
			} );


		// Detect the keyboard usage to show outline around links and fields
		//-------------------------------------------------------------------
		$body.on( 'keydown', 'a,input,textarea,select,span[tabindex]', function( e ) {
			if ( 9 == e.which ) {
				if ( ! $body.hasClass( 'show_outline' ) ) {
					$body.addClass( 'show_outline' );
				}
			}
		} );


		// Init background video
		//------------------------------------
		
		// Use Bideo to play local video
		var $top_panel_with_bg_video = jQuery( '.top_panel.with_bg_video' );
		if (GREENY_STORAGE['background_video'] && $top_panel_with_bg_video.length > 0 && window.Bideo) {
			// Waiting 10ms after mejs init
			setTimeout(
				function() {
					$top_panel_with_bg_video.prepend( '<video id="background_video" loop muted></video>' );
					var bv = new Bideo();
					bv.init(
						{
							// Video element
							videoEl: document.querySelector( '#background_video' ),

							// Container element
							container: document.querySelector( '.top_panel' ),

							// Resize
							resize: true,

							// autoplay: false,

							isMobile: window.matchMedia( '(max-width: 768px)' ).matches,

							playButton: document.querySelector( '#background_video_play' ),
							pauseButton: document.querySelector( '#background_video_pause' ),

							// Array of objects containing the src and type
							// of different video formats to add
							// For example:
							//	src: [
							//			{	src: 'night.mp4', type: 'video/mp4' }
							//			{	src: 'night.webm', type: 'video/webm;codecs="vp8, vorbis"' }
							//		]
							src: [
							{
								src: GREENY_STORAGE['background_video'],
								type: 'video/' + greeny_get_file_ext( GREENY_STORAGE['background_video'] )
							}
							],

							// What to do once video loads (initial frame)
							onLoad: function () {
								//document.querySelector('#background_video_cover').style.display = 'none';
							}
						}
					);
				}, 10
			);

			// Use Tubular to play video from Youtube
		} else if (jQuery.fn.tubular) {
			jQuery( 'div#background_video' ).each(
				function() {
					var $self = jQuery( this );
					var youtube_code = $self.data( 'youtube-code' );
					if (youtube_code) {
						$self.tubular( {videoId: youtube_code} );
						jQuery( '#tubular-player' ).appendTo( $self ).show();
						jQuery( '#tubular-container,#tubular-shield' ).remove();
					}
				}
			);
		}

		// Tabs
		//------------------------------------
		if ( jQuery.ui && jQuery.ui.tabs ) {
			jQuery( '.greeny_tabs:not(.inited)' ).each(
				function () {
					var $self = jQuery( this );
					// Get initially opened tab
					var init = $self.data( 'active' );
					if (isNaN( init )) {
						init       = 0;
						var active = $self.find( '> ul > li[data-active="true"]' ).eq( 0 );
						if (active.length > 0) {
							init = active.index();
							if (isNaN( init ) || init < 0) {
								init = 0;
							}
						}
					} else {
						init = Math.max( 0, init );
					}
					// Init tabs
					$self.addClass( 'inited' ).tabs(
						{
							active: init,
							show: {
								effect: 'fadeIn',
								duration: 300
							},
							hide: {
								effect: 'fadeOut',
								duration: 300
							},
							create: function( event, ui ) {
								if ( ui.panel.length > 0 && ! ready_busy ) {
									$document.trigger( 'action.init_hidden_elements', [ui.panel] );
								}
							},
							activate: function( event, ui ) {
								if ( ui.newPanel.length > 0 && ! ready_busy ) {
									$document.trigger( 'action.init_hidden_elements', [ui.newPanel] );
									$window.trigger('resize');
								}
							}
						}
					);
				}
			);
		}

		// AJAX loader for the tabs
		jQuery( '.greeny_tabs_ajax' ).on(
			"tabsbeforeactivate", function( event, ui ) {
				if (ui.newPanel.data( 'need-content' )) {
					greeny_tabs_ajax_content_loader( ui.newPanel, 1, ui.oldPanel );
				}
			}
		);

		// AJAX loader for the pages in the tabs
		jQuery( '.greeny_tabs_ajax' ).on(
			"click", '.nav-links a', function(e) {
				var $self = jQuery( this );
				var panel = $self.parents( '.greeny_tabs_content' );
				var page  = 1;
				var href  = $self.attr( 'href' );
				var pos   = -1;
				if ((pos = href.lastIndexOf( '/page/' )) != -1 ) {
					page = Number( href.substr( pos + 6 ).replace( "/", "" ) );
					if ( ! isNaN( page )) {
						page = Math.max( 1, page );
					}
				}
				greeny_tabs_ajax_content_loader( panel, page );
				e.preventDefault();
				return false;
			}
		);


		// Accordion
		//----------------------------------
		if (jQuery.ui && jQuery.ui.accordion) {
			jQuery( '.greeny_accordion:not(.inited)' )
				.addClass( 'inited' )
				.accordion( {
					'header': '.greeny_accordion_title',
					'heightStyle': 'content',
					'create': function( event, ui ) {
						if ( ui.panel.length > 0 && ! ready_busy ) {
							$document.trigger( 'action.init_hidden_elements', [ui.panel] );
						}
					},
					'activate': function( event, ui ) {
						if ( ui.newPanel.length > 0 && ! ready_busy ) {
							$document.trigger( 'action.init_hidden_elements', [ui.newPanel] );
							$window.trigger('resize');
						}
					}
				} );
		}


		// Sidebar open/close
		//----------------------------------------------
		jQuery( '.sidebar_control' ).on(
			'click', function(e){
				var $self = jQuery( this ),
					$parent = $self.parent();
				$parent.toggleClass( 'opened' );
				if ( $body.hasClass('sidebar_small_screen_above') ) {
					var $next = $self.next();
					$next.slideToggle();
					if ( $parent.hasClass( 'opened' ) ) {
						setTimeout( function() {
							$document.trigger( 'action.init_hidden_elements', [$next] );
						}, 310 );
					}
				}
				e.preventDefault();
				return false;
			}
		);



		// Menu
		//----------------------------------------------

		// Keyboard navigation in the menus
		jQuery( '.sc_layouts_menu li > a' ).on( 'keydown', function(e) {
			var handled = false,
				link = jQuery( this ),
				li = link.parent(),
				ul = li.parent(),
				li_parent = ul.parent().prop( 'tagName' ) == 'LI' ? ul.parent() : false,
				item = false;
			if ( 32 == e.which ) {								// Space
				link.trigger( 'click' );
				handled = true;
			} else if ( 27 == e.which ) {						// Esc
				if ( li_parent ) {
					item = li_parent.find( '> a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				}
				handled = true;
			} else if ( 37 == e.which ) {						// Left
				if ( li_parent ) {
					item = li_parent.find( '> a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				} else if ( li.index() > 0 ) {
					item = li.prev().find( '> a' );
					if ( item.length > 0 ) {
						item.eq(0).focus();
					}
				}
				handled = true;
			} else if ( 38 == e.which ) {						// Top
				if ( li.index() > 0 ) {
					item = li.prev().find( '> a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				} else if ( li_parent ) {
					item = li_parent.find( '> a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				}
				handled = true;
			} else if ( 39 == e.which ) {						// Right
				if ( li_parent ) {
					if ( li.find( '> ul' ).length == 1 ) {
						item = li.find( '> ul > li:first-child a' );
						if ( item.length > 0 ) {
							item.get(0).focus();
						}
					}
				} else if ( li.next().prop( 'tagName' ) == 'LI' ) {
					item = li.next().find( '> a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				}
				handled = true;
			} else if ( 40 == e.which ) {						// Bottom
				if ( li_parent || li.find( '> ul' ).length === 0 ) {
					if ( li.next().prop( 'tagName' ) == 'LI' ) {
						item = li.next().find( '> a' );
						if ( item.length > 0 ) {
							item.get(0).focus();
						}
					}
				} else if ( li.find( '> ul' ).length == 1 ) {
					item = li.find( '> ul > li:first-child a' );
					if ( item.length > 0 ) {
						item.get(0).focus();
					}
				}
				handled = true;
			}
			if ( handled ) {
				if ( ! $body.hasClass( 'show_outline' ) ) {
					$body.addClass( 'show_outline' );
				}
				e.preventDefault();
				return false;
			}
			return true;
		} );

		// Open/Close side menu
		jQuery( '.menu_side_button' ).on(
			'click', function(e){
				jQuery( this ).parent().toggleClass( 'opened' );
				e.preventDefault();
				return false;
			}
		);

		// Add images to the menu items with classes image-xxx
		jQuery( '.sc_layouts_menu li[class*="image-"]' ).each(
			function() {
				var $self   = jQuery( this );
				var classes = $self.attr( 'class' ).split( ' ' );
				var icon    = '';
				for (var i = 0; i < classes.length; i++) {
					if (classes[i].indexOf( 'image-' ) >= 0) {
						icon = classes[i].replace( 'image-', '' );
						break;
					}
				}
				if (icon) {
					$self.find( '>a' ).css( 'background-image', 'url(' + GREENY_STORAGE['theme_url'] + 'trx_addons/css/icons.png/' + icon + '.png' );
				}
			}
		);

		// Add arrows to the mobile menu
		jQuery( '.menu_mobile .menu-item-has-children > a,.sc_layouts_menu_dir_vertical .menu-item-has-children > a' ).append( '<span class="open_child_menu"></span>' );

		// Open/Close mobile menu
		function greeny_mobile_menu_open() {
			var $menu = jQuery( '.menu_mobile' );
			$menu
				.addClass( 'opened' )
				.prev( '.menu_mobile_overlay' ).fadeIn();
			$body.addClass( 'menu_mobile_opened' );
			$document
				.trigger( 'action.stop_wheel_handlers' )
				.trigger( 'action.mobile_menu_open', [$menu] );
		}
		function greeny_mobile_menu_close() {
			var $menu = jQuery( '.menu_mobile' );
			$document.trigger( 'action.mobile_menu_close', [$menu] );
			setTimeout( function() {
				$menu
					.removeClass( 'opened' )
					.prev( '.menu_mobile_overlay' ).fadeOut();
				$body.removeClass( 'menu_mobile_opened' );
				$document.trigger( 'action.start_wheel_handlers' );
			}, greeny_apply_filters( 'greeny_filter_mobile_menu_close_timeout', 0, $menu ) );
		}
		jQuery( '.sc_layouts_menu_mobile_button > a,.menu_mobile_button,.menu_mobile_description' )
			.on( 'click', function(e) {
				var $self = jQuery( this );
				if ( $self.parent().hasClass( 'sc_layouts_menu_mobile_button_burger' )
					&& $self.next().hasClass( 'sc_layouts_menu_popup' )
				) {
					return;   // Not use 'return false' - it break event bubble and popup is not open
				}
				greeny_mobile_menu_open();
				e.preventDefault();
				return false;
			}
		);
		$document
			.on( 'keyup', function(e) {
				if ( e.keyCode == 27 ) {
					if ( jQuery( '.menu_mobile.opened' ).length == 1 ) {
						greeny_mobile_menu_close();
						e.preventDefault();
						return false;
					}
				}
			}
		);
		
		$document.on( 'action.trx_addons_inner_links_click', function( e, link_obj, original_e ) {
			if ( $body.hasClass( 'menu_mobile_opened' ) ) {
				// Removing class before menu closing animations started
				// is need to animate inner links and scrolling to the anchor
				$body.removeClass( 'menu_mobile_opened' );
				greeny_mobile_menu_close();
			}
		} );


		jQuery( '.menu_mobile_close, .menu_mobile_overlay' )
			.on( 'click', function(e){
				greeny_mobile_menu_close();
				e.preventDefault();
				return false;
			} );
		jQuery( '.menu_mobile_close' )
			.on( 'keyup', function(e) {
				if (e.keyCode == 13) {
					if (jQuery( '.menu_mobile.opened' ).length == 1) {
						greeny_mobile_menu_close();
						e.preventDefault();
						return false;
					}
				}
			} )
			.on( 'focus', function() {
				if ( ! $body.hasClass( 'menu_mobile_opened' ) ) {
					jQuery( '#content_skip_link_anchor' ).focus();
				}
			} );

		// Open/Close mobile submenu
		jQuery( '.menu_mobile,.sc_layouts_menu_dir_vertical:not([class*="sc_layouts_submenu_"]),.sc_layouts_menu.sc_layouts_submenu_dropdown' )
			.on( 'click', 'li a, li a .open_child_menu', function(e) {
				var $self = jQuery( this );
				var $a    = $self.hasClass( 'open_child_menu' ) ? $self.parent() : $self;
				if ($a.parent().hasClass( 'menu-item-has-children' )) {
					if ($a.attr( 'href' ) == '#' || $self.hasClass( 'open_child_menu' )) {
						if ($a.siblings( 'ul:visible' ).length > 0) {
							$a.siblings( 'ul' ).slideUp().parent().removeClass( 'opened' );
						} else {
							$self.parents( 'li' ).eq(0).siblings( 'li' ).find( 'ul.sub-menu:visible,ul.sc_layouts_submenu:visible' ).slideUp().parent().removeClass( 'opened' );
							$a.siblings( 'ul' ).slideDown(
								function() {
									var $self = jQuery( this );
									// Init layouts
									if ( ! $self.hasClass( 'layouts_inited' ) && $self.parents( '.menu_mobile' ).length > 0) {
										$self.addClass( 'layouts_inited' );
										$document.trigger( 'action.init_hidden_elements', [$self] );
									}
								}
							).parent().addClass( 'opened' );
						}
					}
				}
				if ( ! $self.hasClass( 'open_child_menu' ) && $self.parents( '.menu_mobile' ).length > 0 && greeny_is_local_link( $a.attr( 'href' ) )) {
					jQuery( '.menu_mobile_close' ).trigger( 'click' );
				}
				if ( $self.hasClass( 'open_child_menu' ) || $a.attr( 'href' ) == '#' ) {
					e.preventDefault();
					return false;
				}
			} )
			.on( 'keyup', 'li a', function(e) {
				if ( e.keyCode == 9 ) {
					jQuery(this).find('.open_child_menu').trigger('click');
				}
			} );

		if ( ! GREENY_STORAGE['trx_addons_exist'] || jQuery( '.top_panel.top_panel_default .sc_layouts_menu_default' ).length > 0) {
			// Init superfish menus
			greeny_init_sfmenu( '.sc_layouts_menu:not(.inited):not(.sc_layouts_submenu_dropdown) > ul:not(.inited)' );
			// Show menu
			jQuery( '.sc_layouts_menu:not(.inited)' ).each(
				function() {
					var $self = jQuery( this );
					if ( $self.find( '>ul.inited' ).length == 1 ) {
						$self.addClass( 'inited' );
					}
				}
			);
			// Generate 'scroll' event after the menu is showed
			$window.trigger( 'scroll' );
		}


		// Pagination
		//------------------------------------

		// Infinite scroll in the blog streampages
		$document.on(
			'action.scroll_greeny', function(e) {
				var inf = jQuery( '.nav-links-infinite:not(.all_items_loaded)' );
				if (inf.length === 0) {
					return;
				}
				var container = $content.find( '> .posts_container,> .blog_archive > .posts_container,> .greeny_tabs > .greeny_tabs_content:visible > .posts_container' ).eq( 0 );
				if (container.length == 1 && container.offset().top + container.height() < greeny_window_scroll_top() + greeny_window_height() * 1.5) {
					inf.find( 'a' ).trigger( 'click' );
				}
			}
		);

		// Infinite scroll in the single posts
		GREENY_STORAGE['cur_page_url']   = location.href;
		GREENY_STORAGE['cur_page_title'] = jQuery('.sc_layouts_title_caption').eq(0).text() || jQuery('head title').eq(0).text() || '';
		$document.on(
			'action.scroll_greeny', function(e) {
				if ( $single_post_scrollers.length === 0 ) {
					return;
				}

				var container      = GREENY_STORAGE['which_block_load'] == 'article'
										? $content.eq( 0 )
										: $page_content_wrap.eq( 0 ),
					cur_page_link  = GREENY_STORAGE['cur_page_url'],
					cur_page_title = GREENY_STORAGE['cur_page_title'];

				$single_post_scrollers.each( function() {
					var inf  = jQuery(this),
						link = inf.data('post-link'),
						off  = inf.offset().top,
						st   = greeny_window_scroll_top(),
						wh   = greeny_window_height();
					
					// Change location url
					if (inf.hasClass('nav-links-single-scroll-loaded')) {
						if (link && off < st + wh / 2) {
							cur_page_link  = link;
							cur_page_title = inf.data('post-title');
						}

					// Load next post
					} else if ( ! inf.hasClass('greeny_loading') && link && off < st + wh * 2) {
						greeny_add_to_read_list( container.find( '.previous_post_content:last-child > article[data-post-id]').data('post-id'));
						inf.addClass('greeny_loading');
						jQuery.get( greeny_add_to_url( link, { 'action': 'prev_post_loading' } ) ).done(
							function( response ) {
								// Get inline styles and add to the page styles
								greeny_import_inline_styles( response );
								// Get tags 'link' from response and add its to the 'head'
								greeny_import_tags_link( response );
								// Get article and add it to the page
								var $response = jQuery( response ),
									$response_page_content_wrap = $response.find( '.page_content_wrap' ),
									$response_content = $response.find( '.content' ),
									$response_sidebar = $response.find( '.sidebar' ),
									$response_post_content = GREENY_STORAGE['which_block_load'] == 'article'
																? $response_content
																: $response_page_content_wrap;
								if ( $response_post_content.length > 0 ) {
									var html = $response_post_content.html(),
										response_body_classes = GREENY_STORAGE['which_block_load'] == 'article'
																? null
																: response.match(/<body[^>]*class="([^"]*)"/);
									if ( GREENY_STORAGE['which_block_load'] == 'wrapper' ) {
										if ( $response_sidebar.length === 0
											&& ! response_body_classes
											&& ! $body.hasClass( 'expand_content' )
											&& ! $body.hasClass( 'narrow_content' )
										) {
											$response_post_content.find( '.content' ).width( '100%' );
											html = $response_post_content.html();
										} else if ( $response_sidebar.length > 0 && $body.hasClass( 'narrow_content' ) ) {
											$response_post_content.find( '.post_item_single.post_type_post' ).width( '100%' );
											html = $response_post_content.html();
										}
									}
									container.append(
										'<div class="previous_post_content'
														+ ( response_body_classes
															? ' ' + response_body_classes[1]
															: '' )
														+ ( $response_page_content_wrap.attr( 'data-single-style' ) !== undefined
																? ' single_style_' + $response_page_content_wrap.attr( 'data-single-style' )
																: ''
															)
										+ '">'
											+ html
										+ '</div>'
									);
									inf.removeClass('greeny_loading').addClass( 'nav-links-single-scroll-loaded' );
									// Remove TOC if exists (rebuild on init_hidden_elements)
									jQuery( '#toc_menu' ).remove();
									// Trigger actions to init new elements
									GREENY_STORAGE['init_all_mediaelements'] = true;
									$document
										.trigger( 'action.new_post_added' )
										.trigger( 'action.init_hidden_elements', [container] );
									$window.trigger( 'resize' );
								}
								$document.trigger('action.got_ajax_response', {
									action: 'prev_post_loading',
									result: response
								});
							}
						);
					}						
				} );
				if (cur_page_link != location.href) {
					greeny_document_set_location(cur_page_link);
					jQuery( '.sc_layouts_title_caption,head title' ).html( cur_page_title );
				}
			}
		);

		// Mark single post as readed
		if ( $body.hasClass('single')) {
			greeny_add_to_read_list(jQuery('.content > article[data-post-id]').data('post-id'));
		}

		// Mark readed posts
		$document.on(
			'action.init_hidden_elements', function(e, cont) {
				var read_list = greeny_get_storage('greeny_post_read');
				if ( read_list && read_list.charAt(0) == '[' ) {
					read_list = JSON.parse(read_list);
					for (var p=0; p<read_list.length; p++) {
						var read_post = cont.find('[data-post-id="'+read_list[p]+'"]');
						if (!read_post.addClass('full_post_read') && !read_post.parent().hasClass('content')) {
							read_post.addClass('full_post_read');
						}
					}
				}
			}
		);

		// Open single post right in the blog or in the shortcode "Blogger"
		jQuery( '.posts_container,.sc_blogger_content.sc_item_posts_container' ).on( 'click', 'a', function(e) {
			var link = jQuery(this),
				link_url = link.attr( 'href' ),
				post = link.parents( '.post_item,.sc_blogger_item' ).eq(0),
				post_url = post.find( '.post_title > a,.entry-title > a' ).attr( 'href' ),
				posts_container = post.parents('.posts_container,.sc_item_posts_container').eq(0);
			if ( link_url && post_url && link_url == post_url
				&& ( posts_container.hasClass('open_full_post') || GREENY_STORAGE['open_full_post'] )
				&& ! posts_container.hasClass('no_open_full_post')
				&& ! posts_container.hasClass('columns_wrap')
				&& ! posts_container.hasClass('masonry_wrap')
				&& posts_container.find('.sc_blogger_grid_wrap').length === 0
				&& posts_container.find('.masonry_wrap').length === 0
				&& posts_container.parents('.wp-block-columns').length === 0
				&& ( posts_container.parents('.wpb_column').length === 0 || posts_container.parents('.wpb_column').eq(0).hasClass('vc_col-sm-12') )
				&& ( posts_container.parents('.elementor-column').length === 0 || posts_container.parents('.elementor-column').eq(0).hasClass('elementor-col-100') )
			) {
				posts_container.find('.full_post_opened').removeClass('full_post_opened').show();
				posts_container.find('.full_post_content').remove();
				post.addClass('full_post_loading');
				jQuery.get( greeny_add_to_url( post_url, { 'action': 'full_post_loading' } ) ).done( function( response ) {
					if ( response ) {
						var $response = jQuery( response );
						var post_content = $response.find('.content');
						if ( post_content.length > 0 ) {
							// Get inline styles from response
							var inline_styles = response.match( /<style[^>]*id="trx_addons-inline-styles-inline-css"[^>]*>([^<]*)<\/style>/ );
							if ( inline_styles ) {
								jQuery( '#trx_addons-inline-styles-inline-css' ).append( inline_styles[1] );
							}
							// Get tags 'link' from response and add its to the 'head'
							greeny_import_tags_link( response );
							// Animate to posts_container
							var cs = post.offset().top - ( post.parents('.posts_container').length > 0 ? 100 : 200 );
							greeny_document_animate_to( cs );
							// Insert new content
							post.after( 
									'<div class="full_post_content">'
										+ '<button class="full_post_close" data-post-url="' + post_url + '"></button>'
										+ post_content.html()
									+ '</div>'
								)
								.removeClass('full_post_loading')
								.addClass('full_post_opened')
								.hide()	// instead .slideUp('fast')
								.next().slideDown( 'slow', function() {
									// Trigger actions to init new elements after posts_container is shown
									GREENY_STORAGE['init_all_mediaelements'] = true;
									$document.trigger( 'action.init_hidden_elements', [posts_container] );
									$window.trigger( 'resize' );
								} );
							greeny_full_post_read_change_state();
							// Close full post content on click
							post.next().find('.full_post_close')
								.on( 'click', function(e) {
									var content = jQuery(this).parent(),
										cs = content.offset().top - (content.parents('.posts_container').length > 0 ? 100 : 200),
										post = content.prev();
									content.remove();
									greeny_full_post_read_change_state();
									post
										.removeClass('full_post_opened')
										.slideDown();
									greeny_document_animate_to( cs, 0 );
									e.preventDefault();
									return false;
								} );
							// Remove TOC if exists (rebuild on init_hidden_elements)
							jQuery( '#toc_menu' ).remove();
						}
						$document.trigger('action.got_ajax_response', {
							action: 'full_post_loading',
							result: response
						} );
					}
				} );
				e.preventDefault();
				return false;
			}
		} );


		// Comments
		//------------------------------------

		// Scroll to comments (if hidden)
		if ( location.hash == '#comments' || location.hash == '#respond' ) {
			var $show_comments_button = jQuery( '.show_comments_button' );
			if ( $show_comments_button.length == 1 && ! $show_comments_button.hasClass( 'opened' ) ) {
				$show_comments_button.trigger( 'click' );
				greeny_document_animate_to( location.hash );
			}
		}

		// Other settings
		//------------------------------------
		$document.trigger( 'action.ready_greeny' );

		// Blocks with stretch width
		//----------------------------------------------
		// Action to prepare stretch blocks in the third-party plugins
		$document.trigger( 'action.prepare_stretch_width' );
		// Wrap stretch blocks
		$stretch_width = jQuery( '.trx-stretch-width' );
		$stretch_width.wrap( '<div class="trx-stretch-width-wrap"></div>' );
		$stretch_width.after( '<div class="trx-stretch-width-original"></div>' );
		greeny_stretch_width();

		// Add theme-specific handlers on 'action.init_hidden_elements'
		//---------------------------------------------------------------
		$document.on( 'action.init_hidden_elements', greeny_init_post_formats );
		$document.on( 'action.init_hidden_elements', greeny_add_toc_to_sidemenu );

 		// Init hidden elements
		$document.trigger( 'action.init_hidden_elements', [$body.eq(0)] );

	} // greeny_ready_actions

	
	// Post formats init
	//=====================================================
	function greeny_init_post_formats(e, cont) {

		// Wrap select with .select_container
		greeny_add_select_container( cont );

		// Masonry posts
		cont.find( greeny_apply_filters( 'greeny_filter_masonry_wrap', '.masonry_wrap' ) ).each( function() {
			var masonry_wrap = jQuery( this );
			if ( masonry_wrap.parents( 'div:hidden,article:hidden' ).length > 0) return;
			if ( ! masonry_wrap.hasClass( 'inited' ) ) {
				masonry_wrap.addClass( 'inited' );
				greeny_when_images_loaded( masonry_wrap, function() {
					setTimeout( function() {
									masonry_wrap.masonry( {
										itemSelector: greeny_apply_filters( 'greeny_filter_masonry_item', '.masonry_item' ),
										columnWidth: greeny_apply_filters( 'greeny_filter_masonry_item', '.masonry_item' ),
										percentPosition: true
									} );
									$window.trigger('resize');
									$window.trigger('scroll');
								}, greeny_apply_filters( 'greeny_filter_masonry_init', 10 ) );
				});
			} else {
				setTimeout( function() {
					masonry_wrap.masonry();   // Relayout after activate tab
				}, greeny_apply_filters( 'greeny_filter_masonry_reinit', 510 ) );
			}
		});

		// Load more
		cont.find( '.nav-load-more:not(.inited)' )
			.addClass( 'inited' )
			.on( 'click', function(e) {
				if (GREENY_STORAGE['load_more_link_busy']) {
					return false;
				}
				var more     = jQuery( this );
				var page     = Number( more.data( 'page' ) );
				var max_page = Number( more.data( 'max-page' ) );
				if (page >= max_page) {
					more.parent().addClass( 'all_items_loaded' ).hide();
					return false;
				}
				more.parent().addClass( 'loading' );
				GREENY_STORAGE['load_more_link_busy'] = true;

				var panel = more.parents( '.greeny_tabs_content' );

				// Load simple page content
				if (panel.length === 0) {
					jQuery.get(
						location.href, {
							paged: page + 1
						}
					).done(
						function(response) {
							// Get inline styles and add to the page styles
							greeny_import_inline_styles( response );
							// Get tags 'link' from response and add its to the 'head'
							greeny_import_tags_link( response );
							// Get new posts and append to the .posts_container
							var $response = jQuery( response );
							var $response_posts_container = $response.find('.content .posts_container');
							if ( $response_posts_container.length === 0 ) {
								$response_posts_container = $response.find('.posts_container');
							}
							if ( $response_posts_container.length > 0 ) {
								greeny_loadmore_add_items(
									$content.find( '.posts_container' ).eq( 0 ),
									$response_posts_container.find(
										'> .masonry_item,'
										+ '> div[class*="column-"],'
										+ '> article'
									)
								);
							}
							$document.trigger('action.got_ajax_response', {
								action: 'load_more_next_page',
								result: response
							});
						}
					);

					// Load tab's panel content
				} else {
					jQuery.post(
						GREENY_STORAGE['ajax_url'], {
							nonce: GREENY_STORAGE['ajax_nonce'],
							action: 'greeny_ajax_get_posts',
							blog_template: panel.data( 'blog-template' ),
							blog_style: panel.data( 'blog-style' ),
							posts_per_page: panel.data( 'posts-per-page' ),
							cat: panel.data( 'cat' ),
							parent_cat: panel.data( 'parent-cat' ),
							post_type: panel.data( 'post-type' ),
							taxonomy: panel.data( 'taxonomy' ),
							page: page + 1
						}
					).done(
						function(response) {
							var rez = {};
							try {
								rez = JSON.parse( response );
							} catch (e) {
								rez = { error: GREENY_STORAGE['msg_ajax_error'] };
								console.log( response );
							}
							if (rez.error !== '') {
								panel.html( '<div class="greeny_error">' + rez.error + '</div>' );
							} else {
								// Get inline styles and add to the page styles
								if ( rez.css !== '' ) {
									greeny_import_inline_styles( '<style id="greeny-inline-styles-inline-css">' + rez.css + '</style>' );
								}
								// Get new posts and append to the .posts_container
								greeny_loadmore_add_items(
									panel.find( '.posts_container' ),
									jQuery( rez.data ).find(
										'> .masonry_item,'
										+ '> div[class*="column-"],'
										+ '> article'
									)
								);
							}
							$document.trigger('action.got_ajax_response', {
								action: 'greeny_ajax_get_posts',
								result: rez,
								panel: panel
							});
						}
					);
				}

				// Append items to the container
				function greeny_loadmore_add_items(container, items) {
					if (container.length > 0 && items.length > 0) {
						items.addClass( 'just_loaded_items' );
						container.append( items );
						$document.trigger('action.after_add_content', [container]);
						var just_loaded_items = container.find( '.just_loaded_items' );
						if ( container.hasClass( 'masonry_wrap' ) ) {
							just_loaded_items.addClass( 'hidden' );
							greeny_when_images_loaded(
								just_loaded_items, function() {
									just_loaded_items.removeClass( 'hidden' );
									container.masonry( 'appended', items ).masonry();
									// Remove TOC if exists (rebuild on init_hidden_elements)
									jQuery( '#toc_menu' ).remove();
									// Trigger actions to init new elements
									GREENY_STORAGE['init_all_mediaelements'] = true;
									$document.trigger( 'action.init_hidden_elements', [container.parent()] );
								}
							);
						} else {
							just_loaded_items.removeClass( 'just_loaded_items hidden' );
							// Remove TOC if exists (rebuild on init_hidden_elements)
							jQuery( '#toc_menu' ).remove();
							// Trigger actions to init new elements
							GREENY_STORAGE['init_all_mediaelements'] = true;
							$document.trigger( 'action.init_hidden_elements', [container.parent()] );
						}
						more.data( 'page', page + 1 ).parent().removeClass( 'loading' );
					}
					if (page + 1 >= max_page) {
						more.parent().addClass( 'all_items_loaded' ).hide();
					}
					GREENY_STORAGE['load_more_link_busy'] = false;
					// Fire 'window.resize'
					$window.trigger( 'resize' );
					// Fire 'window.scroll'
					$window.trigger( 'scroll' );
				}

				e.preventDefault();
				return false;
			}
		);

		// MediaElement init
		greeny_init_media_elements( cont );

		// Video play button
		cont.find( '.post_featured.with_thumb .post_video_hover:not(.post_video_hover_popup):not(.inited)' )
			.addClass( 'inited' )
			.on( 'click', function(e) {
				var $self = jQuery( this ),
					$post_featured = $self.parents( '.post_featured' ).eq(0);
				$post_featured
					.addClass( 'post_video_play' )
					.find( '.post_video' ).html( $self.data( 'video' ) );
				$document.trigger( 'action.init_hidden_elements', [ $post_featured ] );
				$window.trigger( 'resize' );
				e.preventDefault();
				return false;
			} )
			.parents('.post_featured')
			.on( 'click', function(e) {
				var $self = jQuery(this);
				if ( ! $self.hasClass('post_video_play') && ! jQuery(e.target).is('a') ) {
					jQuery(this).find('.post_video_hover').trigger('click');
					e.preventDefault();
					return false;
				}
			} );


		// Comments
		//------------------------------------

		// Show/Hide Comments
		jQuery( '.show_comments_button:not(.inited)' )
			.addClass( 'inited' )
			.on( 'click', function(e) {
				var bt = jQuery(this);
				if ( bt.attr( 'href' ) == '#' ) {
					bt.toggleClass( 'opened' ).text( bt.data( bt.hasClass( 'opened' ) ? 'hide' : 'show' ) );
					var $comments_wrap = bt.parent().next();	//jQuery( '.comments_wrap' )
					$comments_wrap.slideToggle( function() {
						$comments_wrap.toggleClass( 'opened' );
						$window.trigger( 'scroll' );
					});
					e.preventDefault();
					return false;
				}
			} );

		// Show comments block when link '#comments' or '#respond' is clicked
		jQuery( 'a[href$="#comments"]:not(.inited),a[href$="#respond"]:not(.inited)' )
			.addClass( 'inited' )
			.on( 'click', function(e) {
				var $self = jQuery(this);
				if ( greeny_is_local_link( $self.attr( 'href') ) ) {
					var $prev_post_content = $self.parents( '.previous_post_content' ),
						$comments_wrap = $prev_post_content.length
											? $prev_post_content.find( '.comments_wrap' ).eq(0)
											: jQuery( '.comments_wrap' ).eq(0),
						$show_comments_button = $comments_wrap.prev().find('.show_comments_button ');
					if ( $comments_wrap.length ) {
						if ( $comments_wrap.css( 'display' ) == 'none' ) {
							if ($show_comments_button.length ) {
								$show_comments_button.trigger( 'click' );
							}
						}
						if ($show_comments_button.length ) {
							greeny_document_animate_to( $show_comments_button.offset().top );
						}
					}
				}
			} );

		// Checkbox with "I agree..."
		var $i_agree = jQuery('input[type="checkbox"][name="i_agree_privacy_policy"]:not(.inited)'
							+ ',input[type="checkbox"][name="gdpr_terms"]:not(.inited)'
							+ ',input[type="checkbox"][name="wpgdprc"]:not(.inited)'
							+ ',input[type="checkbox"][name="AGREE_TO_TERMS"]:not(.inited)'
							+ ',input[type="checkbox"][name="acceptance"]:not(.inited)'
							);
		if ( $i_agree.length > 0 ) {
			if ( true ) {	// New way: On click Submit button - checkbox is checked
				$i_agree.each( function() {
					var chk = jQuery( this ),
						form = chk.parents('form');
					chk.addClass( 'inited' );
					form.find( 'button,input[type="submit"]' ).on( 'click', function(e) {
						if ( ! chk.get(0).checked ) {
							form.find('.trx_addons_message_box').remove();
							form.append(
								'<div class="trx_addons_message_box trx_addons_message_box_error">'
									+ GREENY_STORAGE['msg_i_agree_error']
								+ '</div>'
							);
							var error_msg = form.find('.trx_addons_message_box');
							error_msg.fadeIn();
							setTimeout( function() {
								error_msg.fadeOut( function() {
									error_msg.remove();
								} );
							}, 3000 );
							e.preventDefault();
							return false;
						}
					} );
				} );
			} else {		// Old way: Disable Submit button while checkbox is not checked
				$i_agree
					.addClass('inited')
					.on('change', function(e) {
						var $self = jQuery(this),
							$bt   = $self.parents('form').find('button,input[type="submit"]');
						if ($self.get(0).checked) {
							$bt.removeAttr('disabled');
						} else {
							$bt.attr('disabled', 'disabled');
						}
					})
					.trigger('change');
			}
		}
	}  // greeny_init_post_formats


	// Wrap select with .select_container
	function greeny_add_select_container( cont ) {
		cont.find( 'select:not(.esg-sorting-select):not([class*="trx_addons_attrib_"]):not([size])' ).each(
			function() {
				var $self = jQuery( this );
				if ( $self.css( 'display' ) != 'none'
					&& $self.parents( '.select_container' ).length === 0
					&& ! $self.next().hasClass( 'select2' )
					&& ! $self.hasClass( 'select2-hidden-accessible' )) {
						$self.wrap( '<div class="select_container"></div>' );
						// Bubble submit() up for widget "Categories"
						if ( $self.parents( '.widget_categories' ).length > 0 ) {
							$self.parent().get(0).submit = function() {
								jQuery(this).closest('form').eq(0).submit();
							};
						}
				}
			}
		);
	}
	// Add select_container on 'ajaxComplete'
	$document.on( 'ajaxComplete', function(e) {
		// Trigger event after timeout to allow other scripts add elements on the page
		setTimeout( function() {
			greeny_add_select_container( $body );
		}, 100 );
	} );


	// Init media elements
	GREENY_STORAGE['mejs_attempts'] = 0;
	function greeny_init_media_elements(cont) {
		var audio_selector = greeny_apply_filters( 'greeny_filter_mediaelements_audio_selector', 'audio:not(.inited)' ),
			video_selector = greeny_apply_filters( 'greeny_filter_mediaelements_video_selector', 'video:not(.inited):not([nocontrols]):not([controls="0"]):not([controls="false"]):not([controls="no"])' ),	//:not([autoplay])
			media_selector = audio_selector + ( audio_selector && video_selector ? ',' : '') + video_selector;
		if (GREENY_STORAGE['use_mediaelements'] && cont.find( media_selector ).length > 0) {
			if ( window.mejs ) {
				if (window.mejs.MepDefaults) {
					window.mejs.MepDefaults.enableAutosize = true;
				}
				if (window.mejs.MediaElementDefaults) {
					window.mejs.MediaElementDefaults.enableAutosize = true;
				}
				// Disable init for video[autoplay]
				cont.find(
						  // Old shortcode 'wp-video'
						  'video.wp-video-shortcode[autoplay],'
						+ 'video.wp-video-shortcode[nocontrols],'
						+ 'video.wp-video-shortcode[controls="0"],'
						+ 'video.wp-video-shortcode[controls="false"],'
						+ 'video.wp-video-shortcode[controls="no"],'
						// New block 'video'
						+ '.wp-block-video > video[autoplay],'
						+ '.wp-block-video > video[nocontrols],'
						+ '.wp-block-video > video[controls="0"],'
						+ '.wp-block-video > video[controls="false"],'
						+ '.wp-block-video > video[controls="no"]'
						)
					.removeClass('wp-video-shortcode');
				// Init mediaelements
				cont.find( media_selector ).each(
					function() {
						var $self = jQuery( this );
						// If item now invisible
						if ($self.parents( 'div:hidden,section:hidden,article:hidden' ).length > 0) {
							return;
						}
						if (   $self.addClass( 'inited' ).parents( '.mejs-mediaelement' ).length === 0
							&& $self.parents( '.wp-block-video' ).length === 0
							&& ! $self.hasClass( 'wp-block-cover__video-background' )
							&& $self.parents( '.elementor-background-video-container' ).length === 0
							&& $self.parents( '.elementor-widget-video' ).length === 0
							// Comment a next row if you want init mediaelements on the background video inside a layouts title
							&& $self.parents( '.sc_layouts_title' ).length === 0
							// Uncomment the next row to 
							// disable mediaelements init on the Elementor's video shortcode
							// to support a video ratio, specified in a shortcode parameters
							// && $self.parents( '.elementor-fit-aspect-ratio' ).length === 0
							&& ( GREENY_STORAGE['init_all_mediaelements']
								|| ( ! $self.hasClass( 'wp-audio-shortcode' )
									&& ! $self.hasClass( 'wp-video-shortcode' )
									&& ! $self.parent().hasClass( 'wp-playlist' )
									)
								)
						) {
							var media_cont = $self.parents('.post_video,.video_frame').eq(0);
							if ( media_cont.length === 0 ) {
								media_cont = $self.parent();
							}
							var cont_w = media_cont.length > 0 ? media_cont.width() : -1,
								cont_h = media_cont.length > 0 ? Math.floor(cont_w / 16 * 9) : -1,
								settings = {
									enableAutosize: true,
									videoWidth:     cont_w,   // if set, overrides <video width>
									videoHeight:    cont_h,   // if set, overrides <video height>
									audioWidth:     '100%',   // width of audio player
									audioHeight:    40,	      // height of audio player
									success: function(mejs) {
										if ( mejs.pluginType && 'flash' === mejs.pluginType && mejs.attributes ) {
											mejs.attributes.autoplay
												&& 'false' !== mejs.attributes.autoplay
												&& mejs.addEventListener( 'canplay', function () { mejs.play(); }, false );
											mejs.attributes.loop
												&& 'false' !== mejs.attributes.loop
												&& mejs.addEventListener( 'ended', function () { mejs.play(); }, false );
										}
									}
								};
							$self.mediaelementplayer( settings );
						}
					}
				);
			} else if ( GREENY_STORAGE['mejs_attempts']++ < 5 ) {
				setTimeout( function() { greeny_init_media_elements( cont ); }, 400 );
			}
		}
		// Init all media elements after first run
		setTimeout( function() { GREENY_STORAGE['init_all_mediaelements'] = true; }, 1000 );
	}

	// Load the tab's content
	function greeny_tabs_ajax_content_loader(panel, page, oldPanel) {
		if (panel.html().replace( /\s/g, '' ) === '') {
			var height = oldPanel === undefined ? panel.height() : oldPanel.height();
			if (isNaN( height ) || height < 100) {
				height = 100;
			}
			panel.html( '<div class="greeny_tab_holder" style="min-height:' + height + 'px;"></div>' );
		} else {
			panel.find( '> *' ).addClass( 'greeny_tab_content_remove' );
		}
		panel.data( 'need-content', false ).addClass( 'greeny_loading' );
		jQuery.post(
			GREENY_STORAGE['ajax_url'], {
				nonce: GREENY_STORAGE['ajax_nonce'],
				action: 'greeny_ajax_get_posts',
				blog_template: panel.data( 'blog-template' ),
				blog_style: panel.data( 'blog-style' ),
				posts_per_page: panel.data( 'posts-per-page' ),
				cat: panel.data( 'cat' ),
				parent_cat: panel.data( 'parent-cat' ),
				post_type: panel.data( 'post-type' ),
				taxonomy: panel.data( 'taxonomy' ),
				page: page
			}
		).done(	function( response ) {
			panel.removeClass( 'greeny_loading' );
			var rez = {};
			try {
				rez = JSON.parse( response );
			} catch (e) {
				rez = { error: GREENY_STORAGE['msg_ajax_error'] };
				console.log( response );
			}
			if (rez.error !== '') {
				panel.html( '<div class="greeny_error">' + rez.error + '</div>' );
			} else {
				// Get inline styles and add to the page styles
				if ( rez.css !== '' ) {
					greeny_import_inline_styles( '<style id="greeny-inline-styles-inline-css">' + rez.css + '</style>' );
				}
				// Append posts
				panel
					.find( '.greeny_tab_holder,.greeny_tab_content_remove' ).remove().end()
					.prepend( rez.data ).fadeIn(
						function() {
							greeny_document_animate_to('#content_skip_link_anchor');
							$document.trigger( 'action.init_hidden_elements', [panel] );
							$window.trigger( 'scroll' );
						}
					);
				$document.trigger('action.after_add_content', [panel]);
			}
			$document.trigger( 'action.got_ajax_response', {
				action: 'greeny_ajax_get_posts',
				result: rez,
				panel: panel
			} );
		} );
	} // greeny_tabs_ajax_content_loader


	// Navigation
	//==============================================

	// Init Superfish menu
	function greeny_init_sfmenu(selector) {
		jQuery( selector ).show().each(
			function() {
				var $self = jQuery( this );
				// Do not init the mobile menu - only add class 'inited'
				if ($self.addClass( 'inited' ).parents( '.menu_mobile' ).length > 0) {
					return;
				}
				var animation_in = $self.parent().data( 'animation_in' );
				if (animation_in == undefined) {
					animation_in = "none";
				}
				var animation_out = $self.parent().data( 'animation_out' );
				if (animation_out == undefined) {
					animation_out = "none";
				}
				$self.superfish(
					{
						delay: 300,
						animation: {
							opacity: 'show'
						},
						animationOut: {
							opacity: 'hide'
						},
						speed: 		animation_in != 'none' ? 500 : 200,
						speedOut:	animation_out != 'none' ? 300 : 200,
						autoArrows: false,
						dropShadows: false,
						onBeforeShow: function(ul) {
							var $self = jQuery( this );
							var par_offset = 0,
								par_width = 0,
								ul_width = 0,
								ul_height = 0;
							// Detect horizontal position (left | right)
							if ( $self.parents( "ul" ).length > 1 ) {
								var w      = $page_wrap.width();
								par_offset = $self.parents( "ul" ).eq(0).offset().left;
								par_width  = $self.parents( "ul" ).eq(0).outerWidth();
								ul_width   = $self.outerWidth();
								if (par_offset + par_width + ul_width > w - 20 && par_offset - ul_width > 0) {
									$self.addClass( 'submenu_left' );
								} else {
									$self.removeClass( 'submenu_left' );
								}
							}
							// Shift vertical if menu going out the window
							if ( $self.parents( '.top_panel' ).length > 0 ) {
								ul_height      = $self.outerHeight();
								par_offset     = 0;
								var w_height   = greeny_window_height(),
									row        = $self.parents( '.sc_layouts_row' ).eq(0),
									row_offset = 0,
									row_height = 0,
									par        = $self.parent();
								while (row.length > 0) {
									row_offset += row.outerHeight();
									if (row.hasClass( 'sc_layouts_row_fixed_on' )) {
										break;
									}
									row = row.prev();
								}
								while (par.length > 0) {
									par_offset += par.position().top + par.parent().position().top;
									row_height  = par.outerHeight();
									if (par.position().top === 0) {
										break;
									}
									par = par.parents( 'li' ).eq(0);
								}
								if (row_offset + par_offset + ul_height > w_height) {
									if (par_offset > ul_height) {
										$self.css( {
											'top': 'auto',
											'bottom': '-1.4em'
										} );
									} else {
										$self.css( {
											'top': '-' + (par_offset - row_height - 2) + 'px',
											'bottom': 'auto'
										} );
									}
								}
							}
							// Animation in
							if (animation_in != 'none') {
								$self.removeClass( 'animated faster ' + animation_out );
								$self.addClass( 'animated fast ' + animation_in );
							}
						},
						onBeforeHide: function(ul) {
							if (animation_out != 'none') {
								var $self = jQuery( this );
								$self.removeClass( 'animated fast ' + animation_in );
								$self.addClass( 'animated faster ' + animation_out );
							}
						},
						onShow: function(ul) {
							var $self = jQuery( this );
							// Init layouts
							if ( ! $self.hasClass( 'layouts_inited' )) {
								$self.addClass( 'layouts_inited' );
								$document.trigger( 'action.init_hidden_elements', [$self] );
							}
						}
					}
				);
			}
		);
	}  // greeny_init_sfmenu

	// Add TOC in the side menu
	// Make this function global because it used in the elementor.js
	function greeny_add_toc_to_sidemenu() {
		if ( jQuery( '.menu_side_inner' ).length > 0 && jQuery( '#toc_menu' ).length > 0 ) {
			jQuery( '#toc_menu' ).appendTo( '.menu_side_inner' );
			greeny_stretch_sidemenu();
		}
	}

	// Get inline styles and add to the page styles
	function greeny_import_inline_styles( response ) {
		var selector = 'greeny-inline-styles-inline-css';
		var p1       = response.indexOf( selector );
		if (p1 < 0) {
			selector = 'trx_addons-inline-styles-inline-css';
			p1       = response.indexOf( selector );
		}
		if (p1 > 0) {
			p1                 = response.indexOf( '>', p1 ) + 1;
			var p2             = response.indexOf( '</style>', p1 ),
				inline_css_add = response.substring( p1, p2 ),
				inline_css     = jQuery( '#' + selector );
			if (inline_css.length === 0) {
				$body.append( '<style id="' + selector + '" type="text/css">' + inline_css_add + '</style>' );
			} else {
				inline_css.append( inline_css_add );
			}
		}
	}

	// Get tags 'link' from response and add its to the 'head'
	function greeny_import_tags_link( response ) {
		// Get links to css from response
		var links = response.match( /<link[^>]*rel=['"]stylesheet['"][^>]*id=['"]([^'"]+)['"][^>]*>/g );
		if ( links ) {
			// Prepend its to the HEAD (if not loaded in the current page)
			var $head = jQuery( 'head' );
			for ( var i = 0; i < links.length; i++ ) {
				var matches = links[i].match( /<link[^>]*rel=['"]stylesheet['"][^>]*id=['"]([^'"]+)['"][^>]*>/ );
				if ( matches && matches.length && matches.length > 1 && matches[1] ) {
					if ( jQuery( '#' + matches[1].replace('.', '\\.') ).length === 0 ) {
						$head.prepend( links[i] );
					}
				}
			}
		}
	}

	// Init intersection observer
	//==============================================
	function greeny_intersection_observer_init() {

		if ( typeof IntersectionObserver != 'undefined' ) {
			// Create observer
			if ( typeof GREENY_STORAGE['intersection_observer'] == 'undefined' ) {
				GREENY_STORAGE['intersection_observer'] = new IntersectionObserver( function(entries) {
						entries.forEach( function( entry ) {
							greeny_intersection_observer_in_out( jQuery(entry.target), entry.isIntersecting || entry.intersectionRatio > 0 ? 'in' : 'out', entry );
						} );
					},
					{
						root: null,			// avoiding 'root' or setting it to 'null' sets it to default value: viewport
						rootMargin: '1px',	// increase (if positive) or decrease (if negative) root area
											// 1px is used instead 0px to avoid a problem with appear sticky elements when window is scrolled down
						threshold: 0		// 0.0 - 1.0: 0.0 - fired when top of the object enter in the viewport
											//            0.5 - fired when half of the object enter in the viewport
											//            1.0 - fired when the whole object enter in the viewport
					}
				);
			}
		} else {
			// Emulate IntersectionObserver behaviour
			$window.on( 'scroll', function() {
				if ( typeof GREENY_STORAGE['intersection_observer_items'] != 'undefined' ) {
					for ( var i in GREENY_STORAGE['intersection_observer_items'] ) {
						if ( ! GREENY_STORAGE['intersection_observer_items'][i] || GREENY_STORAGE['intersection_observer_items'][i].length === 0 ) {
							continue;
						}
						var item = GREENY_STORAGE['intersection_observer_items'][i],
							item_top = item.offset().top,
							item_height = item.height();
						greeny_intersection_observer_in_out( item, item_top + item_height > greeny_window_scroll_top() && item_top < greeny_window_scroll_top() + greeny_window_height() ? 'in' : 'out' );
					}
				}
			} );
		}

		// Change state of the entry
		window.greeny_intersection_observer_in_out = function( item, state, entry ) {
			var callback = null;
			if ( state == 'in' ) {
				if ( ! item.hasClass( 'greeny_in_viewport' ) ) {
					item.addClass( 'greeny_in_viewport' );
					callback = item.data('trx-addons-intersection-callback');
					if ( callback ) {
						callback( item, true, entry );
					}
				}
			} else {
				if ( item.hasClass( 'greeny_in_viewport' ) ) {
					item.removeClass( 'greeny_in_viewport' );
					callback = item.data('trx-addons-intersection-callback');
					if ( callback ) {
						callback( item, false, entry );
					}
				}
			}
		};

		// Add elements to the observer
		window.greeny_intersection_observer_add = function( items, callback ) {
			items.each( function() {
				var $self = jQuery( this ),
					id = $self.attr( 'id' );
				if ( ! $self.hasClass( 'greeny_intersection_inited' ) ) {
					if ( ! id ) {
						id = 'io-' + ( '' + Math.random() ).replace('.', '');
						$self.attr( 'id', id );
					}
					$self.addClass( 'greeny_intersection_inited' );
					if ( callback ) {
						$self.data( 'trx-addons-intersection-callback', callback );
					}
					if ( typeof GREENY_STORAGE['intersection_observer_items'] == 'undefined' ) {
						GREENY_STORAGE['intersection_observer_items'] = {};
					}
					GREENY_STORAGE['intersection_observer_items'][id] = $self;
					if ( typeof GREENY_STORAGE['intersection_observer'] !== 'undefined' ) {
						GREENY_STORAGE['intersection_observer'].observe( $self.get(0) );
					}
				}
			} );
		};

		// Remove elements from the observer
		window.greeny_intersection_observer_remove = function( items ) {
			items.each( function() {
				var $self = jQuery( this ),
					id = $self.attr( 'id' );
				if ( $self.hasClass( 'greeny_intersection_inited' ) ) {
					$self.removeClass( 'greeny_intersection_inited' );
					delete GREENY_STORAGE['intersection_observer_items'][id];
					if ( typeof GREENY_STORAGE['intersection_observer'] !== 'undefined' ) {
						GREENY_STORAGE['intersection_observer'].unobserve( $self.get(0) );
					}
				}
			} );
		};
	}



	// Scroll actions
	//==============================================

	// Do actions when page scrolled
	function greeny_scroll_actions() {

		// Call theme/plugins specific action (if exists)
		$document.trigger( 'action.scroll_greeny' );

		// Fix/unfix sidebar
		greeny_fix_sidebar();

		// Fix/unfix nav links
		greeny_fix_nav_links();

		// Fix/unfix share links
		greeny_fix_share_links();

		// Shift top and footer panels when header position is 'Under content'
		greeny_shift_under_panels();

		// Show full post reading progress
		greeny_full_post_reading();

		// Set flag about scroll actions are finished
		GREENY_STORAGE['scroll_busy'] = false;
	}

	// Add post_id to the readed list
	function greeny_add_to_read_list(post_id) {
		if ( post_id > 0 ) {
			var read_list = greeny_get_storage('greeny_post_read');
			if ( read_list && read_list.charAt(0) == '[' ) {
				read_list = JSON.parse(read_list);
			} else {
				read_list = [];
			}
			if ( read_list.indexOf(post_id) == -1 ) {
				read_list.push(post_id);
			}
			greeny_set_storage('greeny_post_read', JSON.stringify(read_list));
		}
	}

	var fpr_bt, fpr_cont, fpr_cs, fpr_ch, fpr_pw;
	function greeny_full_post_read_change_state() {
		fpr_bt = jQuery('.full_post_close');
		if ( fpr_bt.length == 1 ) {
			fpr_cont = fpr_bt.parent();
			fpr_cs   = fpr_cont.offset().top;
			fpr_ch   = fpr_cont.height();
			fpr_pw   = fpr_bt.find('.full_post_progress');
		}
	}

	// Show full post reading progress
	function greeny_full_post_reading() {
		if ( typeof fpr_bt == 'undefined' ) {
			greeny_full_post_read_change_state();
		}
		if ( fpr_bt.length == 1 ) {
			var ws = greeny_window_scroll_top(),
				wh = greeny_window_height();
			if ( ws > fpr_cs ) {
				if ( fpr_pw.length === 0 ) {
					fpr_bt.append(
						'<span class="full_post_progress">'
							+ '<svg viewBox="0 0 50 50">'
								+ '<circle class="full_post_progress_bar" cx="25" cy="25" r="22"></circle>'
							+ '</svg>'
						+ '</span>'
					);
					fpr_pw = fpr_bt.find('.full_post_progress');
				}
				var bar = fpr_pw.find('.full_post_progress_bar'),
					bar_max = parseFloat( bar.css('stroke-dasharray') );
				if ( fpr_cs + fpr_ch > ws + wh ) {
					var now = fpr_cs + fpr_ch - ( ws + wh ),
						delta = bar.data('delta');
					if ( delta == undefined ) {
						delta = now;
						bar.data('delta', delta);
					}
					bar.css('stroke-dashoffset', Math.min( 1, now / delta ) * bar_max );
					if ( now / delta < 0.5 ) {
						var post = fpr_cont.prev(),
							post_id = post.data('post-id');
						post.addClass('full_post_read');
						greeny_add_to_read_list(post_id);
					}
				} else if ( ! fpr_bt.hasClass('full_post_read_complete') ) {
					fpr_bt.addClass('full_post_read_complete');
				} else if ( fpr_cs + fpr_ch + wh / 3 < ws + wh ) {
					fpr_bt.trigger('click');
				}
			} else {
				if ( fpr_pw.length !== 0 ) {
					fpr_pw.remove();
				}
			}
		}
	}  // greeny_full_post_reading

	// Shift top and footer panels when header position is 'Under content'
	function greeny_shift_under_panels() {

		if ($body.hasClass( 'header_position_under' ) && ! greeny_browser_is_mobile()) {

			// Disable 'under' behavior on small screen
			if ( $body.hasClass( 'mobile_layout' ) ) {	//greeny_window_width() < GREENY_STORAGE['mobile_breakpoint_underpanels_off'] ) {
				if ( $header.css( 'position' ) == 'fixed' ) {
					// Header
					$header.css(
						{
							'position': 'relative',
							'left': 'auto',
							'top': 'auto',
							'width': 'auto',
							'transform': 'none',
							'zIndex': 3
						}
					);
					$header.find( '.top_panel_mask' ).hide();
					// Content
					$page_content_wrap.css(
						{
							'marginTop': 0,
							'marginBottom': 0,
							'zIndex': 2
						}
					);
					// Footer
					$footer.css(
						{
							'position': 'relative',
							'left': 'auto',
							'bottom': 'auto',
							'width': 'auto',
							'transform': 'none',
							'zIndex': 1
						}
					);
					$footer.find( '.top_panel_mask' ).hide();
				}
				return;
			}

			// Header
			var delta           = 50;
			var scroll_offset   = greeny_window_scroll_top();
			var header_height   = _header_height;
			var shift           = ! (/Chrome/.test( navigator.userAgent ) && /Google Inc/.test( navigator.vendor ))
									|| $header.find( '.slider_engine_revo' ).length === 0
										? 0	//1.2		// Parallax speed (if 0 - disable parallax)
										: 0;
			var mask            = $header.find( '.top_panel_mask' );
			var mask_opacity    = 0;
			var css             = {};
			if (mask.length === 0) {
				$header.append( '<div class="top_panel_mask"></div>' );
				mask = $header.find( '.top_panel_mask' );
			}
			if ( $header.css( 'position' ) !== 'fixed' ) {
				$page_content_wrap.css(
					{
						'zIndex': 5,
						'marginTop': header_height + 'px'
					}
				);
				$header.css(
					{
						'position': 'fixed',
						'left': 0,
						'top': greeny_adminbar_height() + 'px',
						'width': '100%',
						'zIndex': 3
					}
				);
			} else {
				$page_content_wrap.css( 'marginTop', header_height + 'px' );
			}
			if (scroll_offset > 0) {
				var offset = scroll_offset;	// - greeny_adminbar_height();
				if (offset <= header_height) {
					mask_opacity = Math.max( 0, Math.min( 0.8, (offset - delta) / header_height ) );
					// Don't shift header with Revolution slider in Chrome
					if (shift) {
						$header.css( 'transform', 'translate3d(0px, ' + (-Math.round( offset / shift )) + 'px, 0px)' );
					}
					mask.css(
						{
							'opacity': mask_opacity,
							'display': offset === 0 ? 'none' : 'block'
						}
					);
				} else {
					if (shift) {
						$header.css( 'transform', 'translate3d(0px, ' + (-Math.round( offset / shift )) + 'px, 0px)' );
					}
				}
			} else {
				if (shift) {
					$header.css( 'transform', 'none' );
				}
				if (mask.css( 'display' ) != 'none') {
					mask.css(
						{
							'opacity': 0,
							'display': 'none'
						}
					);
				}
			}

			// Footer
			var footer_height  = Math.min( _footer_height, greeny_window_height() );
			var footer_visible = (scroll_offset + greeny_window_height()) - ( $header.outerHeight() + $page_content_wrap.outerHeight() );
			if ( $footer.css( 'position' ) !== 'fixed' ) {
				$page_content_wrap.css(
					{
						'marginBottom': footer_height + 'px'
					}
				);
				$footer.css(
					{
						'position': 'fixed',
						'left': 0,
						'bottom': 0,
						'width': '100%',
						'zIndex': 2
					}
				);
			} else {
				$page_content_wrap.css( 'marginBottom', footer_height + 'px' );
			}
			if ( footer_visible > 0 ) {
				if ( $footer.css( 'zIndex' ) == 2 ) {
					$footer.css( 'zIndex', 4 );
				}
				mask = $footer.find( '.top_panel_mask' );
				if (mask.length === 0) {
					$footer.append( '<div class="top_panel_mask"></div>' );
					mask = $footer.find( '.top_panel_mask' );
				}
				if (footer_visible <= footer_height) {
					mask_opacity = Math.max( 0, Math.min( 0.8, (footer_height - footer_visible) / footer_height ) );
					// Don't shift header with Revolution slider in Chrome
					if (shift) {
						$footer.css( 'transform', 'translate3d(0px, ' + Math.round( (footer_height - footer_visible) / shift ) + 'px, 0px)' );
					}
					mask.css(
						{
							'opacity': mask_opacity,
							'display': footer_height - footer_visible <= 0 ? 'none' : 'block'
						}
					);
				} else {
					if (shift) {
						$footer.css( 'transform', 'none' );
					}
					if (mask.css( 'display' ) != 'none') {
						mask.css(
							{
								'opacity': 0,
								'display': 'none'
							}
						);
					}
				}
			} else {
				if ( $footer.css( 'zIndex' ) == 4 ) {
					$footer.css( 'zIndex', 2 );
				}
			}
		}
	}  // greeny_shift_under_panels


	// Fix/unfix footer
	function greeny_fix_footer() {
		if ( $body.hasClass( 'header_position_under' ) && ! greeny_browser_is_mobile() ) {
			if ( $footer.length > 0 ) {
				var ft_height = $footer.outerHeight( false ),
				pc            = $page_content_wrap,
				pc_offset     = pc.offset().top,
				pc_height     = pc.height();
				if ( pc_offset + pc_height + ft_height < greeny_window_height() ) {
					if ( $footer.css( 'position' ) != 'absolute' ) {
						$footer.css( {
							'position': 'absolute',
							'left': 0,
							'bottom': 0,
							'width' :'100%'
						} );
					}
				} else {
					if ( $footer.css( 'position' ) != 'relative' ) {
						$footer.css( {
							'position': 'relative',
							'left': 'auto',
							'bottom': 'auto'
						} );
					}
				}
			}
		}
	}  // greeny_fix_footer

	// Fix/unfix sidebar
	function greeny_fix_sidebar(force) {
		// Fix sidebar only if css sticky behaviour is not used
		if ( $body.hasClass( 'fixed_blocks_sticky' ) ) {
			return;
		}
		$sidebar.each( function() {
			var sb        = jQuery( this );
			var content   = sb.siblings( '.content' );
			var old_style = '';

			if ( content.length == 0 ) {
				return;
			}

			// Unfix when sidebar is under content
			if (content.css( 'float' ) == 'none') {
				old_style = sb.data( 'old_style' );
				if (old_style !== undefined) {
					sb.attr( 'style', old_style ).removeAttr( 'data-old_style' );
				}

			} else {

				var sb_height      = sb.outerHeight();
				var sb_shift       = 30;
				var content_height = content.outerHeight();
				var content_top    = content.offset().top;
				var content_shift  = content.position().top;

				// If sidebar shorter then content and page scrolled below the content's top
				if (sb_height < content_height && greeny_window_scroll_top() + greeny_fixed_rows_height() > content_top) {

					var sb_init = {
						'position': 'undefined',
						'float': 'none',
						'top': 'auto',
						'bottom': 'auto',
						'marginLeft': '0',
						'marginRight': '0'
					};

					if (typeof GREENY_STORAGE['scroll_offset_last'] == 'undefined') {
						GREENY_STORAGE['sb_top_last']        = content_top;
						GREENY_STORAGE['scroll_offset_last'] = greeny_window_scroll_top();
						GREENY_STORAGE['scroll_dir_last']    = 1;
					}
					var scroll_dir = greeny_window_scroll_top() - GREENY_STORAGE['scroll_offset_last'];
					if (scroll_dir === 0) {
						scroll_dir = GREENY_STORAGE['scroll_dir_last'];
					} else {
						scroll_dir = scroll_dir > 0 ? 1 : -1;
					}

					var sb_big = sb_height + sb_shift >= greeny_window_height() - greeny_fixed_rows_height(),
					sb_top     = sb.offset().top;

					if (sb_top < 0) {
						sb_top = GREENY_STORAGE['sb_top_last'];
					}

					// If sidebar height greater then window height
					if (sb_big) {

						// If change scrolling dir
						if (scroll_dir != GREENY_STORAGE['scroll_dir_last'] && sb.css( 'position' ) == 'fixed') {
							sb_init.position = 'absolute';
							sb_init.top      = sb_top + content_shift - content_top;

						// If scrolling down
						} else if (scroll_dir > 0) {
							if (greeny_window_scroll_top() + greeny_window_height() >= content_top + content_height) {
								sb_init.position = 'absolute';
								sb_init.bottom   = 0;
							} else if (greeny_window_scroll_top() + greeny_window_height() >= (sb.css( 'position' ) == 'absolute' ? sb_top : content_top) + sb_height + sb_shift) {
								sb_init.position = 'fixed';
								sb_init.bottom   = sb_shift;
							}

						// If scrolling up
						} else {
							if (greeny_window_scroll_top() + greeny_fixed_rows_height() <= sb_top) {
								sb_init.position = 'fixed';
								sb_init.top      = greeny_fixed_rows_height();
							}
						}

					// If sidebar height less then window height
					} else {
						if (greeny_window_scroll_top() + greeny_fixed_rows_height() >= content_top + content_height - sb_height) {
							sb_init.position = 'absolute';
							sb_init.bottom   = 0;
						} else {
							sb_init.position = 'fixed';
							sb_init.top      = greeny_fixed_rows_height();
						}
					}
					
					if (force && sb_init.position == 'undefined' && sb.css('position') == 'absolute') {
						sb_init.position = 'absolute';
						if (sb.css('top') != 'auto') {
							sb_init.top = sb.css('top');
						} else {
							sb_init.bottom = sb.css('bottom');
						}
					}

					// Check bounds
					if ( sb_init.position == 'absolute' || sb_init.position == 'undefined' ) {
						if ( sb_init.top == 'auto' && sb_init.bottom == 'auto' ) {
							sb_init.top = sb.offset().top;
						}
						if ( sb_init.top != 'auto' ) {
							if ( sb_init.top + sb_height > content_height ) {
								sb_init.position = 'absolute';
								sb_init.top = content_shift + content_height - sb_height;
								force = true;
							}
							if ( sb_init.top + sb_height <= content_shift + content_height && sb_init.top >= greeny_window_scroll_top() + greeny_window_height() ) {
								sb_init.position = 'fixed';
								sb_init.top      = 'auto';
								sb_init.bottom   = sb_shift;
								force = true;
							}
						}
					} else if ( sb_init.position == 'fixed' ) {
						if ( sb_init.top == 'auto' && sb_init.bottom == 'auto' && sb.css('top') != 'auto') {
							sb_init.top = parseFloat( sb.css('top') );
						}
						if ( sb_init.top != 'auto' && greeny_window_scroll_top() + sb_init.top + sb_height > content_top + content_height ) {
							sb_init.position = 'absolute';
							sb_init.top = content_shift + content_height - sb_height;
							force = true;
						}
					}

					// Set new position of the sidebar
					if (sb_init.position != 'undefined') {
						// Insert placeholder before sidebar
						var style = sb.attr('style');
						if (!style) style = '';
						if (!sb.prev().hasClass('sidebar_fixed_placeholder')) {
							sb.css(sb_init);
							GREENY_STORAGE['scroll_dir_last'] = 0;
							sb.before('<div class="sidebar_fixed_placeholder '+sb.attr('class')+'"'
									   		+ (sb.data('sb') ? ' data-sb="' + sb.data('sb') + '"' : '')
									   + '></div>');
						}
						// Detect horizontal position
						sb_init.left = sb_init.position == 'fixed' || $body.hasClass('body_style_fullwide') || $body.hasClass('body_style_fullscreen')
											? sb.prev().offset().left
											: sb.prev().position().left;
						sb_init.right = 'auto';
						sb_init.width = sb.prev().width() + parseFloat(sb.prev().css('paddingLeft')) + parseFloat(sb.prev().css('paddingRight'));
						// Set position
						if (force
							|| sb.css('position') != sb_init.position 
							|| GREENY_STORAGE['scroll_dir_last'] != scroll_dir
							|| sb.width() != sb_init.width) {
							if (sb.data('old_style') === undefined) {
								sb.attr('data-old_style', style);
							}
							sb.css(sb_init);
						}
					}

					GREENY_STORAGE['sb_top_last']        = sb_top;
					GREENY_STORAGE['scroll_offset_last'] = greeny_window_scroll_top();
					GREENY_STORAGE['scroll_dir_last']    = scroll_dir;

				} else {

					// Unfix when page scrolling to top
					old_style = sb.data( 'old_style' );
					if (old_style !== undefined) {
						sb.attr( 'style', old_style ).removeAttr( 'data-old_style' );
						if (sb.prev().hasClass('sidebar_fixed_placeholder')) {
							sb.prev().remove();
						}
					}

				}
			}
		} );
	}  // greeny_fix_sidebar

	// Fix/unfix .nav_links_fixed
	function greeny_fix_nav_links() {
		if ( $single_nav_links_fixed.length > 0 && $single_nav_links_fixed.css( 'position' ) == 'fixed') {
			var window_bottom = greeny_window_scroll_top() + greeny_window_height(),
				article = jQuery('.post_item_single'),
				article_top = article.length > 0 ? article.offset().top : greeny_window_height(),
				article_bottom = article_top + ( article.length > 0 ? article.height() * 2 / 3 : 0 ),
				footer_top = $footer.length > 0 ? $footer.offset().top : 100000;
			if ( article_bottom < window_bottom && footer_top > window_bottom ) {
				if ( ! $single_nav_links_fixed.hasClass('nav-links-visible') ) {
					$single_nav_links_fixed.addClass('nav-links-visible');
				}
			} else {
				if ( $single_nav_links_fixed.hasClass('nav-links-visible') ) {
					$single_nav_links_fixed.removeClass('nav-links-visible');
				}					
			}
		}
	}

	// Fix/unfix .post_info_vertical_fixed
	function greeny_fix_share_links() {
		if ( $single_post_info_fixed.length > 0 ) {
			var frh = greeny_fixed_rows_height() + 10,
				st = greeny_window_scroll_top() + frh;
			$single_post_info_fixed.each( function() {
				var share_links = jQuery(this),
					share_links_top = share_links.offset().top,
					share_links_left = share_links.offset().left,
					share_links_height = share_links.height(),
					share_links_position = share_links.css( 'position' ),
					article = share_links.parents('.post_content'),
					article_top = article.offset().top,
					article_bottom = article_top + article.height();
				if ( share_links_position == 'absolute') {
					if ( st >= article_top && st + share_links_height < article_bottom ) {
						share_links
							.data('abs-pos', {
								'left': share_links.css('left'), 
								'top':  share_links.css('top')})
							.addClass('post_info_vertical_fixed_on')
							.css({
								'top':  frh,
								'left': share_links_left
							});
					}
				} else if ( share_links_position == 'fixed' ) {
					if ( st < article_top ) {
						if ( share_links.hasClass( 'post_info_vertical_fixed_on' ) ) {
							var abs_pos = share_links.data('abs-pos');
							share_links
								.removeClass( 'post_info_vertical_fixed_on' )
								.css({
									'top': abs_pos.top,
									'left': abs_pos.left
								});
						}
					} else if ( st + share_links_height >= article_bottom ) {
						share_links.fadeOut();
					} else if ( share_links.css('display') == 'none' ) {
						share_links.fadeIn();
					}
				}
			});
		}
	}




	// Resize actions
	//==============================================

	// Do actions when page scrolled
	function greeny_resize_actions(cont) {

		// Update global values
		_header_height = $header.length === 0 ? 0 : $header.height();
		_footer_height = $footer.length === 0 ? 0 : $footer.height();
		
		// Call handlers
		greeny_check_layout();
		greeny_fix_sidebar(true);
		greeny_fix_footer();
		greeny_fix_nav_links();
		greeny_stretch_width( cont );
		greeny_stretch_bg_video();
		greeny_vc_row_fullwidth_to_boxed( cont );
		greeny_stretch_sidemenu();
		greeny_resize_video( cont );
		greeny_shift_under_panels();

		// Call theme/plugins specific action (if exists)
		//----------------------------------------------
		$document.trigger( 'action.resize_greeny', [cont] );
	}

	// Stretch sidemenu (if present)
	function greeny_stretch_sidemenu() {
		var toc_items = $menu_side_wrap.find( '.toc_menu_item' );
		if (toc_items.length === 0) {
			return;
		}
		var toc_items_height = greeny_window_height()
							- greeny_adminbar_height()
							- $menu_side_logo.outerHeight()
							- toc_items.length;
		var th               = Math.floor( toc_items_height / toc_items.length );
		var th_add           = toc_items_height - th * toc_items.length;
		if (GREENY_STORAGE['menu_side_stretch'] && toc_items.length >= 5 && th >= 30) {
			toc_items.find( ".toc_menu_description,.toc_menu_icon" ).css(
				{
					'height': th + 'px',
					'lineHeight': th + 'px'
				}
			);
			toc_items.eq( 0 ).find( ".toc_menu_description,.toc_menu_icon" ).css(
				{
					'height': (th + th_add) + 'px',
					'lineHeight': (th + th_add) + 'px'
				}
			);
		}
		//$menu_side_wrap.find('#toc_menu').height(toc_items_height + toc_items.length - toc_items.eq(0).height());
	}

	// Scroll sidemenu (if present)
	$document.on( 'action.toc_menu_item_active', function() {
		var toc_menu = $menu_side_wrap.find( '#toc_menu' );
		if (toc_menu.length === 0) {
			return;
		}
		var toc_items = toc_menu.find( '.toc_menu_item' );
		if (toc_items.length === 0) {
			return;
		}
		var th           = toc_items.eq( 0 ).height(),
		toc_menu_pos     = parseFloat( toc_menu.css( 'top' ) ),
		toc_items_height = toc_items.length * th,
		menu_side_height = greeny_window_height()
							- greeny_adminbar_height()
							- $menu_side_logo.outerHeight()
							- $menu_side_logo.next( '.toc_menu_item' ).outerHeight();
		if ( toc_items_height > menu_side_height ) {
			var toc_item_active = $menu_side_wrap.find( '.toc_menu_item_active' ).eq( 0 );
			if ( toc_item_active.length == 1 ) {
				var toc_item_active_pos = (toc_item_active.index() + 1) * th;
				if (toc_menu_pos + toc_item_active_pos > menu_side_height - th) {
					toc_menu.css( 'top', Math.max( -toc_item_active_pos + 3 * th, menu_side_height - toc_items_height ) );
				} else if (toc_menu_pos < 0 && toc_item_active_pos < -toc_menu_pos + 2 * th) {
					toc_menu.css( 'top', Math.min( -toc_item_active_pos + 3 * th, 0 ) );
				}
			}
		} else if ( toc_menu_pos < 0 ) {
			toc_menu.css( 'top', 0 );
		}
	} );

	// Check for mobile layout
	function greeny_check_layout() {
		var resize = true;
		if ( $body.hasClass( 'no_layout' ) ) {
			$body.removeClass( 'no_layout' );
			resize = false;
		}
		var w = window.innerWidth;
		if (w == undefined) {
			w = greeny_window_width() + ( greeny_window_height() < greeny_document_height() || greeny_window_scroll_top() > 0 ? 16 : 0 );
		}
		if ( w < GREENY_STORAGE['mobile_layout_width'] ) {
			if ( ! $body.hasClass( 'mobile_layout' )) {
				$body.removeClass( 'desktop_layout' ).addClass( 'mobile_layout' );
				$document.trigger( 'action.switch_to_mobile_layout' );
				if (resize) {
					$window.trigger( 'resize' );
				}
			}
		} else {
			if ( ! $body.hasClass( 'desktop_layout' )) {
				$body.removeClass( 'mobile_layout' ).addClass( 'desktop_layout' );
				jQuery( '.menu_mobile' ).removeClass( 'opened' );
				jQuery( '.menu_mobile_overlay' ).hide();
				$document.trigger( 'action.switch_to_desktop_layout' );
				if ( resize ) {
					$window.trigger( 'resize' );
				}
			}
		}
		if (GREENY_STORAGE['mobile_device'] || greeny_browser_is_mobile()) {
			$body.addClass( 'mobile_device' );
		}
	}

	// Stretch area to full window width
	function greeny_stretch_width(cont) {
		if (cont === undefined) {
			cont = $body;
		}
		$stretch_width.each(
			function() {
				var $el             = jQuery( this );
				var $el_cont        = $el.parents( '.page_wrap' );
				var $el_cont_offset = 0;
				if ($el_cont.length === 0) {
					$el_cont = $window;
				} else {
					$el_cont_offset = $el_cont.offset().left;
				}
				var $el_full        = $el.next( '.trx-stretch-width-original' );
				var el_margin_left  = parseInt( $el.css( 'margin-left' ), 10 );
				var el_margin_right = parseInt( $el.css( 'margin-right' ), 10 );
				var offset          = $el_cont_offset - $el_full.offset().left - el_margin_left;
				var width           = $el_cont.width();
				if ( ! $el.hasClass( 'inited' )) {
					$el.addClass( 'inited invisible' );
					$el.css(
						{
							'position': 'relative',
							'box-sizing': 'border-box'
						}
					);
				}
				$el.css(
					{
						'left': offset,
						'width': $el_cont.width()
					}
				);
				if ( ! $el.hasClass( 'trx-stretch-content' ) ) {
					var padding      = Math.max( 0, -1 * offset );
					var paddingRight = Math.max( 0, width - padding - $el_full.width() + el_margin_left + el_margin_right );
					$el.css( { 'padding-left': padding + 'px', 'padding-right': paddingRight + 'px' } );
				}
				$el.removeClass( 'invisible' );
			}
		);
	}  // greeny_stretch_width

	// Resize video frames
	function greeny_resize_video(cont) {
		if (cont === undefined) {
			cont = $body;
		}
		// Resize tags 'video'
		cont.find( 'video' ).each( function() {
			var $self = jQuery( this );
			// If item now invisible
			if ( ( ! GREENY_STORAGE['resize_tag_video'] && $self.parents('.mejs-mediaelement').length === 0 )
				|| $self.hasClass( 'trx_addons_resize' )
				|| $self.hasClass( 'trx_addons_noresize' )
				|| $self.parents( 'div:hidden,section:hidden,article:hidden' ).length > 0
			) {
				return;
			}
			var video     = $self.addClass( 'greeny_resize' ).eq( 0 );
			var ratio     = (video.data( 'ratio' ) !== undefined ? video.data( 'ratio' ).split( ':' ) : [16,9]);
			ratio         = ratio.length != 2 || ratio[0] === 0 || ratio[1] === 0 ? 16 / 9 : ratio[0] / ratio[1];
			var mejs_cont = video.parents( '.mejs-video' ).eq(0);
			var mfp_cont  = video.parents( '.mfp-content' ).eq(0);
			var w_attr    = video.data( 'width' );
			var h_attr    = video.data( 'height' );
			if ( ! w_attr || ! h_attr) {
				w_attr = video.attr( 'width' );
				h_attr = video.attr( 'height' );
				if ( ! w_attr || ! h_attr) {
					return;
				}
				video.data( {'width': w_attr, 'height': h_attr} );
			}
			var percent = ('' + w_attr).substr( -1 ) == '%';
			w_attr      = parseInt( w_attr, 10 );
			h_attr      = parseInt( h_attr, 10 );
			var w_real  = Math.ceil(
							mejs_cont.length > 0
									? Math.min( percent ? 10000 : w_attr, mejs_cont.parents( 'div,article' ).eq(0).width() )
									: Math.min( percent ? 10000 : w_attr, video.parents( 'div,article' ).eq(0).width() )
			);
			if ( mfp_cont.length > 0 ) {
				w_real  = Math.max( mfp_cont.width(), w_real );
			}
			var h_real  = Math.ceil( percent ? w_real / ratio : w_real / w_attr * h_attr );
			if ( parseInt( video.attr( 'data-last-width' ), 10 ) == w_real ) {
				return;
			}
			if ( percent ) {
				video.height( h_real );
			} else if ( video.parents( '.wp-video-playlist' ).length > 0 ) {
				if ( mejs_cont.length === 0 ) {
					video.attr( {'width': w_real, 'height': h_real} );
				}
			} else {
				video.attr( {'width': w_real, 'height': h_real} ).css( {'width': w_real + 'px', 'height': h_real + 'px'} );
				if ( mejs_cont.length > 0 ) {
					greeny_set_mejs_player_dimensions( video, w_real, h_real );
				}
			}
			video.attr( 'data-last-width', w_real );
		} );

		// Resize tags 'iframe'
		if ( GREENY_STORAGE['resize_tag_iframe'] ) {
			cont.find( '.video_frame iframe,iframe' ).each( function() {
				var $self = jQuery( this );
				// If item now invisible
				if ( $self.hasClass( 'trx_addons_resize' ) || $self.hasClass( 'trx_addons_noresize' ) || $self.addClass( 'greeny_resize' ).parents( 'div:hidden,section:hidden,article:hidden' ).length > 0 ) {
					return;
				}
				var iframe = $self.eq( 0 );
				if (iframe.length === 0 || iframe.attr( 'src' ) === undefined || iframe.attr( 'src' ).indexOf( 'soundcloud' ) > 0) {
					return;
				}
				var ratio  = (iframe.data( 'ratio' ) !== undefined
								? iframe.data( 'ratio' ).split( ':' )
								: (iframe.parent().data( 'ratio' ) !== undefined
									? iframe.parent().data( 'ratio' ).split( ':' )
									: (iframe.find( '[data-ratio]' ).length > 0
										? iframe.find( '[data-ratio]' ).data( 'ratio' ).split( ':' )
										: (iframe.attr('width') && iframe.attr('height')
											? [iframe.attr('width'), iframe.attr('height')]
											: [16, 9]
											)
										)
									)
								);
				ratio      = ratio.length != 2 || ratio[0] === 0 || ratio[1] === 0 ? 16 / 9 : ratio[0] / ratio[1];
				var w_attr = iframe.attr( 'width' );
				var h_attr = iframe.attr( 'height' );
				if ( ! w_attr || ! h_attr ) {
					return;
				}
				var percent   = ('' + w_attr).substr( -1 ) == '%';
				w_attr        = parseInt( w_attr, 10 );
				h_attr        = parseInt( h_attr, 10 );
				var par       = iframe.parents( 'div,section' ).eq(0),
					contains  = iframe.data('contains-in-parent')=='1' || iframe.hasClass('contains-in-parent'),
					nostretch = iframe.data('no-stretch-to-parent')=='1' || iframe.hasClass('no-stretch-to-parent'),
					pw        = Math.ceil( par.width() ),
					ph        = Math.ceil( par.height() ),
					w_real    = nostretch ? Math.min( w_attr, pw ) : pw,
					h_real    = Math.ceil( percent ? w_real / ratio : w_real / w_attr * h_attr );
				if ( contains && par.css( 'position' ) == 'absolute' && h_real > ph) {
					h_real = ph;
					w_real = Math.ceil( percent ? h_real * ratio : h_real * w_attr / h_attr );
				}
				if (parseInt( iframe.attr( 'data-last-width' ), 10 ) == w_real) {
					return;
				}
				iframe.css( {'width': w_real + 'px', 'height': h_real + 'px'} );
				iframe.attr( 'data-last-width', w_real );
			} );
		}
	}  // greeny_resize_video

	// Set Media Elements player dimensions
	function greeny_set_mejs_player_dimensions(video, w, h) {
		if (mejs) {
			for (var pl in mejs.players) {
				if (mejs.players[pl].media.src == video.attr( 'src' )) {
					if (mejs.players[pl].media.setVideoSize) {
						mejs.players[pl].media.setVideoSize( w, h );
					} else if (mejs.players[pl].media.setSize) {
						mejs.players[pl].media.setSize( w, h );
					}
					mejs.players[pl].setPlayerSize( w, h );
					mejs.players[pl].setControlsSize();
				}
			}
		}
	}

	// Stretch background video
	function greeny_stretch_bg_video() {
		var video_wrap = jQuery( 'div#background_video,.tourmaster-background-video' );
		if (video_wrap.length === 0) {
			return;
		}
		var cont = video_wrap.hasClass( 'tourmaster-background-video' ) ? video_wrap.parent() : video_wrap,
		w        = cont.width(),
		h        = cont.height(),
		video    = video_wrap.find( '>iframe,>video' );
		if (w / h < 16 / 9) {
			w = h / 9 * 16;
		} else {
			h = w / 16 * 9;
		}
		video
			.attr( {'width': w, 'height': h} )
			.css( {'width': w, 'height': h} );
	}

	// Recalculate width of the vc_row[data-vc-full-width="true"] when content boxed or menu_side=='left|right'
	$document.on('vc-full-width-row action.before_resize_trx_addons', function(e, container) {
		greeny_vc_row_fullwidth_to_boxed( jQuery(container) );
	});
	function greeny_vc_row_fullwidth_to_boxed(cont) {
		if ( $body.hasClass( 'body_style_boxed' )
			|| $body.hasClass( 'menu_side_present' )
			|| parseInt($page_wrap.css('paddingLeft'), 10) > 0
		) {
			if (cont === undefined || ! cont.hasClass( '.vc_row' ) || ! cont.data( 'vc-full-width' )) {
				cont = jQuery( '.vc_row[data-vc-full-width="true"]' );
			}
			var rtl                = jQuery( 'html' ).attr( 'dir' ) == 'rtl';
			var page_wrap_pl       = parseInt( $page_wrap.css('paddingLeft'), 10 );
			if ( isNaN( page_wrap_pl ) ) {
				page_wrap_pl = 0;
			}
			var page_wrap_pr       = parseInt( $page_wrap.css('paddingRight'), 10 );
			if ( isNaN( page_wrap_pr ) ) {
				page_wrap_pr = 0;
			}
			var page_wrap_width    = $page_wrap.outerWidth() - page_wrap_pl - page_wrap_pr;
			var content_wrap       = $page_content_wrap.find( '.content_wrap' );
			var content_wrap_width = content_wrap.width();
			var indent             = ( page_wrap_width - content_wrap_width ) / 2;
			cont.each( function() {
				var $self           = jQuery( this );
				var mrg             = parseInt( $self.css( 'marginLeft' ), 10 );
				var stretch_content = $self.attr( 'data-vc-stretch-content' );
				var stretch_row     = $self.attr( 'data-vc-full-width' );
				var in_content      = $self.parents( '.content_wrap' ).length > 0;
				$self.css( {
					'width':         in_content && ! stretch_content && ! stretch_row ? Math.min( page_wrap_width, content_wrap_width ) : page_wrap_width,
					'left':          rtl ? 'auto' : ( in_content ? -indent : 0 ) - mrg,
					'right':         ! rtl ? 'auto' : ( n_content ? -indent : 0 ) - mrg,
					'padding-left':  stretch_content ? 0 : indent + mrg,
					'padding-right': stretch_content ? 0 : indent + mrg
				} );
			} );
		}
	}

	ready_busy = false;

} );
// Buttons decoration (add 'hover' class)
// Attention! Not use cont.find('selector')! Use jQuery('selector') instead!

jQuery( document ).on(
	'action.init_hidden_elements', function(e, cont) {
		"use strict";

		// Add button's hover class (selected in Theme Options) to the tags 'button' and 'a'
		// 'sc_button_hover_fade' | 'sc_button_hover_slide_left' | 'sc_button_hover_slide_right' | 'sc_button_hover_slide_top' | 'sc_button_hover_slide_bottom'
		if (GREENY_STORAGE['button_hover'] && GREENY_STORAGE['button_hover'] != 'default') {
			jQuery(
				// Tag 'button'
				'button:not(.search_submit):not(.full_post_close):not([class*="sc_button_hover_"]),'
				+ '.sc_form_field button:not([class*="sc_button_hover_"]),'
				// Theme and sc_button
				+ '.theme_button:not([class*="sc_button_hover_"]),'
				+ '.sc_button:not([class*="sc_button_simple"]):not([class*="sc_button_bordered"]):not([class*="sc_button_hover_"]),'
				// Theme tabs
				+ '.greeny_tabs .greeny_tabs_titles li a:not([class*="sc_button_hover_"]),'
				// More link
				+ '.post_item .more-link:not([class*="sc_button_hover_"]),'
				// Links in the trx_addons hover with back info
				+ '.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_"]),'
				// BuddyPress
				+ '#buddypress a.button:not([class*="sc_button_hover_"])'
				// MP Time table
				+ '.mptt-navigation-tabs li a:not([class*="sc_button_hover_style_"]),'
				// EDD (Easy digital downloads)
				+ '.edd_download_purchase_form .button:not([class*="sc_button_hover_style_"]),'
				+ '.edd-submit.button:not([class*="sc_button_hover_style_"]),'
				+ '.widget_edd_cart_widget .edd_checkout a:not([class*="sc_button_hover_style_"]),'
				// WooCommerce
				+ '.hover_shop_buttons .icons a:not([class*="sc_button_hover_style_"]),'
				+ '.woocommerce #respond input#submit:not([class*="sc_button_hover_"]),'
				+ '.woocommerce .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"]),'
				+ '.woocommerce-page .button:not([class*="shop_"]):not([class*="view"]):not([class*="sc_button_hover_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_' + GREENY_STORAGE['button_hover'] );

			// Add button's hover class if not based on :before or :after elements (like 'sc_button_hover_arrow')
			if (GREENY_STORAGE['button_hover'] != 'arrow') {
				jQuery(
					// Form buttons
					'input[type="submit"]:not([class*="sc_button_hover_"]),'
					+ 'input[type="button"]:not([class*="sc_button_hover_"]),'
					// Tag cloud
					+ '.tagcloud > a:not([class*="sc_button_hover_"]),'
					// VC tabs and accordion
					+ '.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_"]),'
					+ '.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_"]),'
					// WooCommerce
					+ '.woocommerce nav.woocommerce-pagination ul li a:not([class*="sc_button_hover_"]),'
					// Tribe Events Calendar
					+ '.tribe-events-button:not([class*="sc_button_hover_"]),'
					+ '#tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),'
					+ '.tribe-bar-mini #tribe-bar-views .tribe-bar-views-list .tribe-bar-views-option a:not([class*="sc_button_hover_"]),'
					+ '.tribe-events-cal-links a:not([class*="sc_button_hover_"]),'
					+ '.tribe-events-sub-nav li a:not([class*="sc_button_hover_"]),'
					// ThemeREX Addons buttons
					+ '.isotope_filters_button:not([class*="sc_button_hover_"]),'
					+ '.trx_addons_scroll_to_top:not([class*="sc_button_hover_"]),'
					+ '.sc_promo_modern .sc_promo_link2:not([class*="sc_button_hover_"]),'
					+ '.slider_container .slider_prev:not([class*="sc_button_hover_"]),'
					+ '.slider_container .slider_next:not([class*="sc_button_hover_"]),'
					+ '.sc_slider_controller_titles .slider_controls_wrap > a:not([class*="sc_button_hover_"])'
				).addClass( 'sc_button_hover_just_init sc_button_hover_' + GREENY_STORAGE['button_hover'] );
			}

			// Alter style: sc_button_hover_style_default
			jQuery(
				// Controls in the slider controller (style 'Titles') - arrows at the left and right side
				'.sc_slider_controller_titles .slider_controls_wrap > a:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_default' );

			// Alter style: sc_button_hover_style_inverse
			jQuery(
				// Links in the trx_addons hover with back info
				'.trx_addons_hover_content .trx_addons_hover_links a:not([class*="sc_button_hover_style_"]),'
				// WooCommerce single product: related products and up-sells
				+ '.single-product ul.products li.product .post_data .button:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_inverse' );

			// Alter style: sc_button_hover_style_hover
			jQuery(
				// Social share icons
				'.post_item_single .post_content .post_meta .post_share .socials_type_block .social_item .social_icon:not([class*="sc_button_hover_style_"]),'
				// WooCommerce buttons with class 'alt'
				+ '.woocommerce #respond input#submit.alt:not([class*="sc_button_hover_style_"]),'
				+ '.woocommerce a.button.alt:not([class*="sc_button_hover_style_"]),'
				+ '.woocommerce button.button.alt:not([class*="sc_button_hover_style_"]),'
				+ '.woocommerce input.button.alt:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_hover' );

			// Alter style: sc_button_hover_style_alter
			jQuery(
				// WooCommerce buttons in the notice and info areas
				'.woocommerce .woocommerce-message .button:not([class*="sc_button_hover_style_"]),'
				+ '.woocommerce .woocommerce-info .button:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_alter' );

			// Alter style: sc_button_hover_style_alterbd
			jQuery(
				// ThemeREX Addons tabs in the sidebar
				'.sidebar .trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"]),'
				// Theme tabs
				+ '.greeny_tabs .greeny_tabs_titles li a:not([class*="sc_button_hover_style_"]),'
				// Tag cloud
				+ '.widget_tag_cloud a:not([class*="sc_button_hover_style_"]),'
				// Woocommerce tag cloud
				+ '.widget_product_tag_cloud a:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_alterbd' );

			// Alter style: sc_button_hover_style_dark
			jQuery(
				// VC tabs and accordion
				'.vc_tta-accordion .vc_tta-panel-heading .vc_tta-controls-icon:not([class*="sc_button_hover_style_"]),'
				+ '.vc_tta-color-grey.vc_tta-style-classic .vc_tta-tab > a:not([class*="sc_button_hover_style_"]),'
				// WooCommerce tabs in the single product
				+ '.single-product div.product > .woocommerce-tabs .wc-tabs li a:not([class*="sc_button_hover_style_"]),'
				// sc_button with color style 'dark'
				+ '.sc_button.color_style_dark:not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"]),'
				// Slider controls (arrows)
				+ '.slider_prev:not([class*="sc_button_hover_style_"]),'
				+ '.slider_next:not([class*="sc_button_hover_style_"]),'
				// Video player - button 'Play'
				+ '.trx_addons_video_player.with_cover .video_hover:not([class*="sc_button_hover_style_"]),'
				// ThemeREX Addons tabs
				+ '.trx_addons_tabs .trx_addons_tabs_titles li a:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_dark' );

			// Alter style: sc_button_hover_style_extra
			jQuery(
				// Price table links
				'.sc_price_item_link:not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_extra' );

			// Alter style: sc_button_hover_style_link2
			jQuery(
				// sc_button with color style 'link2'
				'.sc_button.color_style_link2:not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_link2' );

			// Alter style: sc_button_hover_style_link3
			jQuery(
				// sc_button with color style 'link3'
				'.sc_button.color_style_link3:not([class*="sc_button_simple"]):not([class*="sc_button_hover_style_"])'
			).addClass( 'sc_button_hover_just_init sc_button_hover_style_link3' );

			// Remove hover class from some elements for which hover-effect should not be used
			jQuery(
				// Media player 'Media elements'
				'.mejs-controls button,'
				// Magnific popup close button
				+ '.mfp-close,'
				// sc_button with background image
				+ '.sc_button_bg_image,'
				// Buttons in rows with type 'Narrow' inside custom layouts
				+ '.sc_layouts_row_type_narrow .sc_button,'
				// Tribe Events 5.0+ new design
				+ '.tribe-common-c-btn-icon,'
				+ '.tribe-events-c-top-bar__datepicker-button,'
				+ '.tribe-events-calendar-list-nav button,'
				+ '.tribe-events-cal-links .tribe-events-button,'
				// Links in the hover 'Shop'
				+ '.hover_shop_buttons a,'
				// Woocommerce
				+ 'button.pswp__button,'
				+ '.woocommerce-orders-table__cell-order-actions .button'
			).removeClass( 'sc_button_hover_' + GREENY_STORAGE['button_hover'] );

			// Remove temporary class 'sc_button_hover_just_init'
			setTimeout(
				function() {
					jQuery( '.sc_button_hover_just_init' ).removeClass( 'sc_button_hover_just_init' );
				}, 500
			);

		}

        //Underline Hover
        //++++++++++++++++
        jQuery(
            '.sc_icons_simple .sc_icons_item_description a:not(.underline_hover),'
            + '.sc_icons_plate .sc_icons_item a.sc_icons_item_more_link:not(.underline_hover),'
            + '.sc_layouts_title_breadcrumbs .breadcrumbs a:not(.underline_hover)'
        ).addClass( 'underline_hover' );

        //Underline Hover Reverse
        //++++++++++++++++
        jQuery(
            '.sc_icons_plain .sc_icons_item .sc_icons_item_more_link:not(.underline_hover_reverse),'
            + '.sc_icons_bordered .sc_icons_item_description a:not(.underline_hover_reverse)'
        ).addClass( 'underline_hover_reverse' );


        //Underline Animation
        //+++++++++++++++++++
		
        /*
		jQuery(
            ''
        ).addClass( 'underline_anim' );
		*/

        jQuery(window).scroll(function() {
            jQuery( '.underline_anim:not(.underline_do_hover)' ).each( function() {
                var item = jQuery(this);
                if ( item.offset().top < jQuery( window ).scrollTop() + jQuery( window ).height() - 80 ) {
                    item.addClass( 'underline_do_hover' );
                }
            } );
        });

	}
);
