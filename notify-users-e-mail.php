<?php
/**
 * Notify Users E-Mail.
 *
 * @package   Notify_Users_EMail
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 *
 * @wordpress-plugin
 * Plugin Name:       Notify Users E-Mail
 * Plugin URI:        http://wordpress.org/plugins/notify-users-e-mail/
 * Description:       Notification of new posts by e-mail to all users
 * Version:           2.0.0
 * Author:            Valerio Souza, CodeHost, claudiosanches
 * Author URI:        http://valeriosouza.com.br
 * Text Domain:       notify-users-e-mail
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/valeriosouza/notify_users_email
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Main class.
 */
require_once plugin_dir_path( __FILE__ ) . '/public/class-notify-users-e-mail.php';

/**
 * Register plugin activation and deactivation.
 */
register_activation_hook( __FILE__, array( 'Notify_Users_EMail', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Notify_Users_EMail', 'deactivate' ) );

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', array( 'Notify_Users_EMail', 'get_instance' ) );

/**
 * Administration.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once plugin_dir_path( __FILE__ ) . '/admin/class-notify-users-e-mail-admin.php';
	add_action( 'plugins_loaded', array( 'Notify_Users_EMail_Admin', 'get_instance' ) );
	add_action( 'admin_init', array( 'Notify_Users_EMail', 'update' ) );
}
