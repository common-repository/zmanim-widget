=== Zmanim Widget ===
Contributors: Misha Beshkin
Tags: sabbath, shabbat, shabat, jewish, judaism, zmanim, holidays, widget
Requires at least: 2.7
Tested up from : 3.5
Stable tag: 1.14.3

Displays Jewish calendar information in a widget.

== Description ==

Displays Jewish calendar information in a widget.

== Installation ==

1. Upload the folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place Widget in proper place in widgets' configuration page.

== Frequently Asked Questions ==

= How to set my location? =
Enter Lattitude, Longitude and Offset to proper boxes in Widget Setup page. 

== Changelog ==
= 1.14.3 =
- fixed warning message about problem with widget
= 1.14 =
- updated the plugin to comply with Wordpress.org rules
- Map widget is hidden till proper upgrade to Google API V3.
= 1.13.1 =
- modified mechanism for detecting DST time in Israel
= 1.13 =
- added mechanism for detection of Israel Day Saving Time.
= 1.12.2 =
- small wording fix in admin part
- added paypal button to support further development by KosherDev.
= 1.12 =
- added new functionality. Now it is possible to display Zmanim for a week forward
= 1.11.1 =
- fixed Plag haMincha time. It was 1 proportional hour later, than it should be.
= 1.11 =
- added Tzet haKochavim to zmanim. Value depends on Shabat end setting.
= 1.10 =
- add possibility to hide zmanim
= 1.9.2 = 
- fixed wrong time for Shabbat candle lighting
= 1.9.1 =
- removed debugging message
= 1.9 =
- added selector for configuration of the end of Shabbat (8.5 degrees below horizon or 45 minutes after sunset).
= 1.8.7 =
- administrative console javascript was moved from main page to be executed only in admin part. (special thanks to KosherJava.com) 
= 1.8.6 =
- javascript code to be added only in case zmanim map widget is initialized. (special thanks to KosherJava.com)
= 1.8.5 =
- Fixed incorrect display of Haftara info
= 1.8.4 =
- changed Toggle to Show/Hide
- added possibility to show/hide by one click
- improved localization
= 1.8.3 =
- fixed incompatibility with some themes
- fixed unchecking of hide checkbox in administration part
= 1.8.1 =
- localization to Russian
- bug fixes
= 1.8 =
- Added possiblity to hide/show sections in widget
= 1.7 =
- Notable changed in date/time display. Now date/time format can be set manually or used default of the Wordpress blog.
- Advanced configuration page got options to change date/time formatting manually.
- fixed hebrew wording
= 1.6.4 = 
- fixed minor issues with Notice messages
- changed Shabbat time to Rabbeinu Tam opinion (according to http://zemanim.net/docs/Gewirtz-Part_1.pdf)
- fixed issue with resetting settings every week
= 1.6.3 =
- fixed end of Sabbath time in case it occurs after 24:00
- added link to KosherDev.com
= 1.6.2. =
- removed warnings on incorrect inclusion
= 1.6.1 =
- hebrew translation fix for Shabbat
= 1.6 =
- Hebrew translation (partial) is added
= 1.5.1 =
- fixed problem with not registering zmanim.js with jQuery
- add verification, that Google maps api key is applied. In case not, map won't try to be displayed.
= 1.5 =
- Added small map wiget to display direction to Kotel
- Admin part got basic settings section
- Google API setting
= 1.0 =
- Advanced options administration section is added
- 4 ways of counting Alot haShachar
- 2 ways earliets Tallit
- 2 ways for praying time
- added Mincha Ktana time
= 0.10.4 =
- fixed not counting DST difference.
= 0.10.3 =
- fixed Upcoming date display feature. Now in case today is a holliday, then next holliday will be displayed.
= 0.10.2 =
- added Shkiah value
- fixed wrong Shabat date
= 0.10.1 =
- added missed file
= 0.10 =
- Omer counting is added
= 0.9 =
- DayLightSaving setting and functionality is added
- freeing from Hametz
= 0.8.1 =
- Added updated file
= 0.8 =
- Added Weekly Torah portion
thanks to HebCal.com for a csv file with all the order of torah reading (http://www.hebcal.com/help/sedra.html)
= 0.7.1 =
- Added missed file to trunk
= 0.7 =
- Added holidays section.
- corrected some localization issues.
= 0.6 = 
- Added localization methods and functions to the code
- created Russian translation
= 0.5.3 =
- Fixing problem with Warnings and notices due to some variable missing in functions.
= 0.5.2 =
- Fixed minutes displaying single number instead of double with preceding 0.
= 0.5.1 =
- Fixed problem with RSS feed and some other issues with wordpress 2.9
= 0.5 =
- minor fix to Chatzos in Widget display
- added Hebrew date display.
- fixed display of hours counting till the Shabbos beginning
= 0.4 =
- Added possilibity interactively select any location in World from autocomplete menu
= 0.3.1 =
- fixed layout of Widget (replaced H3 tags with nested UL)
= 0.3 =
- added Ashkenaz/Sephard selector to widget configuration.
- Ashkenaz/Sephard translations are added.
- Shabbos in one day replaced with 'tomorrow'
- fixed incorrect display of 'It will come in 6 days'
= 0.2.2 =
- change error reporting to UI to E_ERROR
= 0.2.1 =
- Adding missed file to repository
= 0.2 =
- Added zmanim calculations to Widget
- added credits to Cipora Greve and Young Israel of St. Louis
- add location name in config and display in widget
- fixed bug with not getting data from DB
= 0.1 =
- Create basic data display in Widget

== TODO ==
- Add Diaspora/Eretz setting in config
- add Postpone Shushan Purim in config
- add translation to Ashkenaz/Sephard of hollidays

== Credits ==
- Kosherjava.com for a great map with direction for Kotel
http://www.kosherjava.com/maps/zmanim.html
- Tichnut.de for a wonderful description of various types of Zmanim
http://www.tichnut.de/jewish/jewcalsdkdoc/times.html
- HebCal.com for a csv file with all the order of torah reading 
http://www.hebcal.com/help/sedra.html
- Cipora Greve for a wonderful explanation and examples of zmanim calculations and hollidays in php
http://www.ziporah-greve.net/prog/jewish-php.html
- Young Israel of St. Louis congregation for am exmplanation on zmanim
http://www.youngisrael-stl.org/articlereader.php?artname=Zmanim.html
- Autocomplete plugin for jQeury 
http://bassistance.de/jquery-plugins/jquery-plugin-autocomplete/
- GeoNames.org
http://www.geonames.org/export/geonames-search.html 
- KosherJava.com for a wonderful functions set to convert current date to Hebrew. At this time, I used functions from version 0.9.
http://www.kosherjava.com/wordpress/hebrew-date-plugin/

== Screenshots == 
<img src="http://kosherdev.com/wp-content/uploads/2009/12/zmanim_config_selector.png">
