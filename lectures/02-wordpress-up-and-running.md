## The 5 Minute Install

1. [Create a database](http://codex.wordpress.org/Installing_WordPress#Step_2:_Create_the_Database_and_a_User) for the installation.
	1. It's typically best to create a new database for each site, although you can add multiple sites to the same database.
	1. Note the name of the database, username and password.
1. Download the latest version of WordPress from http://wordpress.org/latest.zip.
1. Unzip the package and move the files to the webroot of your server.
1. Setup any server configs needed to access your site
1. Visit your site and walk through the instructions

In class demo using a Vagrant box:

1. Create the database:
	* `mysql -u root -p blank`
	* ```CREATE DATABASE `wdim393f`;```
	* ```GRANT ALL PRIVILEGES ON `wdim393f`.* TO 'wp'@'localhost' IDENTIFIED BY 'wp';```
1. Download files (or at least show how this would be done)
1. Move files to directory (`www/wdmin393f`)
1. Setup nginx config (add the following as `wdim393f.conf` in `/sites/`)
	```nginx
	server {
		listen       80;
		server_name  wdim393f.dev;
		root         /srv/www/wdim393f;
		include      /etc/nginx/nginx-wp-common.conf;
	}
	```
1. Restart nginx
1. Add `wdim393f.dev` to hosts file
1. Visit http://wdim393f.dev
1. Fill in the details

Refs/Credits:

* http://codex.wordpress.org/Installing_WordPress

## Touring the Admin

* Click through admin to see what is available in a vanilla install of WordPress.