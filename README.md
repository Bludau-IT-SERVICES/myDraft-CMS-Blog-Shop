# myDraft CMS | Blog | Shop PHP-System
Setup your own CMS, Blog, Shop, Portal with the newest PHP 8.x combatible version

# Upload the whole Content 
Open the domain or Localhost in the Browser and you should be asked for database credentials.
Navigate to yourdomain.com/install/install.php in a Browser of your Choice.

# Requirements for the Server
+ > PHP 7.3 up to PHP 8.1
+ a MySQL / MariaDB > 10.5 Database
+ User login credentials for the Database

# myDraft installer config file
You find the default config file in the directory "include"  with the file "inc_config-data.php" there are many Constants.

Email Setup
-----------

	# API KOMPONENTE NEWS 
	define("CORE_MAIL_FROM_API",     "kontakt@bludau.io");
	define("CORE_MAIL_FROM_API_NAME",     "myDraft API Support");
	define("CORE_MAIL_SEND_API_BCC",     "kontakt@bludau.io");
	define("CORE_MAIL_SEND_API_BCC_NAME",     "myDraft API Support");
	define("CORE_MAIL_FROM_API_REGISTER",     "kontakt@bludau.io");
	define("CORE_MAIL_FROM_API_REGISTER_NAME",     "myDraft Anmeldebestätigung");

Twitter API Setup
-----------------

	define("twitter_oauth_access_token","");
	define("twitter_oauth_access_token_secret","");
	define("twitter_consumer_key","");
	define("twitter_consumer_secret","");

Cron Setup API KEY
------------------
Please change the Defaul "CORE_CRON_API_KEY" in the "inc_config-data.php".

	define("CORE_CRON_API_KEY",     "SNaA8BMi9KlJlH3i5SEx");

The myDraft installer generated file is called "installed.php".

	<?php
		define("HOST","localhost");
		define("USER","sql_username"); 
		define("PASS","sql_passwort");
		define("DB","sql_database");                    
		define("SECRECT_KEY","application_secret"); 
		define("SECRECT_IV","application_init_vektor"); 
	?>

Without secret KEY and secret IV no Login and encryption of user data will work.

# myDraft installer SQL file
"/install/myDraft Datenbank Schema.sql" there is the defaul structure of the SQL default strukture.
SQL is compatible and tested with mariaDB 10.6.x. You could import the SQL file with mysqldump for example with HeidiSQL.

# The default myDraft module structure / plugin system
There is a Folder called "module" inside of this Folder are all Modules that could be put in the Frontend.
Every folder inside of "modules" represent a Plugin with a fixed structure with own Frontend Editing Options. 

Example myDraft plugin file and directory structure
-------------------------------------------
"texthtml"\admin
"texthtml"\admin\form\%module_name%-settings.php (This are the settings and Add and Update HTML Input Fields that are representing the Options and Content)
"texthtml"\index.php

# SQL Setup a myDraft Module
Every PHP Module has a SQL Table with "modul_%module_name%" and should containt the following default fields:

+ id
+ title_de
+ content_de
+ lastchange
+ last_usr
+ design
+ menue_id
+ created_at
+ updated_at

# Example Module Default Init SQL Table (for copy & paste)
Module name "texthtml".

	CREATE TABLE `modul_texthtml` (
		`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
		`title_de` VARCHAR(255) NOT NULL DEFAULT '0' COLLATE 'utf8mb4_general_ci',
		`content_de` LONGTEXT NOT NULL COLLATE 'utf8mb4_general_ci',
		`lastchange` TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
		`last_usr` INT(11) UNSIGNED NOT NULL,
		`design` VARCHAR(50) NOT NULL DEFAULT 'box' COLLATE 'utf8mb4_general_ci',
		`menue_id` INT(10) UNSIGNED NULL DEFAULT NULL,
		`created_at` DATETIME NULL DEFAULT NULL,
		`updated_at` TIMESTAMP NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
		PRIMARY KEY (`id`) USING BTREE
	)
	COLLATE='utf8mb4_general_ci'
	ENGINE=InnoDB
	AUTO_INCREMENT=1;

# A fixed PHP Function is in every myDraft Module needed
Inside the Folder "module"\index.php 

<?php 
# >> TEXTHTML Modul 
function LoadModul_texthtml($config) {
..
?>

the LoadModul_%module_name% like the shown function body. $config a an Array with all Custom Option that are inside of the Plugin.

# Page compositing with includeable modules everywhere
You can choose from different Build-In Pagelayout. 
+ col-2-right
+ col-2-left
+ col-3

# The "Templating System" is using Smarty PHP Template Engine
Build and Deploy different Page Layouts with the Folder "/templates" if the Page Cache is used you are finding the cache data under "/cache".

# Multidomain PHP CMS System
use heidisql for connecting and viewing the structure of the database tables.
One table is called "domains". There you as many subdomains and domains that shall run with the same database.

# Multistore portal with modules with one cental marketplace for all competitor
You can Setup a Software as a Service (SaaS) if you want.
All tables are beginning with "shop_".

# Setup a news portal with Twitter autoposting which is consisting of multiple categorized.
You can Setup a news aggregator.
All tables are beginning with "rss_". 
You need to Setup the PHP Cronjob File cron.php to run periodically.