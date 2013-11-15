<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Notify_Users_EMail
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'notify_users_email' );
delete_option( 'notify_users_email_version' );
