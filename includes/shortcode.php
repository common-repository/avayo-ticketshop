<?php

add_shortcode('avayo', 'avayo_shortcode');

function avayo_frontend_scripts() {
    wp_enqueue_script( 'avayo-ticketscript', 'https://storage.googleapis.com/avayo-production/assets/ticketshop.js', array(), '1.0.0' );
}

add_action( 'wp_enqueue_scripts', 'avayo_frontend_scripts' );

function avayo_shortcode($atts) {

	if( esc_attr( get_option('avayo_account') ) == '' )
		return __('Avayo - U heeft geen account ingesteld.', 'avayo-plugin');

	$script = '<script type="text/javascript">Avayo.init("' . esc_attr( get_option('avayo_account') ) . '");';

	if( isset($atts['show_banner']) ) {
		$script .= 'Avayo.show_banner(true);';
	}

	if( isset($atts['locale']) ) {
		$script .= "Avayo.locale('" . $atts['locale'] . "');";
	}

	if( isset($atts['ga4_tag_id']) ) {
		$script .= "Avayo.trackingId('" . $atts['ga4_tag_id'] . "');";
	}

	if( isset($atts['event_id']) && is_numeric($atts['event_id']) ) {
		$script .= 'Avayo.event_id(' . intval($atts['event_id']) . ');';
	}

	if( isset($atts['p_id']) && is_numeric($atts['p_id']) ) {
		$script .= 'Avayo.p_id(' . intval($atts['event_id']) . ');';
	}

	if( isset($atts['display']) && $atts['display'] == 'mini' ) {
		$script .= 'Avayo.miniCalendar(' . intval($atts['mini_items']) . ');</script>';
	} else {
		$script .= 'Avayo.insertTicketshop();</script>';
	}

	return $script;
}