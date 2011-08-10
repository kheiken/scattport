/**
 *
 */
function getNotifications() {
	$.get(SITE_URL + 'ajax/get_notifications', function(data) {
		if (data.length > 0) {
			$('#notifications').append(data).slideDown();
		}
	});
}

/**
 * Saves the changes done by in-place edit.
 *
 * @param obj
 * @param cancel
 */
function saveChanges(obj, cancel) {
	var a;

	if (!cancel) {
		a = $(obj).parent().siblings(0).val();
		$.post(SITE_URL + 'ajax/save_project', { content: a }, function(response) {
			alert("Die Änderungen wurden gespeichert.");
		});
	} else {
		a = cancel;
	}

	$(obj).parent().parent().after('<div class="editInPlace">' + a + '</div>').remove();
}

/**
 * Alternates the table rows.
 */
$.fn.alternateRowColors = function() {
	$('tbody tr:odd', this).removeClass('even').addClass('odd');
	$('tbody tr:even', this).removeClass('odd').addClass('even');
	return this;
};

/**
 * Replaces html line breaks with newlines.
 */
$.fn.br2nl = function() {
	return $(this).html().replace(/(<br>)|(<br \/>)|(<p>)|(<\/p>)/g, "");
};

/**
 * Similar to PHP's nl2br function.
 */
$.fn.nl2br = function() {
	var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	return $(this).html().replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
};

/*
 * Tabs
 */
$(document).ready(function() {
	$(".tab_content").hide(); // hide all content
	$("ul.tabs li:first").addClass("active").show(); // activate first tab
	$(".tab_content:first").show(); // show first tab content

	// onClick event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); // remove any "active" class
		$(this).addClass("active"); // add "active" class to selected tab
		$(".tab_content").hide(); // hide all tab content

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn(); // fade in the active tab content
		return false;
	});

	$('#notifications').hide();
	getNotifications();
	setInterval('getNotifications()', '5000');
});

/**
 * Do some stuff if document is ready.
 */
$(document).ready(function() {
	/*
	 * Tables
	 */
	var settings = {
			table_class : 'tableList'
	};

	// add or delete "hover" class on mouseOver event
	$('.' + settings.table_class + ' tbody tr').hover(function() {
		$(this).addClass("hover");
	}, function() {
		$(this).removeClass("hover");
	});

	// add or delete "selected" class if a row is selected via checkbox
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

	$('.sortable').tableDnD();

	/*
	 * In-place editor
	 */
	$('.editInPlace').click(function() {
		var textarea = '<div><textarea rows="6" cols="60">' + $(this).br2nl() + '</textarea><p></p>';
		var button = '<p><a class="button save" href="javascript:void(0);">Speichern</a> <a class="button cancel" href="javascript:void(0);">Abbrechen</a></div>';
		var revert = $(this).html();

		$(this).after(textarea + button).remove();

		$('.save').click(function() {
			saveChanges(this, false);
		});
		$('.cancel').click(function() {
			saveChanges(this, revert);
		});
	}).mouseover(function() {
		$(this).addClass('editable');
	}).mouseout(function() {
		$(this).removeClass('editable');
	});

	$('select[name="activeProject"]').bind('change', function () {
		var url = $(this).val();
		if (url) {
			window.location = url;
		}
	});
});
