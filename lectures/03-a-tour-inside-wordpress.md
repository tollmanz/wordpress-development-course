## Database

* As of WordPress 3.5.2, there are 11 default tables created when WordPress is installed:
	* wp_commentmeta: metadata associated with comments
	* wp_comments: comments for posts
	* wp_links: holds all information related to links
	* wp_options: hold key/value pairings of options
	* wp_postmeta: metadata associated with posts
	* wp_posts: holds basic post information
	* wp_terms: holds basic term information
	* wp_term_relationships: associates terms with posts and links
	* wp_term_taxonomy: associates a term with a taxonomy
	* wp_usermeta: metadata associated with users
	* wp_users: holds basic user information
* There are repeated motifs in the database (primary object table, metadata table); however, these motifs are not reliable.

Refs/Credits

* http://codex.wordpress.org/Database_Description

## Files

* Root files
* /wp-admin
	* At the root of this folder are pages/views
		* These views will include extra, necessary functionality as needed
	* /includes
		* Admin only functions (e.g., media manager)
	* /network
		* Views for the network admin panel
	* Generally speaking, if functionality is in the admin only, it will be defined in /wp-admin.
* /wp-includes
	* Functions
		* Numerous API functions are defined in these files (e.g., post manipulation functions)
	* Classes
		* Handy classes are defined in these files (e.g., HTTP_API)	
		* All start with "class-"
	* Template Tags
		* Convenience functions to print data to the screen
		* All conclude with "-template.php"
	* /js
		* WordPress ships with a number of popular 3rd party scripts (e.g., jQuery, jQuery UI, Backbone)
		* If WordPress core uses it, it is available to plugin and theme devs
* /wp-content
	* Holds all user generated content:
	* /plugins
	* /themes
	* /uploads

Refs/Credits

* http://www.youtube.com/watch?v=TPXAB2f0jwk
* http://thinkoomph.com/slides/wcnyc-ethitter-moving-beyond-codex
* http://core.trac.wordpress.org/browser