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
