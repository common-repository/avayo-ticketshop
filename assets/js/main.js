jQuery(document).ready(function($) {

	function updateShortcode() {
		var inputs = ['display', 'mini_items', 'ga4_tag_id', 'event_id', 'p_id', 'show_banner', 'locale'];
		var shortcode = '[avayo';
		inputs.forEach(function(cur) {
			if ((cur === 'display') && ($('*[name="avayo_' + cur + '"]').val() !== 'mini')) {
				return;
			}
			if ((cur === 'mini_items') && ($('*[name="avayo_display"]').val() !== 'mini')) {
				return;
			}
			if( $('*[name="avayo_' + cur + '"]').length > 0 ) {
				if( $('*[name="avayo_' + cur + '"]').attr('type') == 'checkbox' && $('*[name="avayo_' + cur + '"]').is(':checked') ) {
					shortcode += ' ' + cur + '="' + $('*[name="avayo_' + cur + '"]').val() + '"';
				} else if( $('*[name="avayo_' + cur + '"]').val() != '' && $('*[name="avayo_' + cur + '"]').attr('type') != 'checkbox' ) {
					shortcode += ' ' + cur + '="' + $('*[name="avayo_' + cur + '"]').val() + '"';
				}
			}
		})
		shortcode += ']';

		$('#shortcode').val(shortcode).change();
	}

	updateShortcode();

	$('body').on('change paste keyup', 'input[name="avayo_ga4_tag_id"], input[name="avayo_mini_items"], input[name="avayo_event_id"], input[name="avayo_p_id"], input[name="avayo_show_banner"], select[name="avayo_display"], select[name="avayo_locale"]', function(e) {
		updateShortcode();
	})

	$('body').on('change', 'select[name="avayo_display"]', function(e) {
		if ($(this).children("option:selected").val() == 'mini')
			$('#mini_items_column').show();
		else
			$('#mini_items_column').hide();
	})

	$('body').on('click', '.button--copy', function(e) {
		e.preventDefault();
		var copyText = document.getElementById("shortcode");
		copyText.select();
		document.execCommand("Copy");
	})

	$('#shortcode').change(resizeInput).each(resizeInput);

	function resizeInput() {
	    $(this).attr('size', $(this).val().length);
	}

})