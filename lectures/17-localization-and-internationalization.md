## Localization and Internationalization

* **Localzation (l10n)**: the process of translating a product into different languages or adapting a product for a specific country or region ([Wikipedia](http://en.wikipedia.org/wiki/Localization))
* **Internationalization (i18n)**: the process of designing a software application so that it can be adapted to various languages and regions without engineering changes ([Wikipedia](http://en.wikipedia.org/wiki/Internationalization_and_localization))

## Internationalizing WordPress Code

1. Mark strings as translatable
	* WordPress provides a number of functions that build off of gettext for this purpose:
		* `_e`: translate and echo a string
		* `__`: translate and return a string
		* `_n`: translate a singular and plural version of a string
	* Second argument of each of the functions is a text domain
		* The text domain indicates that the translation belongs to a certain set of translations
		* Helps ensure that if a word is translated in two plugins, only the one from your plugin is used
	* Load the translation files
		```php
		function myplugin_init() {
			$plugin_dir = trailingslashit( basename( dirname( __FILE__ ) ) );
			load_plugin_textdomain( 'my-plugin', false, $plugin_dir . 'languages/' );
		}
		add_action( 'plugins_loaded', 'myplugin_init' );
		```
1. Create a translation template file
	* Use poEdit
	* Use xgettext
	* Use online tools (e.g., the plugin repo will do this for you)

## Localizing

1. Translate each string in the .pot file
1. Compile into a .mo file
1. Add .mo file to appropriate directory