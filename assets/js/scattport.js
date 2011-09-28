/**
 * Gets the queued notifications.
 */
function getNotifications() {
	$.get(SITE_URL + 'ajax/get_notifications', function(data) {
		if (data.length > 0) {
			$('#notifications').append(data).slideDown();
		}
	});
}

/**
 * Looks for finished jobs.
 */
function jobDaemon() {
	$.getJSON(SITE_URL + 'ajax/check_jobs', function(data) {
		$.each(data, function(key, value) {
			$('#' + key).find('strong').html(value);
		});
	});
}

/**
 * Sets a cookie.
 *
 * @param name
 * @param value
 * @param days
 */
function setCookie(name, value, days) {
	var today = new Date();
	var expire = new Date();

	if (days == null || days == 0) {
		days = 1;
	}
	expire.setTime(today.getTime() + 3600000 * 24 * days);
	document.cookie = name + "=" + escape(value) + ";path=/;expires=" + expire.toGMTString();
}

/**
 * Gets a cookie.
 *
 * @param name
 * @returns
 */
function getCookie(name) {
	var cookie = ' ' + document.cookie;
	var index = cookie.indexOf(" " + name + "=");

	if (index == -1) {
		index = cookie.indexOf(";" + name + "=");
	}
	if (index == -1 || name == '') {
		return '';
	}

	var index2 = cookie.indexOf(";", index + 1);

	if (index2 == -1) {
		index2 = cookie.length;
	}
	return unescape(cookie.substring(index + name.length + 2, index2));
}

/**
 *
 * @param cookieName
 * @returns
 */
var CookieList = function(cookieName) {
	var cookie = getCookie(cookieName);
	// load the items or a new array if null
	var items = cookie ? cookie.split(/,/) : new Array();

	return {
		"add": function(val) {
			// add to the items
			items.push(val);
			// save the items to a cookie
			setCookie(cookieName, items.join(','));
		}, "remove": function(val) {
			// remove the value from items
			items.splice(items.indexOf(val), 1);
			// save the items to a cookie
			setCookie(cookieName, items.join(','));
		}, "clear": function() {
			items = null;
			// clear the cookie
			setCookie(cookieName, null, null);
		}, "items": function() {
			// get all the items
			return items;
		}
	};
};

/**
 * Alternates the table rows.
 */
$.fn.alternateRowColors = function() {
	$('tbody tr:odd', this).removeClass('even').addClass('odd');
	$('tbody tr:even', this).removeClass('odd').addClass('even');
	return this;
};

/**
 * Do some stuff if document is ready.
 */
$(document).ready(function() {
	/*
	 * Tabs
	 */
	$('.tab_content').hide(); // hide all content
	$('ul.tabs li:first').addClass('active').show(); // activate first tab
	$('.tab_content:first').show(); // show first tab content

	// on-click event
	$('ul.tabs li').click(function() {
		$('ul.tabs li').removeClass('active'); // remove any 'active' class
		$(this).addClass('active'); // add 'active' class to selected tab
		$('.tab_content').hide(); // hide all tab content

		var activeTab = $(this).find('a').attr('href');
		if (activeTab.match(/^#.*/g)) {
			$(activeTab).fadeIn(); // fade in the active tab content
			return false;
		} else {
			return true;
		}
	});

	/*
	 * Notification stuff
	 */
	$('#notifications').hide();
	getNotifications();
	setInterval('getNotifications()', '5000');
	jobDaemon();
	setInterval('jobDaemon()', JOBS_CHECK_INTERVAL * 1000);

	/*
	 * Tables
	 */
	var settings = {
			table_class : 'tableList'
	};

	// add or delete 'hover' class on mouseOver event
	$('.' + settings.table_class + ' tbody tr').hover(function() {
		$(this).addClass('hover');
	}, function() {
		$(this).removeClass('hover');
	});

	// add or delete 'selected' class if a row is selected via checkbox
	$('.' + settings.table_class + ' tbody input:checkbox').click(function() {
		if ($(this).attr('checked') == true) {
			$(this).parent().parent().addClass('selected');
		} else {
			$(this).parent().parent().removeClass('selected');
		}
	});

	// alternate table rows
	$('.' + settings.table_class).each(function() {
		var table = $(this);
		table.alternateRowColors(table);
	});

	/*
	 * Pagination
	 */
	$('.paginated').each(function() {
		var currentPage = 0;
		var numPerPage = 10;
		var table = $(this);

		table.bind('repaginate', function() {
			var start = currentPage * numPerPage;
			var end = (currentPage + 1) * numPerPage;
			table.find('tbody tr').slice(start, end).show().end().slice(0, start).hide().end().slice(end).hide().end();
		});

		var numRows = table.find('tbody tr').length;
		var numPages = Math.ceil(numRows / numPerPage);

		var $pager = $('<div class="pagination"></div>');
		var $pagelist = $('<ul></ul>');

		$pagelist.append('<li class="pages">Seite:</li>');

		for (var page = 0; page < numPages; page++) {
			$('<li class="page-number">' + (page + 1) + '</li>').bind('click', {'newPage': page}, function(event) {
				currentPage = event.data['newPage'];
				table.trigger('repaginate');

				$(this).addClass('active').siblings().removeClass('active');
			}).appendTo($pagelist).addClass('clickable');
		}

		$pagelist.find('li.page-number:first').addClass('active');
		$pager.append($pagelist);
		$pager.insertAfter(table);

		table.trigger('repaginate');
	});

	/*
	 * Sortable tables
	 */
	$('.sortable').tableDnD({
		dragHandle: 'drag_handle'
	});

	$('.sortable tr').hover(function() {
		$(this).find('td.drag_handle').addClass('drag_handle-show');
	}, function() {
		$(this).find('td.drag_handle').removeClass('drag_handle-show');
	});

	/*
	 * Active project selection
	 */
	$('select[name="activeProject"]').bind('change', function () {
		var url = $(this).val();
		if (url) {
			window.location = url;
		}
	});

	/*
	 * Toggleable navigation
	 */
	var toggleList = new CookieList("toggled");
	var toggled = toggleList.items();

	for (var i = 0; i < toggled.length; i++) {
		$('#' + toggled[i]).toggleClass('active').find('ul').hide();
	}

	$('.togglable').find('a').not('ul li ul li a').click(function() {
		var id = $(this).parent().attr('id');

		// toggle
		$(this).parent().toggleClass('active').find('ul').toggle();

		if ($(this).next().css('display') == 'none') {
			toggleList.add(id);
		} else {
			toggleList.remove(id);
		}
	});
});
