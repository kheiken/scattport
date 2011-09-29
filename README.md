ScattPort - Webinterface for light scattering simulators
========================================================

Setup
-----

To install a local instance of this webinterface, the following software is required:

* Webserver (mod_rewrite is a plus, but not required. see application/config/config.php)
* PHP 5
* MySQL

Simply import mysql_scheme.sql into your database, set the connection data inside application/config/database.php.

Copy config/database.sample.php to config/database.php and adjust the values.
Same thing goes for config/config.sample.php and .htaccess.sample (if you want mod_rewrite-support).


License
-------

This application is published under the MIT License. See COPYING.

This application uses the following third-party software:

* CodeIgniter by Ellislab, http://codeigniter.com
* jQuery, http://jquery.com/
* Fugue Icons, http://p.yusukekamiyamane.com/
