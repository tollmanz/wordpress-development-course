# Homework Due August 26, 2013 at 6 PM PST

This document outlines the homework that is assigned on August 19, 2013 and due on August 26, 2013 at 6 PM PST.

## Bye Bye Bye Lines

In a desperate attempt to recapture the magic they had at the beginning of the millenium, 'N Sync have decided to reunite. As part of an all out marketing blitz, they heard that WordPress powers about 18% of the internet and decided to target that market with the "Bye Bye Bye Lines" WordPress plugin. The plugin appends a "bye" line to a post's content. It allows a user to add an additional piece of information to a post in a specific format. It is like a "byline", but at the end of a post, making it a "bye line".

Given that this is the first time JT and the gang have sat down to work on a WordPress plugin, they have enlisted your services for a review of their work. After all, they do not want to be known as that band that wrote a terrible WordPress plugin. When you install the plugin, it works! It does *exactly* what it says it should do; however, you, the ever clever WordPress aficionado that you are, decide to dive into the code for a close review. Upon doing so, you find at least 5 distinct issues with the code, all of which are related to best practices, user experience and security.

For this assignment, identify 5 distinct problems with the following plugin code. Edit the code to fix the problems that you identify. Turn in your modified version of this plugin.

```php
<?php
/*
Plugin Name: Bye Bye Bye Lines
Plugin URI:  http://www.nsync.com/
Description: Display a byline at the end of a post, making it a Bye bye bye line.
Version:     1.0
Author:      'N Sync
Author URI:  http://www.nsync.com/
License:     GPLv2 or later
*/

/**
 * Set up the metabox.
 *
 * @param  string    $post_type    The post type.
 * @param  object    $post         The current post object.
 * @return void
 */
function call_meta_box( $post_type, $post ) {
	add_meta_box(
		'byebyebye_line',
		__( 'Bye Bye Bye Line', 'byebyebye_lines' ),
		'display_meta_box',
		'post',
		'side',
		'high'
	);
}

add_action( 'add_meta_boxes', 'call_meta_box', 10, 2 );

/**
 * Display the HTML for the metabox.
 *
 * @param  object    $post    The current post object
 * @param  array     $args    Additional arguments for the metabox.
 * @return void
 */
function display_meta_box( $post, $args ) {
?>
	<p>
		<label for="byeline">
			<?php _e( 'Bye Bye Bye Line', 'byebyebye_lines' ); ?>:&nbsp;
		</label>
		<input type="text" class="widefat" name="byeline" value="" />
		<em>
			<?php _e( 'HTML is not allowed', 'byebyebye_lines' ); ?>
		</em>
	</p>
<?php
}

/**
 * Save the metabox.
 *
 * @param  int       $post_id    The ID for the current post.
 * @param  object    $post       The current post object.
 */
function save_meta_box( $post_id, $post ) {
	if ( ! isset( $_POST['byeline'] ) ) {
		return;
	}

	$byeline = $_POST['byeline'];
	update_post_meta( $post_id, 'byebyebye-line', $byeline );
}

add_action( 'save_post', 'save_meta_box', 10, 2 );

/**
 * Append the Bye Bye Bye Line to the content.
 *
 * @param  string    $content    The original content.
 * @return string                The altered content.
 */
function print_byebyebye_line( $content ) {
	$byebyebye_line = get_post_meta( get_the_ID(), 'byebyebye-line', true );
	return $content . $byebyebye_line;
}

add_filter( 'the_content', 'print_byebyebye_line' );
```

## What to turn in

By August 26, 2013 at 6 PM PST, you must send me an email (see syllabus for email address) with all of the following: 

* Email me a copy of your plugin as a .zip file. I should be able to easily install the plugin through the WordPress admin.
* Email me a link to the plugin on your Github account. It is *very important* that I am able to see your work on this assignment through Github commits.