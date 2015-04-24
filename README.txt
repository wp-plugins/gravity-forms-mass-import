=== Plugin Name ===

Contributors: Aryan Duntley, dunar21

Plugin Name: Gravity Forms Mass Import

Donate link: http://worldpressrevolution.com/wpr_myplugins/gravity-forms-mass-import/

Plugin URI: http://wordpress.org/extend/plugins/gravity-forms-mass-import/

Author URI: http://worldpressrevolution.com/

Tags: gravity forms, gravity forms import, import, csv import, gravity forms entries, gravity forms entries import, entries import, mass import, wordpress csv import, csv, csv import, import csv gravity forms 

Requires at least: 3.0.1

Tested up to: 3.9.1

Stable tag: 1.5.1

License: GPLv2 or later

License URI: http://www.gnu.org/licenses/gpl-2.0.html



Allows for mass import of gravity forms entries from a CSV file. 



== Description ==

NOTE:  THIS PLUGIN DOES NOT AND WILL NOT SUPPORT GF 1.9+. For information on possible alternatives please visit the plugin site: [Aryan Duntley](http://worldpressrevolution.com/wpr_myplugins/gravity-forms-mass-import/ "Aryan Duntley Web Dev")



This plugin allows Gravity Forms users to import entries from a CSV file



A new item will appear in you gravity forms side menu called "Import Entries".  There you will be provided with a selection box to pick which form you would like to import into.  Once selected, you will be provided with the "Labels" of your form that will be needed as the headers of your CSV file.  Make sure that you use those exactly.  If you have erroneous or unavailable headers in your CSV file, an error will be generated.  Please note, that this plugin is not designed to work with the gravity forms post fields or pricing/product fields.

A new feature allows users to specify the date a record (or entry) is listed as having been created.  To do this, you must create an extra field in your form editor titled "actualPostDate".  You should make this field a single line text field.  Make sure that "actualPostDate" is included in your headers as will be indicated in the required headers list when you select your form before csv entry.  Finally, the format for entry is the MYSQL date time format (2005-08-05 10:41:13).

This plugin was created for users who wish to use Gravity Forms as an online database/data entry option and wish to import data from a previous system.





Plugin site: [Aryan Duntley](http://worldpressrevolution.com/wpr_myplugins/gravity-forms-mass-import/ "Aryan Duntley Web Dev")



Donations welcome.  If you find this plugin useful and would maybe like to request more features or hope for future updates and optimizations, please help me eat!  I find it takes a lot of pizza to code, something about thinking and calories...



== Installation ==



1. Upload `gravity-forms-mass-import` folder to the `/wp-content/plugins/` directory

2. Activate the plugin through the 'Plugins' menu in WordPress





== Frequently Asked Questions ==



Since this is the first release, there are no frequently asked questions.  Ask away and this page could be filled up!  Donations motivate responses...



== Screenshots ==



1. Simple enough.





== Changelog ==
= 1.2=

*Will now upload large text into proper database table

*Fixed issue with a query that was querying entire table instead of just a single value

*added support for setting date of creation for an entry

= 1.1 =

*Had a user who was having difficulties with the Ajax call. Using wp_ajax now.  Hope this helps.

= 1.0.5 =

*Changed the automated delimiter search.  All delimiters must be commas and all fields must be encased in double quotes.

= 1.0.1 =

* Added necessary instructions

* Some minor code optimizations



= 1.0.0 =

* Initial version.



