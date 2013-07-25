## What are Themes?

* Themes change the appearance of a WordPress website
* Can also add functionality to a site, but should do so with caution

## Installing a WordPress Theme

* Install from the WordPress.org repo
* Upload a zip
* Add files to wp-content/themes manually
* Demo the methods
* Demo the theme repo

## The Anatomy of a Theme

* Minimum requirement is a **style.css** file with a header that includes a theme name and an **index.php** file
	* **style.css** header can include:
		* Theme Name
		* Theme URI
		* Author
		* Author URI
		* Description
		* Version
		* License
		* License URI
		* Tags
		* Text Domain
* **functions.php** is a special file that is executed early in the WordPress page load and allows for additional, non template code to be executed by the theme
* The rest of the files are template files
	* The template file loaded depends on the [template hierarchy](http://codex.wordpress.org/Template_Hierarchy)
	![Template Hierarchy](https://raw.github.com/tollmanz/wordpress-development-course/master/lectures/assets/template-hierarchy.png)
	* Template loaded is dictated by the main query
* **header.php** defines the top, or "header", section of the HTML document
* **footer.php** defines the bottom, or "footer", section of the HTML document
* **sidebar.php** typically holds a sidebar column, which may have widget areas
* **index.php** is the default file that is loaded if no other files are matched
* Template files are primarily composed of HTML and PHP that generates dynamic post data

## Template Hierarchy

* Determines which file will be loaded to generate a view
* **wp-includes/template-loader.php** determines which file is loaded from the theme:
```php
if ( defined('WP_USE_THEMES') && WP_USE_THEMES ) :
	$template = false;
	if     ( is_404()            && $template = get_404_template()            ) :
	elseif ( is_search()         && $template = get_search_template()         ) :
	elseif ( is_tax()            && $template = get_taxonomy_template()       ) :
	elseif ( is_front_page()     && $template = get_front_page_template()     ) :
	elseif ( is_home()           && $template = get_home_template()           ) :
	elseif ( is_attachment()     && $template = get_attachment_template()     ) :
		remove_filter('the_content', 'prepend_attachment');
	elseif ( is_single()         && $template = get_single_template()         ) :
	elseif ( is_page()           && $template = get_page_template()           ) :
	elseif ( is_category()       && $template = get_category_template()       ) :
	elseif ( is_tag()            && $template = get_tag_template()            ) :
	elseif ( is_author()         && $template = get_author_template()         ) :
	elseif ( is_date()           && $template = get_date_template()           ) :
	elseif ( is_archive()        && $template = get_archive_template()        ) :
	elseif ( is_comments_popup() && $template = get_comments_popup_template() ) :
	elseif ( is_paged()          && $template = get_paged_template()          ) :
	else :
		$template = get_index_template();
	endif;
	if ( $template = apply_filters( 'template_include', $template ) )
		include( $template );
	return;
endif;
```

## A Look at Twenty Thirteen

* If no templates are matched, **index.php** is loaded
* **index.php** is a good example of a theme template

```php
<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme and one of the
 * two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php twentythirteen_paging_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div><!-- #content -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
```

* `get_header()`, `get_sidebar()`, and `get_footer()` include **header.php**, **sidebar.php**, and **footer.php**, respectively.
* **header.php**:

```php
<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header" role="banner">
			<a class="home-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
				<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</a>

			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<h3 class="menu-toggle"><?php _e( 'Menu', 'twentythirteen' ); ?></h3>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentythirteen' ); ?>"><?php _e( 'Skip to content', 'twentythirteen' ); ?></a>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
					<?php get_search_form(); ?>
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->
		</header><!-- #masthead -->

		<div id="main" class="site-main">
```

* **footer.php**:
```php
<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>

		</div><!-- #main -->
		<footer id="colophon" class="site-footer" role="contentinfo">
			<?php get_sidebar( 'main' ); ?>

			<div class="site-info">
				<?php do_action( 'twentythirteen_credits' ); ?>
				<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'twentythirteen' ) ); ?>" title="<?php esc_attr_e( 'Semantic Personal Publishing Platform', 'twentythirteen' ); ?>"><?php printf( __( 'Proudly powered by %s', 'twentythirteen' ), 'WordPress' ); ?></a>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->

	<?php wp_footer(); ?>
</body>
</html>
```

* It is extremely important that every theme calls two functions
	1. `wp_head()` needs to be called immediately before the closing `</head>` tag
	1. `wp_footer()` needs to be called immediately before the closing `</body>` tag

## The Loop

* The content of the main query for the page load is utilized in the loop
* The loop starts with:
```php
<?php while ( have_posts() ) : the_post(); ?>
```
* Within the loop, [template tags](http://codex.wordpress.org/Template_Tags) are used to print content to the screen (e.g., `the_title()`, `the_author()`)
* Example loop:
```php
<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php if ( has_post_thumbnail() && ! post_password_required() ) : ?>
			<div class="entry-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php endif; ?>

			<h1 class="entry-title"><?php the_title(); ?></h1>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentythirteen' ) . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'twentythirteen' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->

	<?php comments_template(); ?>
<?php endwhile; ?>
```