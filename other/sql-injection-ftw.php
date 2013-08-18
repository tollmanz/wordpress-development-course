<?php

function sql_injection_ftw() {
	global $wpdb;
	$id = absint( $_GET['id'] );

	$sql = "SELECT * FROM wp_posts WHERE wp_posts.ID = %d;";
	$sql = $wpdb->prepare( $sql, $id );
	$results = $wpdb->query( $sql );

	foreach ( $results as $key => $result ) {
		echo esc_html( $result->post_title ) . '<br />';
	}
}

add_action( 'wp_footer', 'sql_injection_ftw' );