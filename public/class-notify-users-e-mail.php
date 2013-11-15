<?php
/**
 * Notify Users E-Mail.
 *
 * @package   Notify_Users_EMail
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

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
	 * @since   2.0.0
	 *
	 * @var     string
	 */
	const VERSION = '2.0.0';

	/**
	 * Plugin slug for text domain.
	 *
	 * @since    2.0.0
	 *
	 * @var      string
	 */
	protected static $plugin_slug = 'notify-users-email';

	/**
	 * Settings name.
	 *
	 * @since    2.0.0
	 *
	 * @var      string
	 */
	protected static $settings_name = 'notify_users_email';

	/**
	 * Instance of this class.
	 *
	 * @since    2.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     2.0.0
	 */
	private function __construct() {

		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added.
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Nofity users when publish a post.
		add_action( 'publish_post', array( $this, 'send_notification' ) );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    2.0.0
	 *
	 * @return   Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return self::$plugin_slug;
	}

	/**
	 * Return the settings name.
	 *
	 * @since     2.0.0
	 *
	 * @return    string Settings name variable.
	 */
	public function get_settings_name() {
		return self::$settings_name;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    2.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 *
	 * @return   void
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
	 * @since    2.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 *
	 * @return   void
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
	 * @since    2.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {
		if ( 1 !== did_action( 'wpmu_new_blog' ) )
			return;

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
	 * @since    2.0.0
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
	 * @since    2.0.0
	 *
	 * @return   void
	 */
	private static function single_activate() {
		$options = array(
			'send_to'       => '',
			'send_to_users' => array_keys( get_editable_roles() ),
			'subject'       => sprintf( __( 'New post published at %s on {date}', self::$settings_name ), get_bloginfo( 'name' ) ),
			'body'          => __( 'A new post "{title}" - {link} has been published on {date}.', self::$settings_name ),
		);

		add_option( self::$settings_name, $options );
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    2.0.0
	 *
	 * @return   void
	 */
	private static function single_deactivate() {
		delete_option( self::$settings_name );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 *
	 * @return   void
	 */
	public function load_plugin_textdomain() {
		$domain = $this->get_plugin_slug();
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );
	}

	/**
	 * Apply placeholders.
	 *
	 * @since    2.0.0
	 *
	 * @param    string $string  String to apply the placeholder.
	 * @param    int    $post_id Post ID.
	 *
	 * @return   string          New string.
	 */
	protected function apply_placeholders( $string, $post_id ) {
		$default_date_format = get_option( 'date_format' ) . ' ' . __( '\a\t', $this->get_plugin_slug() ) . ' ' . get_option( 'time_format' );
		$date_format = apply_filters( $this->get_settings_name() . '_date_format', get_the_time( $default_date_format, $post_id ) );

		$string = str_replace( '{title}', sanitize_text_field( get_the_title( $post_id ) ), $string );
		$string = str_replace( '{link}', esc_url( get_permalink( $post_id ) ), $string );
		$string = str_replace( '{date}', $date_format, $string );

		return $string;
	}

	/**
	 * Nofity users when publish a post.
	 *
	 * @since    2.0.0
	 *
	 * @param    int $post_id Current post ID.
	 *
	 * @return   void
	 */
	public function send_notification( $post_id ) {
		if ( 'publish' == $_POST['post_status'] && 'publish' != $_POST['original_post_status'] ) {
			$settings       = get_option( $this->get_settings_name() );
			$to             = ! empty( $settings['to'] ) ? $settings['to'] : get_option( 'admin_email' );
			$subject        = $this->apply_placeholders( $settings['subject'], $post_id );
			$body           = $this->apply_placeholders( $settings['body'], $post_id );
			$wp_user_search = new WP_User_Query(
				array(
					'fields' => array( 'user_email' )
				)
			);

			// Sets the Bcc.
			$bcc = array();
			foreach ( $wp_user_search->get_results() as $user )
				$bcc[] = $user->user_email;

			$emails  = array_unique( array_merge( $bcc, explode( ',', $to ) ) );
			$headers = 'Bcc: ' . implode( ',', $emails );

			// Send the emails.
			$teste = wp_mail( '', $subject, $body, $headers );
		}
	}

}
