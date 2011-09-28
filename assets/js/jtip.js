/*
 * JTip
 * By Cody Lindley (http://www.codylindley.com)
 * Under an Attribution, Share Alike License
 * JTip is built on top of the very light weight jquery library.
 */

/**
 * On page load (as soon as it's ready) call JT_init
 */
$(document).ready(JT_init);

/**
 * Initializes JTip.
 */
function JT_init() {
	$('a.jtip').hover(function() {
		JT_show(this.href, this.id, this.name);
	}, function() {
		$('#jt').remove();
	}).click(function() {
		return false;
	});
}

/**
 * Shows a tooltip.
 *
 * @param url
 * @param linkId
 * @param title
 */
function JT_show(url, linkId, title) {
	if (title == false) title = '&nbsp;';

	var de = document.documentElement;
	var w = self.innerWidth || (de&&de.clientWidth) || document.body.clientWidth;
	var hasArea = w - getAbsoluteLeft(linkId);

	var clickElementY = getAbsoluteTop(linkId) + 2; // set y position
	var clickElementX = 0;

	var queryString = url.replace(/^[^\?]+\??/,'');
	var params = parseQuery(queryString);

	if (params['width'] === undefined) {
		params['width'] = 200;
	}
	if (params['link'] !== undefined) {
		$('#' + linkId).bind('click', function() {
			window.location = params['link'];
		});
		$('#' + linkId).css('cursor','pointer');
	}

	if (hasArea > (params['width'] * 1) + 75) {
		$('body').append('<div id="jt" class="jt_right" style="width: ' + params['width'] * 1 + 'px;"><div id="jt_copy"><div class="jt_loader"><div></div></div>'); // right side
		var arrowOffset = getElementWidth(linkId) + 11;
		clickElementX = getAbsoluteLeft(linkId) + arrowOffset; // set x position
	} else {
		$('body').append('<div id="jt" class="jt_left" style="width: ' + params['width'] * 1 + 'px;"><div id="jt_copy"><div class="jt_loader"><div></div></div>'); // left side
		clickElementX = getAbsoluteLeft(linkId) - ((params['width']*1) + 15); //set x position
	}

	$('#jt').css({ left: clickElementX + 'px', top: clickElementY + 'px' });
	$('#jt').show();
	$('#jt_copy').load(url);
}

/**
 * Gets the width of the tooltip.
 *
 * @param objectId
 * @returns
 */
function getElementWidth(objectId) {
	x = document.getElementById(objectId);
	return x.offsetWidth;
}

/**
 * No description yet.
 *
 * @param objectId
 * @returns
 */
function getAbsoluteLeft(objectId) {
	// get an object left position from the upper left viewport corner
	o = document.getElementById(objectId);
	// get left position from the parent object
	oLeft = o.offsetLeft;
	// parse the parent hierarchy up to the document element
	while (o.offsetParent != null) {
		oParent = o.offsetParent; // get parent object reference
		oLeft += oParent.offsetLeft; // add parent left position
		o = oParent;
	}
	return oLeft;
}

/**
 * No description yet.
 *
 * @param objectId
 * @returns
 */
function getAbsoluteTop(objectId) {
	// get an object top position from the upper left viewport corner
	o = document.getElementById(objectId);
	// get top position from the parent object
	oTop = o.offsetTop;
	// parse the parent hierarchy up to the document element
	while (o.offsetParent != null) {
		oParent = o.offsetParent; // get parent object reference
		oTop += oParent.offsetTop; // add parent top position
		o = oParent;
	}
	return oTop;
}

/**
 * No description yet.
 *
 * @param query
 */
function parseQuery(query) {
	var params = new Object();
	if (!query) {
		return params; // return empty object
	}
	var pairs = query.split(/[;&]/);

	for (var i = 0; i < pairs.length; i++) {
		var keyValue = pairs[i].split('=');
		if (!keyValue || keyValue.length != 2) {
			continue;
		}
		var key = unescape(keyValue[0]);
		var val = unescape(keyValue[1]);

		val = val.replace(/\+/g, ' ');
		params[key] = val;
	}
	return params;
}

/**
 * No description yet.
 *
 * @param event
 */
function blockEvents(event) {
	if (event.target) {
		event.preventDefault();
	} else {
		event.returnValue = false;
	}
}
