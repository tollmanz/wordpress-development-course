## Best Practices

* [Wikipedia](http://en.wikipedia.org/wiki/Best_practice) - method or technique that has consistently shown results superior to those achieved with other means, and that is used as a benchmark.
* Recommendations based on years of experience from other WordPress developers

### Recommendations

* Use WordPress' APIs where possible, including using WordPress' functions first before building one of your own
	* Not using known APIs makes it difficult to understand your code
	* It's reasonable to assume that WP's APIs are more stable than custom ones
	* Built in support if using WP's APIs
* Use the enqueue functions
* Prefix *everything* to avoid collisions
	* Functions of the same name will cause a fatal error
	* Variables of the same name will lead to unexpected behavior
	* Database keys of the same name will lead to unexpected results
* Store the proper data in the proper tables using the proper API
* Use the settings API
* Test your plugin
	* ...against different WP versions
	* ...against different data sets
	* ...against difference environments
	* The more testing you do, the better the experience will be for the user

## Code Standards

* A set of rules or guidelines used when writing code (from [Wikipedia](http://en.wikipedia.org/wiki/Programming_style)).
* WordPress has a code style guide for [PHP](http://make.wordpress.org/core/handbook/coding-standards/php/), [HTML](http://make.wordpress.org/core/handbook/coding-standards/html/), and [CSS](http://make.wordpress.org/core/handbook/coding-standards/css/).
* Leads to more readable code
* Reduces the disagreement about non-consequential things
* Generally improve code quality

## Resources

* [Objective Best Practices for Plugin Development?](http://wordpress.stackexchange.com/questions/715/objective-best-practices-for-plugin-development) - WordPress Answers - multiple authors