## Installing plugins

* Install from the WordPress.org repo
* Upload a zip
* Add files to wp-content/plugins manually
* Demo the methods
* Demo the plugin repo

## The Anatomy of a Plugin

* Only requirement is a single file
* To host on WordPress.org, you also must have a readme.txt file, which is used to populate information in the WordPress.org plugin repo

### The Plugin Header

* The plugin will only be recognized by WordPress if one of the plugin files has a plugin header.
* The plugin header is a PHP style comment with specific fields for plugin information.
* At a minimum, you must provide a plugin name:

```php
/*
Plugin Name: Whatever P Dangit
*/
```

* Supported fields are:
	* Plugin Name
	* Plugin URI
	* Version
	* Description
	* Author
	* Author URI
	* Text Domain : Loads translation files for the header text
	* Domain Path : Path to find the files
	* Network : Whether or not to install for the network
	* License

```php
/*
Plugin Name: Whatever P Dangit
Plugin URI: http://whatever-p-plugin.com
Version: 1.0
Description: Frees your WordPress installation from capital P's.
Author: Zack Tollman
Author URI: http://tollmanz.com
License: GNU General Public License v2 or later
*/
```

* The file containing the header will automatically be executed. 
* You can optionally include other PHP files, but they must be manually called.

## Writing Your First Plugin

* Problem: By default, WordPress always corrects the spelling of WordPress to have a capital "P". I want to remove this feature.
* How is this implemented in the first place?
	* I know there is a function called "capital_P_dangit()"
	* How is the function used?
		* Search in the files for "capital_P_dangit"
		```php
		// Format WordPress
		foreach ( array( 'the_content', 'the_title' ) as $filter )
			add_filter( $filter, 'capital_P_dangit', 11 );
		add_filter( 'comment_text', 'capital_P_dangit', 31 );
		```

		* Written differently:
		```php
		// Format WordPress
		add_filter( 'the_content', 'capital_P_dangit', 11 );
		add_filter( 'the_title', 'capital_P_dangit', 11 );
		add_filter( 'comment_text', 'capital_P_dangit', 31 );
		```
* How can we "undo" this effect?
	* We just need to remove the filters
	* WordPress provides a "remove_filter" function that can be utilized
	```php
	remove_filter( 'the_content', 'capital_P_dangit', 11 );
	remove_filter( 'the_title', 'capital_P_dangit', 11 );
	remove_filter( 'comment_text', 'capital_P_dangit', 31 );
	```
	* Filters are only removed if they have already been added.
	* We need to make sure that the code is executed *after* the filter is added.
	* Run the code on an action hooked to init
	```php
	function zdt_remove_capital_P_dangit() {
		remove_filter( 'the_content',  'capital_P_dangit', 11 );
		remove_filter( 'the_title',    'capital_P_dangit', 11 );
		remove_filter( 'comment_text', 'capital_P_dangit', 31 );
	}

	add_action( 'init', 'zdt_remove_capital_P_dangit' );
	```

## When to Execute Code

* Almost never run code directly in the file itself
* Hook the code that will eventually run on an action or a filter
* Earliest a plugin (non-MU) can run code `plugins_loaded`
* Earliest a theme can run code `after_setup_theme`
* In most cases, the earliest you should run code is on `init`
* `init` runs after all of the WordPress API is loaded, but before the main query is executed
* `template_redirect` is the first hook that allows you to reliably use conditional template tags (e.g., is_single())
* [Make sense of WP core load](http://www.rarst.net/script/wordpress-core-load/) (created by Rarst: http://www.rarst.net/script/wordpress-core-load/)

![Make sense of WordPress Core Load - By Rarst (http://www.rarst.net/script/wordpress-core-load/)](https://raw.github.com/tollmanz/wordpress-development-course/master/lectures/assets/wordpress_core_load.png "Make sense of WordPress Core Load - By Rarst (http://www.rarst.net/script/wordpress-core-load/)")

## Finding the Right Hook

* [Codex Action Reference](http://codex.wordpress.org/Plugin_API/Action_Reference) highlights important hooks to be aware of
* [Filters of The Day](http://fotd.werdswords.com/) is a new project that highlights a few filters per day
* Reading core WordPress
* Experience