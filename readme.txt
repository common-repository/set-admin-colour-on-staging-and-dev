=== Auto Set Admin Colour on Staging and Dev ===
Contributors: Fullworks
Tags: admin style,staging,developer,dev tools,admin color
Requires at least: 5.1
Tested up to: 5.4
Requires PHP: 7.1
Stable tag: 4.0.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Provides an automatic, garish style to staging and dev domain, automatically, never get confused again, also can set index / noindex automatically

== Description ==

Lightweight plugin to help developers know they are on their staging site.

If your domain  appears to be a staging or dev domain the admin colours change to a bright colour and you get a nag notice to remind you.

This plugin comes with two custom admin colours, Neon Pink ( you will ned sunglasses ) and Material, if the custom colours are are selected they also shown on the customizer and the adminbar on front end view.

You can also select a standard admin colour which will override the user colours on staging or development.

The plugin comes pre-loaded  with patterns for development and staging domain name matching. You can changes these as required in the plugin's settings page.

Search engine visibility can also be controlled automatically to stop your staging sites getting indexed by Google.




== Installation ==

Install as a normal wordpress plugin

== Frequently Asked Questions ==

= Are there any settings? =

Dashboard>Settings>Staging Colours  but please note, to keep things tidy the setting page is not displayed on production sites ( as it adds no value and hence is just clutter )

= Can I change the colour scheme? =

Yes - go to Dashboard>Settings>Staging Colours

= Can I change what is recognized as a dev or staging domain? =

Yes - go to Dashboard>Settings>Staging Colours

= I changed the settings for domain selection and now nothing shows =

If you change the domain regular expressions and make a mistake and nothing shows becaus ethe plugin 'thinks' your domain is live - simply deactivate, delete and re-install the plugin. this will reset the expressions

== Screenshots ==

1. Neon Pink
2. Material Design Blue
3. customizer
4. front-end

== Upgrade Notice ==
= 4.0.0 =
This version adds a setting to auto noindex dev and  staging site and auto allow search engines for live - you may want to check the settings are right for you

== Changelog ==
= 4.0.0 =
* Adds a setting to auto noindex dev and  staging site and auto allow search engines for live

= 3.0.2 =
* set options if none exist

= 3.0.1 =
* php tidy

= 3.0.0 =
* Major changes to allow definition of development and staging domain and selection of any registered color scheme

= 2.2.5 =
* fix missing class

= 2.2.4 =
* removed link

= 2.2.3 =
* 5.3 support
* remove settings page from live sites

= 2.2.2 =
* added *.dev.cc

= 2.2.1 =
* change link to correct support page

= 2.2 =
* added Freemius optin
* added uninstall clean up of settings

= 2.1 =
* #2 add colour to frontend adminbar
* #3 add colour to customizer

= 2.0 =
* Added extra colour scheme and an settings option page

= 1.1.1 =
* Fix localisation

= 1.1 =
* Extend list of domains

