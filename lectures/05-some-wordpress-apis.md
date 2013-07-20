## Adding JS and CSS

* WordPress uses an "enqueues" system for adding JS and CSS to a page. 
* The enqueue system allows a developer to declare dependencies and use JS/CSS that is included with WordPress.
* Not using the enqueues system can cause plugin conflicts that can break a site

### wp_enqueue_script()

Arguments:

* `handle`: a unique id for the script
* `src`: URL of the script
* `deps`: scripts that this script depends on
* `ver`: the version of the script
* `in_footer`: whether or not to load the script in the footer

Example:

```php
wp_enqueue_script(
	'my-responsiveslides',
	plugins_url( '/responsiveslides.js', __FILE__ ),
	array( 'jquery' ),
	'1.54',
	true
);
```

Results in:

```html
...
<head>
	...
	<script type='text/javascript' src='http://wdim393f.dev/wp-includes/js/jquery/jquery.js?ver=1.8.3'></script>
	<script type='text/javascript' src='http://wdim393f.dev/wp-content/plugins/responsiveslides/responsiveslides.js?ver=1.54'></script>
	...
</head>
...
```

### wp_enqueue_style()

Arguments:

* `handle`: a unique id for the script
* `src`: URL of the script
* `deps`: scripts that this script depends on
* `ver`: the version of the script
* `media`: the link tag media element

Example:

```php
wp_enqueue_style(
	'my-responsiveslides',
	plugins_url( '/responsiveslides.css', __FILE__ ),
	array(),
	'1.54',
	'all'
);
```

Results in:

```html
...
<head>
	...
	<link rel='stylesheet' id='my-responsiveslides-css'  href='http://wdim393f.dev/wp-content/plugins/responsiveslides/responsiveslides.css?ver=1.54' type='text/css' media='all' />
	...
</head>
...
```
