<?php
/*
 * Notify Users E-Mail.
 *
 * @package   Notify_Users_EMail
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 *
@wordpress-plugin
Plugin Name:       Notify Users E-Mail
Plugin URI:        http://wordpress.org/plugins/notify-users-e-mail/
Description:       Notification of new posts by e-mail to all users
Version:           3.0.2
Author:            Valerio Souza, claudiosanches, ThalitaPinheiro
Author URI:        http://valeriosouza.com.br
Text Domain:       notify-users-e-mail
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Domain Path:       /languages
GitHub Plugin URI: https://github.com/valeriosouza/notify-users-e-mail
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Notify_Users_EMail' ) ) :

/**
 * Notify Users E-Mail class.
 *
 * @package Notify_Users_EMail
 * @author  Valerio Souza <eu@valeriosouza.com.br>
 */
class Notify_Users_EMail {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	const VERSION = '3.0.2';

	/**
	 * Settings name.
	 *
	 * @var      string
	 */
	protected static $settings_name = 'notify_users_email';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added.
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		/**
		 * Admin actions.
		 */
		if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
			$this->admin_include();
			add_action( 'admin_init', array( $this, 'update' ) );
		}

		// Nofity users when publish a post.
		add_action( 'publish_post', array( $this, 'send_notification_post' ) );

		// Nofity users when publish a page.
		add_action( 'publish_page', array( $this, 'send_notification_page' ) );

		// Nofity users when publish a comment.
		add_action( 'wp_insert_comment', array( $this, 'send_notification_comment' ) );
	}

	/**
	 * Admin includes
	 *
	 * @return void
	 */
	private function admin_include() {
		require_once 'includes/class-notify-users-e-mail-admin.php';
	}

	/**
	 * Return the settings name.
	 *
	 * @return string Settings name variable.
	 */
	public function get_settings_name() {
		return self::$settings_name;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses
	 *                               "Network Activate" action, false if
	 *                               WPMU is disabled or plugin is
	 *                               activated on an individual blog.
	 *
	 * @return void
	 */
	public static function activate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();
			} else {
				self::single_activate();
			}
		} else {
			self::single_activate();
		}
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses
	 *                               "Network Deactivate" action, false if
	 *                               WPMU is disabled or plugin is
	 *                               deactivated on an individual blog.
	 *
	 * @return void
	 */
	public static function deactivate( $network_wide ) {
		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {
					switch_to_blog( $blog_id );
					self::single_deactivate();
				}

				restore_current_blog();
			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}
	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {
		global $wpdb;

		// Get an array of blog ids.
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );
	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @return   void
	 */
	private static function single_activate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( 'activate-plugin_' . $plugin );

		$options = array(
			'send_to'          => '',
			'send_to_users'    => array_keys( get_editable_roles() ),
			'subject_post'     => sprintf( __( 'New post published at %s on {date}', self::$settings_name ), get_bloginfo( 'name' ) ),
			'body_post'        => __( 'A new post {title} - {link_post} has been published on {date}.', self::$settings_name ),
			'subject_page'     => sprintf( __( 'New page published at %s on {date}', self::$settings_name ), get_bloginfo( 'name' ) ),
			'body_page'        => __( 'A new page {title} - {link_page} has been published on {date}.', self::$settings_name ),
			'subject_comment'  => sprintf( __( 'New comment published at %s', self::$settings_name ), get_bloginfo( 'name' ) ),
			'body_comment'     => __( 'A new comment {link_comment} has been published.', self::$settings_name ),
		);

		add_option( self::$settings_name, $options );
		add_option( self::$settings_name . '_version', self::VERSION );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @return   void
	 */
	private static function single_deactivate() {
        if ( ! current_user_can( 'activate_plugins' ) ) {
            return;
        }

        $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
        check_admin_referer( 'deactivate-plugin_' . $plugin );

		delete_option( self::$settings_name );
		delete_option( self::$settings_name . '_version' );
	}

	/**
	 * Update the plugin options.
	 *
	 * Make update to version 2.0.0.
	 *
	 * @return   void
	 */
	public function update() {
		$version = get_option( self::$settings_name . '_version' );

		if ( empty( $version ) ) {
			if ( get_option( 'notify_users_mail' ) ) {
				$options = array(
					'send_to'       => get_option( 'notify_users_mail' ),
					'send_to_users' => array_keys( get_editable_roles() ),
					'subject_post'       => get_option( 'notify_users_subject_post' ),
					'body_post'          => get_option( 'notify_users_body_post' ),
					'subject_page'       => get_option( 'notify_users_subject_page' ),
					'body_page'          => get_option( 'notify_users_body_page' ),
					'subject_comment'       => get_option( 'notify_users_subject_comment' ),
					'body_comment'          => get_option( 'notify_users_body_comment' ),
				);

				// Remove old options.
				delete_option( 'notify_users_mail' );
				delete_option( 'notify_users_subject_post' );
				delete_option( 'notify_users_body_post' );
				delete_option( 'notify_users_subject_page' );
				delete_option( 'notify_users_body_page' );
				delete_option( 'notify_users_subject_comment' );
				delete_option( 'notify_users_body_comment' );

				// Save new options.
				update_option( self::$settings_name, $options );
				update_option( self::$settings_name . '_version', self::VERSION );
			}
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @return   void
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'notify-users-e-mail' );

		load_textdomain( 'notify-users-e-mail', trailingslashit( WP_LANG_DIR ) . 'notify-users-e-mail/notify-users-e-mail-' . $locale . '.mo' );
		load_plugin_textdomain( 'notify-users-e-mail', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Apply placeholders.
	 *
	 * @param  string $string  String to apply the placeholder.
	 * @param  int    $post_id Post ID.
	 *
	 * @return string          New string.
	 */
	protected function apply_placeholders( $string, $post_id ) {
		$default_date_format = get_option( 'date_format' ) . ' ' . __( '\a\t', 'notify-users-e-mail' ) . ' ' . get_option( 'time_format' );
		$date_format = apply_filters( $this->get_settings_name() . '_date_format', get_the_time( $default_date_format, $post_id ) );

		$string = str_replace( '{title}', sanitize_text_field( get_the_title( $post_id ) ), $string );
		$string = str_replace( '{link_post}', esc_url( get_permalink( $post_id ) ), $string );
		$string = str_replace( '{link_page}', esc_url( get_permalink( $post_id ) ), $string );
		$string = str_replace( '{link_comment}', esc_url( get_comment_link($post_id) ), $string );
		$string = str_replace( '{date}', $date_format, $string );
		//back is comming
		//$string = str_replace( '{excerpt}', sanitize_text_field( get_the_excerpt( $post_id ) ), $string );

		return $string;
	}

	/**
	 * Create the nofitication email list.
	 *
	 * @param  array  $roles   Roles of users who received the email.
	 * @param  string $send_to List of emails.
	 *
	 * @return array           Email list.
	 */
	protected function notification_list( $roles, $send_to ) {
		$emails = array();

		if ( is_array( $roles ) && ! empty( $roles ) ) {
			// Get emails by user role.
			foreach ( $roles as $role ) {
				// Get the emails.
				$user = new WP_User_Query(
					array(
						'role'   => $role,
						'fields' => array( 'user_email' )
					)
				);
				$user_results = $user->get_results();

				// Add the emails in $mails variable.
				if ( ! empty( $user_results ) ) {
					foreach ( $user_results as $email ) {
						$emails[] = $email->user_email;
					}
				}
			}
		}

		// Merge all emails list.
		$emails = array_unique( array_merge( $emails, explode( ',', $send_to ) ) );

		return $emails;
	}

	/**
	 * Nofity users when publish a post.
	 *
	 * @param  int $post_id Current post ID.
	 *
	 * @return void
	 */
	public function send_notification_post( $post_id ) {
		if ( 'publish' == $_POST['post_status'] && 'publish' != $_POST['original_post_status'] ) {
			$settings = get_option( $this->get_settings_name() );
			$emails   = $this->notification_list( $settings['send_to_users'], $settings['send_to'] );
			$subject_post  = $this->apply_placeholders( $settings['subject_post'], $post_id );
			$body_post     = $this->apply_placeholders( $settings['body_post'], $post_id );
			$headers  = 'Bcc: ' . implode( ',', $emails );

			// Send the emails.
			if ( apply_filters( $this->get_settings_name() . '_use_wp_mail', true ) ) {
				wp_mail( '', $subject_post, $body_post, $headers );
			} else {
				do_action( $this->get_settings_name() . '_custom_mail_engine', $emails, $subject_post, $body_post );
			}
		}
	}

	/**
	 * Nofity users when publish a page.
	 *
	 * @param  int $post_id Current post ID.
	 *
	 * @return void
	 */
	public function send_notification_page( $post_id ) {
		if ( 'publish' == $_POST['post_status'] && 'publish' != $_POST['original_post_status'] ) {
			$settings = get_option( $this->get_settings_name() );
			$emails   = $this->notification_list( $settings['send_to_users'], $settings['send_to'] );
			$subject_page  = $this->apply_placeholders( $settings['subject_page'], $post_id );
			$body_page     = $this->apply_placeholders( $settings['body_page'], $post_id );
			$headers  = 'Bcc: ' . implode( ',', $emails );

			// Send the emails.
			if ( apply_filters( $this->get_settings_name() . '_use_wp_mail', true ) ) {
				wp_mail( '', $subject_page, $body_page, $headers );
			} else {
				do_action( $this->get_settings_name() . '_custom_mail_engine', $emails, $subject_page, $body_page );
			}
		}
	}
	/**
	 * Nofity users when publish a comment.
	 *
	 * @param int $post_id Current post ID.
	 *
	 * @return void
	 */
	public function send_notification_comment( $post_id ) {
		$settings         = get_option( $this->get_settings_name() );
		$emails           = $this->notification_list( $settings['send_to_users'], $settings['send_to'] );
		$subject_comment  = $this->apply_placeholders( $settings['subject_comment'], $post_id );
		$body_comment     = $this->apply_placeholders( $settings['body_comment'], $post_id );
		$headers          = 'Bcc: ' . implode( ',', $emails );

		// Send the emails.
		if ( apply_filters( $this->get_settings_name() . '_use_wp_mail', true ) ) {
			wp_mail( '', $subject_comment, $body_comment, $headers );
		} else {
			do_action( $this->get_settings_name() . '_custom_mail_engine', $emails, $subject_comment, $body_comment );
		}
	}
}

/**
 * Register plugin activation and deactivation.
 */
register_activation_hook( __FILE__, array( 'Notify_Users_EMail', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Notify_Users_EMail', 'deactivate' ) );

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', array( 'Notify_Users_EMail', 'get_instance' ), 0 );

endif;
