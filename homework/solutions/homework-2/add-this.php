<?php
/*
Plugin Name: Sharkquake Add This Buttons
Plugin URI: http://sharkquake.com
Version: 1.0
Description: Adds AddThis buttons to the single post pages.
Author: Zack Tollman
Author URI: http://tollmanz.com
License: GNU General Public License v2 or later
*/

/**
 * Print the necessary script in the footer.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_enqueue_scripts() {
	// Do not add the script if not on a single post or if AddThis is disabled
	if ( ! is_single() || '1' === get_option( 'squake_disable_addthis', 0 ) ) {
		return;
	}

	// Add the script to the footer
	wp_enqueue_script(
		'squake-add-this',
		'//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-51ec768100376693',
		array(),
		false,
		true
	);
}

add_action( 'wp_enqueue_scripts', 'squake_enqueue_scripts' );

/**
 * Add the configuration script to enable the buttons.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_add_addthis_script() {
	// Do not add the script if not on a single post or if AddThis is disabled
	if ( ! is_single() || '1' === get_option( 'squake_disable_addthis', 0 ) ) {
		return;
	}

	// Get the settings for use in the JS
	$position = get_option( 'squake_position', 'left' );
	$buttons  = get_option( 'squake_number_of_buttons', 2 );

	// Guarantee that buttons is in the acceptable range
	$buttons = ( $buttons < 1 || $buttons > 6 ) ? 2 : $buttons;
?>
	<script type="text/javascript">
		addthis.layers({
			'theme' : 'gray',
			'share' : {
				'position' : '<?php echo sanitize_key( $position ); ?>',
				'numPreferredServices' : <?php echo absint( $buttons ); ?>
			},
			'whatsnext' : {},
			'recommended' : {
				'title': "<?php echo esc_js( get_the_title() ); ?>"
			}
		});
	</script>
<?php
}

add_action( 'wp_footer', 'squake_add_addthis_script', 20 );

/**
 * Add an options page for the plugin.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_add_options_page() {
	// Add new page under the "Settings tab
	add_options_page(
		__( 'AddThis Options' ),
		__( 'AddThis Options' ),
		'manage_options',
		'squake_options_page',
		'squake_render_options_page'
	);
}

add_action( 'admin_menu', 'squake_add_options_page' );

/**
 * Render the options page.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_render_options_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'AddThis Options' ); ?></h2>
		<form action="options.php" method="post">
			<?php settings_fields( 'squake_settings' ); ?>
			<?php do_settings_sections( 'squake_options_page' ); ?>
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
function squake_add_settings() {
	// Add the settings section to hold the interface
	add_settings_section(
		'squake_main_settings',
		__( 'AddThis Controls' ),
		'squake_render_main_settings_section',
		'squake_options_page'
	);

	// Register a position option which is either "right" or "left"
	register_setting(
		'squake_settings',
		'squake_position',
		'squake_validate_position'
	);

	// Add the settings field to define the interface
	add_settings_field(
		'squake_position_field',
		__( 'Button position' ),
		'squake_render_position_input',
		'squake_options_page',
		'squake_main_settings'
	);

	// Register an option to define the number of buttons to show
	register_setting(
		'squake_settings',
		'squake_number_of_buttons',
		'absint'
	);

	// Add the settings field to define the interface
	add_settings_field(
		'squake_number_of_buttons_field',
		__( 'Number of buttons' ),
		'squake_render_number_of_buttons_input',
		'squake_options_page',
		'squake_main_settings'
	);

	// Register a binary value called "squake_disable"
	register_setting(
		'squake_settings',
		'squake_disable_addthis',
		'absint'
	);

	// Add the settings field to define the interface
	add_settings_field(
		'squake_disable_addthis_field',
		__( 'Disable AddThis buttons' ),
		'squake_render_disable_button_input',
		'squake_options_page',
		'squake_main_settings'
	);
}

add_action( 'admin_init', 'squake_add_settings' );

/**
 * Render text to be displayed in the "squake_main_settings" section.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_render_main_settings_section() {
?>
	<p>Main settings for the AddThis plugin.</p>
<?php
}

/**
 * Render the input for the "squake_position" option.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_render_position_input() {
	// Get the current value and default to "left"
	$position = get_option( 'squake_position', 'left' )
?>
	<input type="radio" value="left" name="squake_position" <?php checked( 'left', $position ); ?> />
	&nbsp;&nbsp;<?php _e( 'Left' ); ?>
	<br />
	<input type="radio" value="right" name="squake_position" <?php checked( 'right', $position ); ?> />
	&nbsp;&nbsp;<?php _e( 'Right' ); ?>
<?php
}

/**
 * Validate the position value.
 *
 * The value can either be "left" or "right". Accept nothing else.
 *
 * @since  1.0.
 *
 * @param  string    $value    The value of the option.
 * @return string              The validated value of the option.
 */
function squake_validate_position( $value ) {
	// If not a valid value, default to 'left'
	if ( ! in_array( $value, array( 'right', 'left' ) ) ) {
		$value = 'left';
	}

	return $value;
}

/**
 * Render the input for the number of buttons option.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_render_number_of_buttons_input() {
	// Get the current number of buttons
	$number_of_buttons = get_option( 'squake_number_of_buttons', 2 );
?>
	<select name="squake_number_of_buttons">
		<option value="1" <?php selected( 1, $number_of_buttons ); ?>>1</option>
		<option value="2" <?php selected( 2, $number_of_buttons ); ?>>2</option>
		<option value="3" <?php selected( 3, $number_of_buttons ); ?>>3</option>
		<option value="4" <?php selected( 4, $number_of_buttons ); ?>>4</option>
		<option value="5" <?php selected( 5, $number_of_buttons ); ?>>5</option>
		<option value="6" <?php selected( 6, $number_of_buttons ); ?>>6</option>
	</select>
<?php
}

/**
 * Render the input for the "squake_disable_addthis" setting.
 *
 * @since  1.0.
 *
 * @return void
 */
function squake_render_disable_button_input() {
	// Get the current value
	$current = get_option( 'squake_disable_addthis', 0 );
?>
	<input name="squake_disable_addthis" type="checkbox" value="1" <?php checked( 1, $current, false ); ?> />
<?php
}