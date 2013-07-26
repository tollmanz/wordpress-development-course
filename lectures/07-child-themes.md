## What is a Child Theme?

* A child theme is a theme that inherits structure, style, and functionality from a parent theme.
* The child theme them can override aspects of the parent theme if the parent theme is coded properly.

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

* A child theme's **functions.php** file is loaded prior to a parent theme's **functions.php** file loading.
	* The child theme can take precedence over a parent theme
* The child theme's **style.css** loads after the parent theme's **style.css** as the parent theme is `@import`ed into the child theme's **style.css**.
* A child theme's template files are loaded before a parent theme's template files.
```php
function locate_template($template_names, $load = false, $require_once = true ) {
	$located = '';
	foreach ( (array) $template_names as $template_name ) {
		if ( !$template_name )
			continue;
		if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
			$located = STYLESHEETPATH . '/' . $template_name;
			break;
		} else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
			$located = TEMPLATEPATH . '/' . $template_name;
			break;
		}
	}

	if ( $load && '' != $located )
		load_template( $located, $require_once );

	return $located;
}
```
* Child theme's are most commonly used to tweak a parent theme while maintaining the parent theme's ability to receive future updates

## Preparing a Parent for a Child

* A parent theme should allow PHP functions to be overriden by wrapping functions in `function_exists` calls
	* Allows a child theme to define a function of the same name and load that function insted of the parent's function
* Do not be overly specific with CSS selectors so that the cascade is overridable by the child theme

## Overriding a Parent Theme

* Define a template in the child theme
* Override a selector in the child theme's CSS
* Define a function in functions.php