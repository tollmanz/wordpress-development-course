## Meta Boxes

* In the write screens for CPTs, additional interface elements can be displayed in metaboxes.
* An API exists to add additional metaboxes to the write screen.

## add_meta_box()

* `id`: HTML id attribute for the box
* `title`: Title that displays on the metabox
* `callback`: Name of the function that displays the HTML 
* `post_type`: Type of post to display the metabox on
* `context`: Part of the page to display the metabox
* `priority`: Where to display the metabox in the context relative to other ones
* `callback_args`: Values to send to the callback function

* Resource: http://www.wproots.com/complex-meta-boxes-in-wordpress/

## Post meta

* An API for setting/getting data from the `wp_postmeta` table

### get_post_meta()

* `post_id`: the ID to associate the meta with
* `key`: the name of the post meta
* `single`: if true, result is returned as a string

### update_post_meta()

* `post_id`: the ID to associate the meta with
* `key`: the name of the post meta
* `value`: the value of the metadata
* `prev_value`: old value of the meta

## Example

```php
<?php
/*
Plugin Name: Weather Display
Plugin URI: http://tollmanz.com
Description: Display the temperature when publishing a post.
Version: 1.0
Author: Zack Tollman
Author URI: http://tollmanz.com
License: GPLv2 or later
*/


/**
 * Registers the metabox.
 *
 * @param  string    $post_type    The current post type.
 * @param  object    $post         The current post object.
 * @return void
 */
function zdt_call_meta_box( $post_type, $post ) {
	// Registers the metabox for display
	add_meta_box(
		'weather_box',
		__( 'Current Weather', 'zdt_plugin' ),
		'zdt_display_meta_box',
		'post',
		'side',
		'high'
	);
}

add_action( 'add_meta_boxes', 'zdt_call_meta_box', 10, 2 );

/**
 * Renders the HTML for the metabox.
 *
 * @param  object    $post    The current post object.
 * @param  array     $args    Arguments passed to the metabox function.
 * @return void
 */
function zdt_display_meta_box( $post, $args ) {
	wp_nonce_field( plugins_url( __FILE__ ), 'zdt_plugin_noncename' );
?>
	<p>
		<label for="zdt-temperature">
			<?php _e( 'Temperature (&deg;F)', 'zdt_plugin' ); ?>:&nbsp;
		</label>
		<input type="text" name="zdt-temperature" value="<?php echo get_post_meta( $post->ID, 'zdt-temperature', true ); ?>"/>
		<br />
		<em>
			<?php _e( 'Must be a numeric value', 'zdt_plugin' ); ?>
		</em>
	</p>
<?php
}

/**
 * Save the metabox content.
 *
 * @param  int       $post_id    The post ID for the current post.
 * @param  object    $post       The current post object.
 * @return void
 */
function zdt_save_meta_box( $post_id, $post ) {
	// Do not save during autosave routines
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// Verify permissions before saving
	if ( 'page' === $_POST[ 'post_type' ] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Check the nonce to secure against CSRF
	if ( isset( $_POST[ 'zdt_plugin_noncename' ] ) && wp_verify_nonce( $_POST[ 'zdt_plugin_noncename' ], plugins_url( __FILE__ ) ) ) {
		if ( is_numeric( $_POST[ 'zdt-temperature' ] ) ) {
			update_post_meta( $post_id, 'zdt-temperature', absint( $_POST[ 'zdt-temperature' ] ) );
		} else {
			delete_post_meta( $post_id, 'zdt-temperature' );
		}
	}

	return;
}

add_action( 'save_post', 'zdt_save_meta_box', 10, 2 );

/**
 * Display the temperature information.
 *
 * @param  string    $content    The current content.
 * @return string                The modified content.
 */
function zdt_display_temperature( $content ) {
	$temp = get_post_meta( get_the_ID(), 'zdt-temperature', true );
	$temp = absint( $temp );
	$new_content = '<em>It was ' . $temp . '&deg; F when I published this post.</em>';
	return $content . $new_content;
}

add_filter( 'the_content', 'zdt_display_temperature', 40, 1 );
```