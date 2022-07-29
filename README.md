# myDraft CMS | Blog | Shop PHP-System
Setup your own CMS, Blog, Shop with the newest PHP 8.x combatible version

# Upload the whole Content 
Open the domain or Localhost in the Browser and you should be asked for database credentials.
Navigate to yourdomain.com/install/install.php in a Browser of your Choice.

# Requirements for the Server
+ > PHP 7.3 up to PHP 8.1
+ a MySQL / MariaDB > 10.5 Database
+ Credentials for the Database

# The Module System / Plugin System
There is a Folder called "module" inside of this Folder are all Modules that could be put in the Frontend.
Every folder inside of "modules" represent a Plugin with a fixed structure with own Frontend Editing Options. 

Example Plugin most commonly used:
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

# Example SQL for Copy & Paste

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

# A fixed PHP Function is in every Module needed
Inside the Folder "module"\index.php 

<?php 
# >> TEXTHTML Modul 
function LoadModul_texthtml($config) {
..
?>

the LoadModul_%module_name% like the shown function body. $config a an Array with all Custom Option that are inside of the Plugin.

# The Page Compositing with Modules
You can choose from different Build-In Pagelayout. 
+ col-2-right
+ col-2-left
+ col-3

# The "Templating System" is using Smarty PHP Template Engine
Build and Deploy different Page Layouts with the Folder "templates" if the Page Cache is used you are finding the Cache Data under "cache".

# Multidomain PHP CMS System
use heidisql for connecting and viewing the structure of the database tables.
One table is called "domains". There you as many subdomains and domains that shall run with the same Database.

# Multistore portal with modules with one cental marketplace for all competitor
You can Setup a Software as a Service (SaaS) if you want.
All tables are beginning with "shop_".

# Setup a news portal with Twitter autoposting which is consisting of multiple categorized.
You can Setup a news aggregator.
All tables are beginning with "rss_". 
You need to Setup the PHP Cronjob File cron.php to run periodically.