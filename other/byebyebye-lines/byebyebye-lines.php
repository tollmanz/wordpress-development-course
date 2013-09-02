<?php
/*
Plugin Name: Bye Bye Bye Lines
Plugin URI:  http://tollmanz.com
Description: Display a byline at the end of a post, making it a Bye bye bye line.
Version:     1.0
Author:      Zack Tollman
Author URI:  http://tollmanz.com
License:     GPLv2 or later
*/

/**
 * Set up the metabox.
 *
 * @param  string    $post_type    The post type.
 * @param  object    $post         The current post object.
 * @return void
 */
function zdt_call_meta_box( $post_type, $post ) {
	add_meta_box(
		'byebyebye_line',
		__( 'Bye Bye Bye Line', 'zdt_byebyebye_lines' ),
		'zdt_display_meta_box',
		'post',
		'side',
		'high'
	);
}

add_action( 'add_meta_boxes', 'zdt_call_meta_box', 10, 2 );

/**
 * Display the HTML for the metabox.
 *
 * @param  object    $post    The current post object
 * @param  array     $args    Additional arguments for the metabox.
 * @return void
 */
function zdt_display_meta_box( $post, $args ) {
	wp_nonce_field( 'save', 'zdt-byebyebye-line' );
	$byebyebye_line = get_post_meta( get_the_ID(), '_zdt-byebyebye-line', true );
?>
	<p>
		<label for="byeline">
			<?php _e( 'Bye Bye Bye Line', 'zdt_byebyebye_lines' ); ?>:&nbsp;
		</label>
		<input type="text" class="widefat" name="byeline" value="<?php echo esc_attr( $byebyebye_line ); ?>" />
		<em>
			<?php _e( 'HTML is not allowed', 'zdt_byebyebye_lines' ); ?>
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
function zdt_save_meta_box( $post_id, $post ) {
	// Do not save during autosave routines
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;

	// If value is not available, exit function
	if ( ! isset( $_POST['zdt_byeline'] ) ) {
		return;
	}

	// Verify permissions before saving
	if ( isset( $_POST[ 'post_type' ] ) && 'page' === $_POST[ 'post_type' ] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	// Check the nonce to secure against CSRF
	if ( isset( $_POST[ 'zdt-byebyebye-line' ] ) && wp_verify_nonce( $_POST[ 'zdt-byebyebye-line' ], 'save' ) ) {
		if ( is_numeric( $_POST['zdt_byeline'] ) ) {
			update_post_meta( $post_id, '_zdt-byebyebye-line', sanitize_text_field( $_POST['zdt_byeline'] ) );
		} else {
			delete_post_meta( $post_id, '_zdt-byebyebye-line' );
		}
	}
}

add_action( 'save_post', 'zdt_save_meta_box', 10, 2 );

/**
 * Append the Bye Bye Bye Line to the content.
 *
 * @param  string    $content    The original content.
 * @return string                The altered content.
 */
function zdt_print_byebyebye_line( $content ) {
	$byebyebye_line = get_post_meta( get_the_ID(), '_zdt-byebyebye-line', true );
	return $content . esc_html( $byebyebye_line );
}

add_filter( 'the_content', 'zdt_print_byebyebye_line' );