<?php
/*
Plugin Name: International
Plugin URI: http://tollmanz.com
Description: Example plugin for internationalization
Version: 1.0
Author: Zack Tollman
Author URI: http://tollmanz.com
License: GPLv2 or later
*/

function i18n_func( $title ) {
	if ( 'audio' === get_post_format() ) {
		return __( 'Listen:', 'zdt-internationalization' ) . ' ' . $title;
	} elseif ( 'video' === get_post_format() ) {
		return __( 'Watch:', 'zdt-internationalization' ) . ' ' . $title;
	} else {
		return __( 'Read:', 'zdt-internationalization' ) . ' ' . $title;
	}
}

add_filter( 'the_title', 'i18n_func' );

function i18n_load_text_domain() {
	$plugin_dir = trailingslashit( basename( dirname( __FILE__ ) ) );
	load_plugin_textdomain( 'zdt-internationalization', false, $plugin_dir . 'languages/' );
}

add_action( 'plugins_loaded', 'i18n_load_text_domain' );