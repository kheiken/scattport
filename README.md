ScattPort - Webinterface for light scattering simulators
========================================================

Setup
-----

First, get a clone of the current release:

    $ git clone https://github.com/krstn/scattport.git
    $ cd scattport

Now, create the database schema. There is a sample .sql file included for use with MySQL:

    $ mysqladmin -uroot create $DATABASE
    $ mysql -uroot $DATABASE < mysql_schema.sql

Replace $DATABASE with whatever suits your needs.

There are two configurations files included that you need to modify:

* `config.php` - changes required in line 17 and 29
* `database.php` - lines 44 to 48 need to be adjusted to suit your environment

Copy the samples and edit them accordingly:

    $ cp application/config/config.sample.php application/config/config.php
    $ cp application/config/database.sample.php application/config/database.php
    $ vim application/config/config.php
    $ vim application/config/database.php

It is important to copy these files rather than moving them. If you move them, updates (`git pull`) would get nasty.

The file `application/config/scattport.php` contains some settings for the different simulators you want to integrate. The file is fully documented, so you should be fine.


License
-------

This application is published under the MIT License. See COPYING.

This application uses the following third-party software:

* CodeIgniter by Ellislab, http://codeigniter.com
* jQuery, http://jquery.com/
* jsc3d, http://code.google.com/p/jsc3d/
* Fugue Icons, http://p.yusukekamiyamane.com/
