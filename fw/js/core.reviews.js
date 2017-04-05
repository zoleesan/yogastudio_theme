/* global jQuery:false */
/* global YOGASTUDIO_GLOBALS:false */

jQuery(document).ready(function () {
	"use strict";

	YOGASTUDIO_GLOBALS['reviews_user_accepted'] = false;

	yogastudio_add_hidden_elements_handler('init_reviews', yogastudio_init_reviews);

	yogastudio_init_reviews(jQuery('body'));
});


// Init reviews elements
function yogastudio_init_reviews(cont) {
	"use strict";

	// Drag slider - set new rating
	cont.find('.reviews_editable .reviews_slider:not(.inited)').each(function() {
		"use strict";
		if (typeof(YOGASTUDIO_GLOBALS['reviews_allow_user_marks'])=='undefined' || !YOGASTUDIO_GLOBALS['reviews_allow_user_marks']) return;
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this).addClass('inited');
		var row  = jQuery(this).parents('.reviews_item');
		var wrap = jQuery(this).parents('.reviews_stars_wrap');
		var rangeMin = 0;
		var rangeMax = parseInt(row.data('max-level'));
		var step  = parseFloat(row.data('step'));
		var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
		var grid  = Math.max(1, (wrap.width()-jQuery(this).width()) / (rangeMax - rangeMin) / prec);
		// Move slider to init position
		var val = parseFloat(row.find('input[type="hidden"]').val());
		var x = Math.round((val - rangeMin) * (wrap.width()-jQuery(this).width()) / (rangeMax - rangeMin));
		yogastudio_reviews_set_current_mark(row, val, x, false);
		jQuery(this).draggable({
			axis: 'x',
			grid: [grid, grid],
			containment: '.reviews_stars_wrap',
			scroll: false,
			drag: function (e, ui) {
				"use strict";
				var pos = ui.position.left >= 0 ? ui.position.left : ui.originalPosition.left + ui.offset.left;
				var val = Math.min(rangeMax, Math.max(rangeMin, Math.round(pos * prec * (rangeMax - rangeMin) / (wrap.width()-jQuery(this).width())) / prec + rangeMin));
				yogastudio_reviews_set_current_mark(row, val);
			}
		});
	});


	// Click on stars - set new rating
	cont.find('.reviews_editor .reviews_editable .reviews_stars_wrap:not(.inited),.reviews_editor .reviews_max_level_100 .reviews_criteria:not(.inited)').each(function() {
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this)
			.addClass('inited')
			.on('click', function (e) {
				"use strict";
				if (typeof(YOGASTUDIO_GLOBALS['reviews_allow_user_marks'])=='undefined' || !YOGASTUDIO_GLOBALS['reviews_allow_user_marks']) return;
				if (jQuery(this).hasClass('reviews_criteria') && !jQuery(this).next().hasClass('reviews_editable')) return;
				var wrap = jQuery(this).hasClass('reviews_criteria') ? jQuery(this).next() : jQuery(this);
				var row  = wrap.parents('.reviews_item');
				var wrapWidth = wrap.width()-wrap.find('.reviews_slider').width();
				var rangeMin = 0;
				var rangeMax = parseInt(row.data('max-level'));
				var step  = parseFloat(row.data('step'));
				var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
				var grid  = wrapWidth / (rangeMax - rangeMin + 1) / step;
				var wrapX = e.pageX - wrap.offset().left;
				if (wrapX <= 1) wrapX = 0;
				if (wrapX > wrapWidth) wrapX = wrapWidth;
				var val = Math.min(rangeMax, Math.max(rangeMin, Math.round(wrapX * prec * (rangeMax - rangeMin) / wrapWidth) / prec + rangeMin));
				yogastudio_reviews_set_current_mark(row, val, wrapX);
			});
	});


	// Save user's marks
	cont.find('.reviews_accept:not(.inited)').each(function() {
		if (jQuery(this).parents('div:hidden,article:hidden').length > 0) return;
		jQuery(this)
			.addClass('inited')
            .on('click', 'a', function(e) {
				"use strict";
				if (typeof(YOGASTUDIO_GLOBALS['reviews_allow_user_marks'])=='undefined' || !YOGASTUDIO_GLOBALS['reviews_allow_user_marks']) return;
				var marks_cnt = 0;
				var marks_sum = 0;
				var marks_accept = jQuery(this).parents('.reviews_accept');
				var marks_panel = marks_accept.siblings('.reviews_editor');
				marks_panel.find('input[type="hidden"]').each(function (idx) {
					"use strict";
					var row  = jQuery(this).parents('.reviews_item');
					var step  = parseFloat(row.data('step'));
					var prec  = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
					var mark = parseFloat(jQuery(this).val());
					if (isNaN(mark)) mark = 0;
					YOGASTUDIO_GLOBALS['reviews_marks'][idx] = Math.round(((YOGASTUDIO_GLOBALS['reviews_marks'].length>idx && YOGASTUDIO_GLOBALS['reviews_marks'][idx]!='' 
						? parseFloat(YOGASTUDIO_GLOBALS['reviews_marks'][idx])*YOGASTUDIO_GLOBALS['reviews_users'] 
						: 0) + mark) / (YOGASTUDIO_GLOBALS['reviews_users']+1)*prec)/prec;
					jQuery(this).val(YOGASTUDIO_GLOBALS['reviews_marks'][idx]);
					marks_cnt++;
					marks_sum += mark;
				});
				if (marks_sum > 0) {
					if (YOGASTUDIO_GLOBALS['reviews_marks'].length > marks_cnt)
						YOGASTUDIO_GLOBALS['reviews_marks'] = YOGASTUDIO_GLOBALS['reviews_marks'].splice(marks_cnt, YOGASTUDIO_GLOBALS['reviews_marks'].length-marks_cnt)
					YOGASTUDIO_GLOBALS['reviews_users']++;
					marks_accept.fadeOut();
					jQuery.post(YOGASTUDIO_GLOBALS['ajax_url'], {
						action: 'reviews_users_accept',
						nonce: YOGASTUDIO_GLOBALS['ajax_nonce'],
						post_id: YOGASTUDIO_GLOBALS['post_id'],
						marks: YOGASTUDIO_GLOBALS['reviews_marks'].join(','),
						users: YOGASTUDIO_GLOBALS['reviews_users']
					}).done(function(response) {
						var rez = {};
						try {
							rez = JSON.parse(response);
						} catch (e) {
							rez = { error: YOGASTUDIO_GLOBALS['ajax_error'] };
							console.log(response);
						}
						if (rez.error === '') {
							YOGASTUDIO_GLOBALS['reviews_allow_user_marks'] = false;
							yogastudio_set_cookie('yogastudio_votes', YOGASTUDIO_GLOBALS['reviews_vote'] + (YOGASTUDIO_GLOBALS['reviews_vote'].substr(-1)!=',' ? ',' : '') + YOGASTUDIO_GLOBALS['post_id'] + ',', 365);
							marks_panel.find('.reviews_item').each(function (idx) {
								jQuery(this).data('mark', YOGASTUDIO_GLOBALS['reviews_marks'][idx])
									.find('input[type="hidden"]').val(YOGASTUDIO_GLOBALS['reviews_marks'][idx]).end()
									.find('.reviews_value').html(YOGASTUDIO_GLOBALS['reviews_marks'][idx]).end()
									.find('.reviews_stars_hover').css('width', Math.round(YOGASTUDIO_GLOBALS['reviews_marks'][idx]/YOGASTUDIO_GLOBALS['reviews_max_level']*100) + '%');
							});
							yogastudio_reviews_set_average_mark(marks_panel);
							marks_panel.find('.reviews_stars').removeClass('reviews_editable');
							marks_panel.siblings('.reviews_summary').find('.reviews_criteria').html(YOGASTUDIO_GLOBALS['strings']['reviews_vote']);
						} else {
							marks_panel.siblings('.reviews_summary').find('.reviews_criteria').html(YOGASTUDIO_GLOBALS['strings']['reviews_error']);
						}
					});
				}
				e.preventDefault();
				return false;
			});
	});
}


// Set current mark value
function yogastudio_reviews_set_current_mark(row, val) {
	"use strict";
	var x = arguments[2]!=undefined ? arguments[2] : -1;
	var clear = arguments[3]!=undefined ? arguments[3] : true;
	var rangeMin = 0;
	var rangeMax = parseInt(row.data('max-level'));
	row.find('.reviews_value').html(val);
	row.find('input[type="hidden"]').val(val).trigger('change');
	row.find('.reviews_stars_hover').css('width', Math.round(row.find('.reviews_stars_bg').width()*val/(rangeMax-rangeMin))+'px');
	if (x >=0) row.find('.reviews_slider').css('left', x+'px');
	// Clear user marks and show Accept Button
	if (!YOGASTUDIO_GLOBALS['admin_mode'] && !YOGASTUDIO_GLOBALS['reviews_user_accepted'] && clear) {
		YOGASTUDIO_GLOBALS['reviews_user_accepted'] = true;
		row.siblings('.reviews_item').each(function () {
			"use strict";
			jQuery(this).find('.reviews_stars_hover').css('width', 0);
			jQuery(this).find('.reviews_value').html('0');
			jQuery(this).find('.reviews_slider').css('left', 0);
			jQuery(this).find('input[type="hidden"]').val('0');
		});
		// Show Accept button
		row.parent().next().fadeIn();
	}
	yogastudio_reviews_set_average_mark(row.parents('.reviews_editor'));
}

// Show average mark
function yogastudio_reviews_set_average_mark(obj) {
	"use strict";
	var avg = 0;
	var cnt = 0;
	var rangeMin = 0;
	var rangeMax = parseInt(obj.find('.reviews_item').eq(0).data('max-level'));
	var step = parseFloat(obj.find('.reviews_item').eq(0).data('step'));
	var prec = Math.pow(10, step.toString().indexOf('.') < 0 ? 0 : step.toString().length - step.toString().indexOf('.') - 1);
	obj.find('input[type="hidden"]').each(function() {
		avg += parseFloat(jQuery(this).val());
		cnt++;
	});
	avg = cnt > 0 ? avg/cnt : 0;
	avg = Math.min(rangeMax, Math.max(rangeMin, Math.round(avg * prec) / prec + rangeMin));
	var summary = obj.siblings('.reviews_summary');
	summary.find('.reviews_value').html(avg);
	summary.find('input[type="hidden"]').val(avg).trigger('change');
	summary.find('.reviews_stars_hover').css('width', Math.round(summary.find('.reviews_stars_bg').width()*avg/(rangeMax-rangeMin))+'px');
}

// Convert percent to rating marks level
function yogastudio_reviews_marks_to_display(mark) {
	"use strict";
	if (YOGASTUDIO_GLOBALS['reviews_max_level'] < 100) {
		mark = Math.round(mark / 100 * YOGASTUDIO_GLOBALS['reviews_max_level'] * 10) / 10;
		if (String(mark).indexOf('.') < 0) {
			mark += '.0';
		}
	}
	return mark;
}

// Get word-value review rating
function yogastudio_reviews_get_word_value(r) {
	"use strict";
	var words = YOGASTUDIO_GLOBALS['reviews_levels'].split(',');
	var k = YOGASTUDIO_GLOBALS['reviews_max_level'] / words.length;
	r = Math.max(0, Math.min(words.length-1, Math.floor(r/k)));
	return words[r];
}
