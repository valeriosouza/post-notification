=== Post Notification by Email (Old Notify Users Email) ===
Contributors: valeriosza, claudiosanches
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=P5QTGDB64SU8E&lc=US&item_name=WordPress%20Plugins&no_note=0&cn=Adicionar%20instru%c3%a7%c3%b5es%20especiais%20para%20o%20vendedor%3a&no_shipping=1&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: notification, users, emails, post, new posts, new pages, new comments, news, newsletter,posts, post type
Requires at least: 3.0
Tested up to: 4.1.1
Stable tag: 4.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Send an email to all users whenever a new post is published on your WordPress.

== Description ==

This version especial thanks for [caramelopardalis](https://github.com/caramelopardalis)

This plugin notifies registered users via email of new posts, pages, post types and comments published.

The notification uses the E-mail PHP, check with your hosting your sending limit and the amount of registered users.

Are usually accepted 200 emails per hour.

If your hosting server does not allow sending emails, use a plugin like SMTP http://wordpress.org/plugins/my-smtp-wp/ or read our FAQ

= Heads up: =

Read the [FAQ](https://wordpress.org/plugins/notify-users-e-mail/faq/) before use.

Want to help? Use the [support](https://wordpress.org/support/plugin/notify-users-e-mail)

= Now we are in: =

11 languages:

- English
- Portuguese
- French
- Polish
- German
- Spanish
- Dutch
- Chinese
- Italian
- Czech
- Japanese

= Want to help? =

If you can translate, help in https://www.transifex.com/projects/p/post-notification-email/ in WP-Translations.

Known to develop, help in https://github.com/valeriosouza/post-notification-by-email

Have suggestions for new features? https://github.com/valeriosouza/post-notification-by-email/issues/new

Want to keep up with the latest news from this plugin? Follow Twitter [@valeriosza](https://twitter.com/valeriosza)

== Installation ==

1. Upload the `Post Notification by Email` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Start posting and to automatically publish an e-mail is sent.
1. Configure the plugin in 'Post Notification by Email' Menu.
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

= Is there any other way to send emails? =

Yes, you can use SendGrid, Mandrill and the Amazon SES. Almost everyone has a WordPress plugin or accept a SMTP configuration.

= I installed the plugin and does not send email. What can be? =

Open the settings screen and see if there is any category or post type registered.

= I want you to always send for all categories, how do? =

On the settings screen select all categories. By default, when you install the plugin he already choose all categories registered.

= What is the real purpose of this plugin? =

Imagine you have 100 people registered on your site and want to notify them of all posts.

This plugin simplifies sending an email with this notice.

= My comments are not being sent because? =

Only emails are sent for comments that do not suffer moderation rules. If for some reason he held in moderation, after the approval e-mail is not sent.

Look this `wp-admin/options-discussion.php`

This plugin will not change this function, you can go out on a add-on or pro version.

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

2. Personalize your message. Use image, colors and whatever you decide.

3. Conditions for sending the email.

== Changelog ==

= 4.1.2 - 23/02/2015 =

* Fixed: bugs and errors.

= 4.1.1 - 20/02/2015 =

* New: Draft for published in Bulk Action.
* Fixed: Error Undefined index: conditional_post_type.
* Fixed: No email is sent for new comments.

= 4.1 - 10/02/2015 =

* Fixed: Resolved bug load_plugin_textdomain.
* Fixed: Resolved bug Emails are being sent for unpublished Comments.
* New: All Posts types in conditional.
* Unreal: We love the Brazilian currency.

= 4.0.4 - 08/01/2014 =

* Fixed: Resolved bugs, errors and add new languages.

= 4.0.3 - 25/12/2014 =

* Fixed: Resolved bugs and errors.

= 4.0.2 - 24/11/2014 =

* Fixed: Resolved bugs and errors.

= 4.0.1 - 24/11/2014 =

* Fixed: Resolved bugs and errors.

= 4.0.0 - 14/11/2014 =

* New: New name.
* New: New menu and page.
* New: Added new {content_post} placeholder for content of posts.
* New: Added conditions for sending the email.
* New: Added new fields editor and HTML email.
* Unreal: We will dominate the world

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

= 4.1.2 - 23/02/2015 =

* Fixed: bugs and errors.

= 4.1.1 - 20/02/2015 =

* New: Draft for published in Bulk Action.
* Fixed: Error Undefined index: conditional_post_type.
* Fixed: No email is sent for new comments.

= 4.1 =

* Fixed: Resolved bug load_plugin_textdomain.
* Fixed: Resolved bug Emails are being sent for unpublished Comments.
* New: All Posts types in conditional.
* Unreal: We love the Brazilian currency.

= 4.0.4 =

* Fixed: Resolved bugs, errors and add new languages.

= 4.0.3 =

* Fixed: Resolved bugs and errors.

= 4.0.2 =

* Fixed: Resolved bugs and errors.

= 4.0.1 =

* Fixed: Resolved bugs and errors.

= 4.0.0 =

* New: New name.
* New: New menu and page.
* New: Added new {content_post} placeholder for content of posts.
* New: Added conditions for sending the email.
* New: Added new fields editor and HTML email.

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

Post Notification by Email is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

Post Notification by Email is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with Post Notification by Email. If not, see <http://www.gnu.org/licenses/>.
