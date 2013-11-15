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
 * Plugin admin.
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
		if ( null == self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    2.0.0
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
	 */
	public function display_plugin_admin_page() {
		$settings_name = $this->settings_name;

		include_once 'views/admin.php';
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function plugin_settings() {
		$settings_section = 'settings_section';

		// Set the settings section.
		add_settings_section(
			$settings_section,
			__( 'Email Settings', $this->plugin_slug ),
			'__return_false',
			$this->settings_name
		);

		// From.
		add_settings_field(
			'from',
			__( 'Email From', $this->plugin_slug ),
			array( $this, 'text_element_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'from',
				'description' => sprintf( __( 'Default is %s', $this->plugin_slug ), '<code>' . get_option( 'admin_email' ) . '</code>' ),
				'default'     => ''
			)
		);

		// Email Subject Prefix.
		add_settings_field(
			'subject_prefix',
			__( 'Email Subject Prefix', $this->plugin_slug ),
			array( $this, 'text_element_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'subject_prefix',
				'description' => '',
				'default'     => ''
			)
		);

		// Email Body Prefix.
		add_settings_field(
			'body_prefix',
			__( 'Email Body Prefix', $this->plugin_slug ),
			array( $this, 'text_element_callback' ),
			$this->settings_name,
			$settings_section,
			array(
				'id'          => 'body_prefix',
				'description' => '',
				'default'     => ''
			)
		);

		// Register settings.
		register_setting( $this->settings_name, $this->settings_name, array( $this, 'validate_options' ) );
	}

	/**
	 * Get option value.
	 *
	 * @param     string $id      Option ID.
	 * @param     string $default Default option.
	 *
	 * @return    array           Option value.
	 */
	protected function get_option_value( $id, $default = '' ) {
		$options = get_option( $this->settings_name );

		if ( isset( $options[ $id ] ) )
			$default = $options[ $id ];

		return $default;
	}

	/**
	 * Text field callback.
	 *
	 * @param     array $args Arguments from the option.
	 *
	 * @return    string      Input field HTML.
	 */
	public function text_element_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text" />', $id, $this->settings_name, $current );

		// Displays the description.
		if ( $args['description'] )
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );

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
			if ( isset( $input[ $key ] ) )
				$output[ $key ] = sanitize_text_field( $input[ $key ] );
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
