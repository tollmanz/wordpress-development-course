## Plugin Extensibility

* Plugins can be written to allow other plugins to extend them
* Add hooks and filters to your plugins to allow for them to be changed

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

	// The default temperature
	$temp = '';

	if ( false != get_post_meta( $post->ID, 'zdt-temperature', true ) ) {
		$temp = get_post_meta( $post->ID, 'zdt-temperature', true );
	} else {
		// Get the current temperature
		$temp_request = wp_remote_get( 'http://api.openweathermap.org/data/2.5/weather?q=portland,or' );

		// Only proceed if the request is successful
		if ( 200 === wp_remote_retrieve_response_code( $temp_request ) ) {
			$temp_data = wp_remote_retrieve_body( $temp_request );
			$temp_json = json_decode( $temp_data );
			$temp      = zdt_kelvin_to_f( $temp_json->main->temp );
			$temp      = apply_filters( 'zdt_temp', $temp );
		}
	}
?>
	<p>
		<label for="zdt-temperature">
			Temperature (&deg;<?php echo esc_html( zdt_temperature_type() ); ?>):&nbsp;
		</label>
		<input type="text" name="zdt-temperature" value="<?php echo floatval( $temp ); ?>"/>
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
	if ( isset( $_POST[ 'zdt_plugin_noncename' ] ) && wp_verify_nonce( $_POST[ 'zdt_plugin_noncename' ], plugins_url( __FILE__ ) ) ) {
		if ( is_numeric( $_POST[ 'zdt-temperature' ] ) ) {
			update_post_meta( $post_id, 'zdt-temperature', floatval( $_POST[ 'zdt-temperature' ] ) );
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
	$temp        = get_post_meta( get_the_ID(), 'zdt-temperature', true );
	$temp        = floatval( $temp );
	$symbol      = esc_html( zdt_temperature_type() );
	$new_content = '<p><em>It was ' . $temp . '&deg; ' . $symbol . ' when I published this post.</em></p>';
	return $content . $new_content;
}

add_filter( 'the_content', 'zdt_display_temperature', 40, 1 );

/**
 * Convert kelvin to celsius.
 *
 * @param  int      $value    The kelvin temp.
 * @return float              The fahrenheit temp.
 */
function zdt_kelvin_to_f( $value ) {
	$celcius    = $value - 273.15;
	$fahrenheit = ( ( $celcius * 9 ) / 5 ) + 32;
	return $fahrenheit;
}

/**
 * Get the temperature symbol.
 *
 * @return string    Temperature symbol.
 */
function zdt_temperature_type() {
	return apply_filters( 'zdt_temperature_type', 'F' );
}
```

In `functions.php`:

```php
<?php
/**
 * Convert temperature to celsius.
 *
 * @param  float    $temp    The temperature in Fahrenheit.
 * @return float             The temperature in Celsius.
 */
function zdt_temp_in_celsius( $temp ) {
	if ( empty( $temp ) ) {
		return $temp;
	}

	return ( $temp - 32 ) * ( 5/9 );
}

add_filter( 'zdt_temp', 'zdt_temp_in_celsius', 10, 1 );

/**
 * Change the temperature symbol to "C".
 *
 * @param  string    $symbol    The current symbol.
 * @return string               The new symbol.
 */
function zdt_temperature_type_filter( $symbol ) {
	return 'C';
}

add_filter( 'zdt_temperature_type', 'zdt_temperature_type_filter' );
```