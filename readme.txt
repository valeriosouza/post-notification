=== Notify Users E-Mail ===
Contributors: valeriosza, claudiosanches, ThalitaPinheiro
Donate link: http://valeriosouza.com.br/doacoes/
Tags: notification, users, emails, post, new posts, new pages, new comments, news, newsletter
Requires at least: 3.0
Tested up to: 4.0
Stable tag: 3.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send an email to all users whenever a new post is published on your WordPress.

== Description ==

Notification of new posts by e-mail to all users

This plugin notifies registered users via email of new posts, pages and comments published.

The notification uses the E-mail PHP, check with your hosting your sending limit and the amount of registered users.

Are usually accepted 200 emails per hour.

If your hosting server does not allow sending emails, use a plugin like SMTP http://wordpress.org/plugins/my-smtp-wp/

Now we are in:

Portuguese(Brazil)<br>
Spanish - ThalitaPinheiro<br>
German(Germany) -snowbeachking<br> 
Chinese - junxiu6<br>

Want to help?

If you can translate, help in https://www.transifex.com/projects/p/post-notification-by-email/

Known to develop, help in https://github.com/valeriosouza/notify-users-e-mail

Have suggestions for new features? https://github.com/valeriosouza/notify-users-e-mail/issues/new

Want to keep up with the latest news from this plugin? Follow Twitter [@valeriooficial](https://twitter.com/valeriooficial) and [@claudiosmweb](https://twitter.com/claudiosmweb) and the hashtag [#notifyuserswp](https://twitter.com/search?f=realtime&q=%23notifyuserswp&src=typd).

== Installation ==

1. Upload the `Notify Users E-Mail` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start posting and to automatically publish an e-mail is sent.
1. Configure the plugin in 'Settings -> Notify Users E-Mail'.
1. Register emails and select levels of users that can receive e-mail.
1. Replace the messages according to your preference.

== Contribute ==

Use https://github.com/valeriosouza/notify-users-e-mail

== Frequently Asked Questions ==

= What limit for sending mail? =

The limit is the one who decides your hosting provider, since we use a PHP function pro shooting. They are usually 200 emails per hour.

= I want to change the email sending, like I do? =

Use an SMTP plugin and configure with the data from your email. It is the safest way to use and to avoid blockages or spam boxes.

We recommend http://wordpress.org/plugins/my-smtp-wp/

= Is to change the way of shooting? =

Yes, you can use an SMTP plugin.

We recommend http://wordpress.org/plugins/my-smtp-wp/

= What is the real purpose of this plugin? =

Imagine you have 100 people registered on your site and want to notify them of all posts.

This plugin simplifies sending an email with this notice.

== For Developers ==

= Custom email engine/function =

If you don't want to send emails by `wp_mail()` you can do the following way:

	add_filter( 'notify_users_email_use_wp_mail', '__return_false' );

	function custom_email_engine( $emails, $subject, $message ) {
		// custom actions here!
	}

	add_action( 'notify_users_email_custom_mail_engine', 'custom_email_engine', 10, 3 );


== Screenshots ==

1. Enter email or choose levels of users with permission to receive emails.

2. Personalize your message. The same can be done with pages and comments.

== Changelog ==

= 3.1.2 - 06/11/2014 =

* Fixed: Resolved error latest version of PHP.

= 3.1.1 - 03/11/2014 =

* New: Added translate Chinese and German.
* New: Screen for Help.

= 3.1.0 - 03/08/2014 =

* New: Added new {date} placeholder for comments.
* New: Improved the date format with date_i18n().
* Fixed: Fixed the duplicate emails.
* Fixed: Fixed broken placeholders.
* Fixed: Fixed the page and comments validation when save the options.
* Unreal: The World not found.

= 3.0.2 - 29/07/2014 =

* Fixed: Resolved error comments link.
* Unreal: It is possible to buy Google for $1.

= 3.0.1 - 01/07/2014 =

* New: Adapted to work with the My SMTP WP plugin .
* Unreal: Makes fresh coffee for you.

= 3.0.0 - 26/06/2014 =

* New: Now it is possible to notify new pages created and new comments received.
* New: New translations available
* Unreal: Makes fresh coffee for you.

= 2.0.0 - 16/04/2014 =

* create new interface

= 1.0.4 - 02/09/2013 =

* Update function mail to wp_mail

= 1.0.3 - 02/09/2013 =

* Update function alert

= 1.0.1 - 01/08/2013 =

* Translation es-ES

= 1.0.0 - 29/07/2013 =

* E-mail sent by BCC
* Screen Options
* Security
* Translation pt-BR

= 0.1.0 - 12/07/2013 =

* Lançada primeira versão beta

== Upgrade Notice ==

= 3.1.2 =

* Fixed: Resolved error latest version of PHP.

= 3.1.1 =

* New: Added translate Chinese and German.
* New: Screen for Help.

= 3.1.0 =
Added new {date} placeholder for comments, Improved the date format with date_i18n(), Fixed the duplicate emails, Fixed broken placeholders, Fixed the page and comments validation when save the options.

= 3.0.2 =

Resolved error comments link.

= 3.0.1 =

Adapted to work with the My SMTP WP plugin .

= 2.1.0 =

== License ==

Notify Users E-Mail is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Notify Users E-Mail is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Notify Users E-Mail. If not, see <http://www.gnu.org/licenses/>.
