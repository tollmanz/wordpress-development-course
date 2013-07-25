## What is a Child Theme?

## How is a Child Theme loaded?

```php
// Load the functions for the active theme, for both parent and child theme if applicable.
if ( ! defined( 'WP_INSTALLING' ) || 'wp-activate.php' === $pagenow ) {
	if ( TEMPLATEPATH !== STYLESHEETPATH && file_exists( STYLESHEETPATH . '/functions.php' ) )
		include( STYLESHEETPATH . '/functions.php' );
	if ( file_exists( TEMPLATEPATH . '/functions.php' ) )
		include( TEMPLATEPATH . '/functions.php' );
}
```

## Preparing a Parent for a Child

## Overriding a Parent Theme