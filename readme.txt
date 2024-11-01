=== Plugin Name ===
Contributors: tsewlliw
Donate link: http://00plugin.net
Tags: audit, devops
Requires at least: 4.2.2
Tested up to: 4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple audit and traceablity for WordPress DevOps

== Description ==

Logs important WordPress lifecycle events like post status changes and plugin activation.

Enhances `WP_DEBUG` mode with extra information about actions taken.

Automatically sends events to New Relic

== Installation ==

1. Upload `what-the-wp.zip` via the `upload` tab in WordPress admin
1. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= Where are my error logs? =

Depends on your system. Great places to start are `/var/log/apache2/$VHOST.error.log` and `/var/log/nginx/$VHOST.error.log`.

== Screenshots ==

== Changelog ==

= 0.5 =
* updated compat
* added user lifecycle events

= 0.4 =
* confirmed compat with 4.4 core
* fixed bug handling theme updates

= 0.3 =
* handle bulk plugin and theme upgrades as multiple events
* make event auditing pluggable via wtwp_audit_event action
* always collect current user ID

= 0.2 =
* audit upgrade events
* send audit events to newrelic if available

= 0.1 =
* its alive

== Upgrade Notice ==

= 0.1 =
You need this plugin
