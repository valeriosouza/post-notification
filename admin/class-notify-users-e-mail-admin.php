<?php
/**
 * Notify Users E-Mail.
 *
 * @package   Notify_Users_EMail_Admin
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

/**
 * Notify Users E-Mail admin class.
 *
 * @package Notify_Users_EMail_Admin
 * @author  Valerio Souza <eu@valeriosouza.com.br>
 */
class Notify_Users_EMail_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    2.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     2.0.0
	 */
	private function __construct() {
		$plugin              = Notify_Users_EMail::get_instance();
		$this->plugin_slug   = $plugin->get_plugin_slug();
		$this->settings_name = $plugin->get_settings_name();

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Init plugin options.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
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
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    2.0.0
	 *
	 * @return   void
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			__( 'Notify Users E-Mail', $this->plugin_slug ),
			__( 'Notify Users E-Mail', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    2.0.0
	 *
	 * @return   void
	 */
	public function display_plugin_admin_page() {
		$settings_name = $this->settings_name;

		include_once 'views/admin.php';
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since     2.0.0
	 *
	 * @return    void
	 */
	public function plugin_settings() {
		$settings_section = 'settings_section';
		$placeholders_description = sprintf(
			__( '%s You can use the following placeholders:%s %s', $this->plugin_slug ),
			'<p>',
			'</p>',
			sprintf(
				'<ul><li><p><code>{title}</code> %s</p></li><li><p><code>{link}</code> %s</p></li><li><p><code>{date}</code> %s</p></li></ul>',
				__( 'to display the title', $this->plugin_slug ),
				__( 'to display the URL', $this->plugin_slug ),
				__( 'to display the date of publication', $this->plugin_slug )
			)
		);

		// Set the settings section.
		add_settings_section(
			$settings_section,
			__( 'Email Settings', $this->plugin_slug ),
			'__return_false',
			$this->settings_name
		);

		// Sent to.
		add_settings_field(
			'send_to',
			__( 'Sent to', $this->plugin_slug ),
			array( $this, 'text_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'send_to',
				'description' => sprintf( '<p>' . __( 'Enter with the recipients for the email (separated by commas).', $this->plugin_slug ) . '</p>' ),
				'default'     => ''
			)
		);

		// Send to users.
		add_settings_field(
			'send_to_users',
			__( 'Send to users', $this->plugin_slug ),
			array( $this, 'users_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'send_to_users',
				'description' => '<p>' . __( 'Select the type of user that will receive notifications.', $this->plugin_slug ) . '</p>',
				'default'     => array()
			)
		);

		// Email Subject.
		add_settings_field(
			'subject',
			__( 'Subject', $this->plugin_slug ),
			array( $this, 'text_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'subject',
				'description' => $placeholders_description,
				'default'     => ''
			)
		);

		// Email Body Prefix.
		add_settings_field(
			'body',
			__( 'Body', $this->plugin_slug ),
			array( $this, 'textarea_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'body',
				'description' => $placeholders_description,
				'default'     => ''
			)
		);

		// Register settings.
		register_setting( $this->settings_name, $this->settings_name, array( $this, 'validate_options' ) );
	}

	/**
	 * Get option value.
	 *
	 * @since     2.0.0
	 *
	 * @param     string $id      Option ID.
	 * @param     string $default Default option.
	 *
	 * @return    array           Option value.
	 */
	protected function get_option_value( $id, $default = '' ) {
		$options = get_option( $this->settings_name );

		if ( isset( $options[ $id ] ) ) {
			$default = $options[ $id ];
		}

		return $default;
	}

	/**
	 * Users field callback.
	 *
	 * @since     2.0.0
	 *
	 * @param     array $args Arguments from the option.
	 *
	 * @return    string      Input field HTML.
	 */
	public function users_callback( $args ) {
		$id       = $args['id'];
		$wp_roles = new WP_Roles();
		$roles    = $wp_roles->get_names();

		// Sets current option.
		$current = $this->get_option_value( $id, $args['default'] );

		$html = sprintf( '<select id="%1$s" name="%2$s[%1$s][]" multiple="multiple">', $id, $this->settings_name );
		foreach ( $roles as $role_value => $role_name ) {
			$current_item = in_array( $role_value, $current ) ? ' selected="selected"' : '';
			$html .= sprintf( '<option value="%s"%s>%s</option>', $role_value, $current_item, $role_name );
		}

		$html .= '</select>';

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Text field callback.
	 *
	 * @since     2.0.0
	 *
	 * @param     array $args Arguments from the option.
	 *
	 * @return    string      Input field HTML.
	 */
	public function text_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text" />', $id, $this->settings_name, $current );

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Textarea field callback.
	 *
	 * @since     2.0.0
	 *
	 * @param     array $args Arguments from the option.
	 *
	 * @return    string      Input field HTML.
	 */
	public function textarea_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		$html = sprintf( '<textarea id="%1$s" name="%2$s[%1$s]" cols="60" rows="5">%3$s</textarea>', $id, $this->settings_name, $current );

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @since    2.0.0
	 *
	 * @param    array $input Options to valid.
	 *
	 * @return   array        Validated options.
	 */
	public function validate_options( $input ) {
		$output = array();

		foreach ( $input as $key => $value ) {
			if ( isset( $input[ $key ] ) ) {
				if ( 'send_to_users' == $key ) {
					$send_to_users = array();
					foreach ( $input[ $key ] as $value ) {
						$send_to_users[] = sanitize_text_field( $value );
					}
					$output[ $key ] = $send_to_users;
				} elseif ( 'body' == $key ) {
					$output[ $key ] = wp_kses( $input[ $key ], array() );
				} else {
					$output[ $key ] = sanitize_text_field( $input[ $key ] );
				}
			}
		}

		return $output;
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    2.0.0
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);
	}
}
