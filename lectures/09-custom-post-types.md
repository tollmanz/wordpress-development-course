## Custom Post Types

* A post-like object that holds a specific type of content. 
* Uses the same API as WordPress posts
* WordPress "posts" and "pages" are custom post types. 
* Allows you to have content that is treated differently than the default posts.
* Show The Slant as an example

## register_post_type()

* There are many arguments and some can get a little complex. 
* For this class, the most important are:
	* `labels`: defines the different associated labels for the CPT (see this [gist](https://gist.github.com/wycks/2377383) for the full list).
	* `taxonomies`: the taxonomies to associate with the post type
	* `menu_position`: a number that represents where the menu item for the CPT will be displayed. The possible values, taken from [the Codex](http://codex.wordpress.org/Function_Reference/register_post_type), are below:
		* 5 - below Posts
		* 10 - below Media
		* 15 - below Links
		* 20 - below Pages
		* 25 - below comments
		* 60 - below first separator
		* 65 - below Plugins
		* 70 - below Users
		* 75 - below Tools
		* 80 - below Settings
		* 100 - below second separator
	* `public`: whether or not the post type is exposed on the front end of the site (e.g., via search, query variables, etc.)
	* `has_archive`: whether or not to enable an archive by default. Setting the value to "true" will provide an archive using the CPT's key as the slug. Sending a string for this value uses the value as the slug.
	* `rewrite`: allows for configuring different URL structures for the CPT
	* `supports`: defines which of the default WP post features are available to this CPT. Options (via [the Codex](http://codex.wordpress.org/Function_Reference/register_post_type)):
		* title
		* editor
		* author
		* thumbnail
		* excerpt
		* trackbacks
		* custom-fields
		* comments
		* revisions
		* page-attributes
		* post-formats

## Why CPTs?

* A consistent, known API to work with
* Lots of features will very little effort
* Built in security and performance
* Compatibility with other plugins/themes

## Caution

* A CPTs interface is only available if the plugin or theme is currently active. The data is still there, but there is no way to access it. Must consider this when using CPTs.