<?php
/*
Plugin Name: Pimpletrest
Plugin URI: http://github.com/tollmanz/wordpress-development-course
Version: 1.0
Description: Adds a pinterest button to single posts.
Author: Zack Tollman
Author URI: http://tollmanz.com
License: GNU General Public License v2 or later
*/

/**
 * Add the Pinterest script prior to the closing body tag.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_enqueue_script() {
	if ( '0' === get_option( 'pimple_disable_button', '0' ) ) {
		// Load the Pinterest script in the footer
		wp_enqueue_script(
			'pimple-pinterest',
			'//assets.pinterest.com/js/pinit.js',
			array(),
			null,
			true
		);
	}
}

add_action( 'wp_enqueue_scripts', 'pimple_enqueue_script' );

/**
 * Append the Pinterest button to content on single post pages.
 *
 * @since  1.0.
 *
 * @param  string    $content    The original content.
 * @return string                The modified content.
 */
function pimple_add_button( $content ) {
	if ( is_single() && '0' === get_option( 'pimple_disable_button', '0' ) ) {
		// Create the Pinterest button HTML
		$button_html  = '<a href="//pinterest.com/pin/create/button/" data-pin-do="buttonBookmark">';
		$button_html .= '<img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" />';
		$button_html .= '</a>';

		// Append the button to the content
		$content .= $button_html;
	}

	return $content;
}

add_filter( 'the_content', 'pimple_add_button', 20 );

/**
 * Add an options page for the plugin.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_add_options_page() {
	// Add new page under the "Settings tab
	add_options_page(
		__( 'Pimpletrest Options' ),
		__( 'Pimpletrest Options' ),
		'manage_options',
		'pimple_options_page',
		'pimple_render_options_page'
	);
}

add_action( 'admin_menu', 'pimple_add_options_page' );

/**
 * Render the options page.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_render_options_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'Pimpletrest Options' ); ?></h2>
		<form action="options.php" method="post">
			<?php settings_fields( 'pimple_disable_button' ); ?>
			<?php do_settings_sections( 'pimple_options_page' ); ?>
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes' ); ?>">
			</p>
		</form>
	</div>
	<?php
}

/**
 * Setup a setting for disabling the Pinterest button.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_add_disable_button_setting() {
	// Register a binary value called "pimple_disable"
	register_setting(
		'pimple_disable_button',
		'pimple_disable_button',
		'absint'
	);

	// Add the settings section to hold the interface
	add_settings_section(
		'pimple_main_settings',
		__( 'Pimpletrest Controls' ),
		'pimple_render_main_settings_section',
		'pimple_options_page'
	);

	// Add the settings field to define the interface
	add_settings_field(
		'pimple_disable_button_field',
		__( 'Disable Pinterest Buttons' ),
		'pimple_render_disable_button_input',
		'pimple_options_page',
		'pimple_main_settings'
	);
}

add_action( 'admin_init', 'pimple_add_disable_button_setting' );

/**
 * Render text to be displayed in the "pimple_main_settings" section.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_render_main_settings_section() {
	echo '<p>Main settings for the Pimplestrest plugin.</p>';
}

/**
 * Render the input for the "pimple_disable_button" setting.
 *
 * @since  1.0.
 *
 * @return void
 */
function pimple_render_disable_button_input() {
	// Get the current value
	$current = get_option( 'pimple_disable_button', 0 );
	echo '<input id="pimple-disable-button" name="pimple_disable_button" type="checkbox" value="1" ' . checked( 1, $current, false ) . ' />';
}