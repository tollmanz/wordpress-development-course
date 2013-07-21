## Adding JS and CSS

* WordPress uses an "enqueues" system for adding JS and CSS to a page. 
* The enqueue system allows a developer to declare dependencies and use JS/CSS that is included with WordPress.
* Not using the enqueues system can cause plugin conflicts that can break a site.
	* e.g., Loading jQuery twice will cause JS errors
	* e.g., Loading scripts out of order will cause JS errors

### wp_enqueue_script()

Arguments:

* `handle`: a unique id for the script
* `src`: URL of the script
* `deps`: scripts that this script depends on
* `ver`: the version of the script
* `in_footer`: whether or not to load the script in the footer

Example:

```php
wp_enqueue_script(
	'my-responsiveslides',
	plugins_url( '/responsiveslides.js', __FILE__ ),
	array( 'jquery' ),
	'1.54',
	true
);
```

Results in:

```html
...
<head>
	...
	<script type='text/javascript' src='http://wdim393f.dev/wp-includes/js/jquery/jquery.js?ver=1.8.3'></script>
	<script type='text/javascript' src='http://wdim393f.dev/wp-content/plugins/responsiveslides/responsiveslides.js?ver=1.54'></script>
	...
</head>
...
```

### wp_enqueue_style()

Arguments:

* `handle`: a unique id for the script
* `src`: URL of the script
* `deps`: scripts that this script depends on
* `ver`: the version of the script
* `media`: the link tag media element

Example:

```php
wp_enqueue_style(
	'my-responsiveslides',
	plugins_url( '/responsiveslides.css', __FILE__ ),
	array(),
	'1.54',
	'all'
);
```

Results in:

```html
...
<head>
	...
	<link rel='stylesheet' id='my-responsiveslides-css'  href='http://wdim393f.dev/wp-content/plugins/responsiveslides/responsiveslides.css?ver=1.54' type='text/css' media='all' />
	...
</head>
...
```

### Example Plugin

**Pimpletrest** adds a Pinterest button to the end of a single post page's content.

1. How is a Pinterest button added?
	* According to the [docs](http://business.pinterest.com/widget-builder/#do_pin_it_button), you need to add a script:
	
	```html
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	```
	
	* You then need to add some HTML where you want the button:
	
	```html
	<a href="//pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" >
		<img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" />
	</a>
	```

1. Add the script via `wp_enqueue_script()`.
1. Add the button via `the_content` filter.

```php
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
	// Load the Pinterest script in the footer
	wp_enqueue_script(
		'pimple-pinterest',
		'//assets.pinterest.com/js/pinit.js',
		array(),
		null,
		true
	);
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
	if ( is_single() ) {
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
```

References:

* [How to Include Javascript and CSS in Your WordPress Themes and Plugins](http://wp.tutsplus.com/articles/how-to-include-javascript-and-css-in-your-wordpress-themes-and-plugins/)

## Add Options Page

* WordPress provides a simple API for adding new pages in the admin screen.
* There are functions that allow you to add pages to each part of the admin screen.
* To add a page that is organized under the "Settings" tab, use `add_options_page()`

### add_options_page()

Arguments:

* `page_title`: text displayed in the page's `<title>` element
* `menu_title`: text displayed in the menu
* `capability`: capability required for the menu to be displayed
* `menu_slug`: the unique id used to refer to this page
* `function`: the function used that generates the markup for the page

Example:

```php
function zdt_add_options_page() {
	add_options_page(
		__( 'ZDT Options' ),
		__( 'ZDT Options' ),
		'manage_options',
		'zdt_options_page',
		'zdt_render_options_page'
	);
}
add_action( 'admin_menu', 'zdt_add_options_page' );

function zdt_render_options_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php _e( 'The Options' ); ?></h2>
		<p>This is the page.</p>
	</div>
	<?php
}
```

Results in:

![Sample options page](https://raw.github.com/tollmanz/wordpress-development-course/master/lectures/assets/sample-options-page.png)

### Add Options Page to Pimpletrest Plugin

1. Need a page that will eventually be used for plugin settings
1. First, register the page
1. Second, render the page

```php
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
	// Load the Pinterest script in the footer
	wp_enqueue_script(
		'pimple-pinterest',
		'//assets.pinterest.com/js/pinit.js',
		array(),
		null,
		true
	);
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
	if ( is_single() ) {
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
	</div>
	<?php
}
```

References:

* [WordPress Codex: add_options_page](http://codex.wordpress.org/Function_Reference/add_options_page)
* [WordPress Codex: Administration Menus](http://codex.wordpress.org/Administration_Menus)
* Professional WordPress Plugin Development, p 170 (Williams, Richard, & Tadlock, 2001)

## The Settings API

* Easy-ish way to add consistent settings screen to a plugin or theme.
* Handles biolerplate code for adding and managing settings.
* Involves 3 functions:
	* `register_setting`
	* `add_settings_section`
	* `add_settings_field`
* Add a setting section, which contains settings field that are composed of registered settings.

### register_setting()

* Defines a setting in WordPress

Arguments:

* `option_group`: defines what group of options the setting belongs to. The group is defined in `settings_field()`
* `option_name`: the name of the option
* `sanitize_callback`: the name of the function used to sanitize/validate the setting before saving it to the database

### add_settings_section()

* Defines a settings section on a settings page. Settings section are grouped together via a shared heading.

Arguments:

* `id`: unique identifier for a section
* `title`: the text for the header of the section
* `callback`: function that renders the content for the section
* `page`: menu page on which to display the section

### add_settings_field()

* Adds a settings field that corresponds with a registered setting in an add settings section

Arguments:

* `id`: HTML ID attribute of the field
* `title`: label used for the field
* `callback`: function that renders the field display
* `page`: the menu page to display the field on
* `section`: section in which to display the field
* `args`: additional arguments to pass the to callback function

### Add a Setting to Pimpletrest

Add a setting that allows a user to turn the Pinterest buttons on and off.

1. Register a setting for disabling the button
1. Add a settings field to add an interface for the setting
1. Add a settings section to hold the settings field

```php
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
	// Load the Pinterest script in the footer
	wp_enqueue_script(
		'pimple-pinterest',
		'//assets.pinterest.com/js/pinit.js',
		array(),
		null,
		true
	);
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
	if ( is_single() ) {
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
```

Results in:

![Pimpletrest settings page](https://raw.github.com/tollmanz/wordpress-development-course/master/lectures/assets/pimpletrest-settings.png)

References:

* [WordPress Settings API Tutorial](http://ottopress.com/2009/wordpress-settings-api-tutorial/)
* [WordPress Codex: Settings API](http://codex.wordpress.org/Settings_API)

## Options API

* Easily add, update, get and delete options.
* Stores data in the `wp_options` table

### add_option

* Adds an option to the database

Arguments:

* `option`: name of the option
* `value`: value of the option
* `deprecated`: old argument that no longer exists. Should always be ''.
* `autoload`: whether or not to automatically get the option on each page load

### update_option

* Update an existing option
* If the option doesn't exist, it will be created

Arguments:

* `option`: name of the option
* `value`: value of the option

### get_option

* Retrieves the value of an option from the database

Arguments:

* `option`: the name of the option
* `default`: the value returned if the option is not found

### delete_option

* Remove an option from the database. 

Arguments:

* `option`: name of the option to delete

### Use the "pimple_disable" Option

The "pimple_disable_button" has been saved to the database. Inspect the value of the option before printing Pinterest buttons.

```php
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
```