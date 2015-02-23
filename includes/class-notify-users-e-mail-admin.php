<?php
/**
 * Post Notification by Email.
 *
 * @package   Notify_Users_EMail_Admin
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Notify Users E-Mail admin class.
 *
 * @package Notify_Users_EMail_Admin
 * @author  Valerio Souza <eu@valeriosouza.com.br>
 */
class Notify_Users_EMail_Admin {

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings co and menu.
	 */
	public function __construct() {
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ),2 );

		// Add the welcome page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_welcome_menu' ),1 );

		// Add the welcome page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_welcome_submenu' ),1 );

		// Init plugin options.
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );

		// Admin scripts.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __NTF_USR_FILE__ ) . 'notify-users-e-mail' . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	* Load admin scripts.
	*
	* @return void
	*/
	public function admin_scripts( $hook ) {

		// Checks if is the settings page.
		if ( 'post-notification-by-email_page_notify-users-e-mail-settings' == $hook ) {
			// Media Upload.
			wp_enqueue_media();

			wp_register_style( 'select2', plugins_url( 'lib/css/select2.css', plugin_dir_path( __FILE__ ) ), array(  ), '3.5.2', 'all' );
			wp_register_script( 'select2', plugins_url( 'lib/js/vendor/select2/select2.min.js', plugin_dir_path( __FILE__ ) ), array( 'jquery' ), '3.5.2', true );



			// Theme Options.
			wp_enqueue_style( 'notify-users-e-mail-admin', plugins_url( 'lib/css/admin.css', plugin_dir_path( __FILE__ ) ), array( 'select2' ), Notify_Users_EMail::VERSION, 'all' );

			wp_enqueue_script( 'notify-users-e-mail-admin', plugins_url( 'lib/js/admin.js', plugin_dir_path( __FILE__ ) ), array( 'jquery', 'select2' ), Notify_Users_EMail::VERSION, true );

			// Localize strings.
			wp_localize_script(
				'notify-users-e-mail-admin',
				'notify_users_e_mail_params',
				array(
					'uploadTitle'   => __( 'Choose a file', 'notify-users-e-mail' ),
					'uploadButton'  => __( 'Add file', 'notify-users-e-mail' ),
				)
			);
		}
	}

	/**
	 * Register the welcome menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return   void
	 */
	public function add_plugin_welcome_menu() {
		add_menu_page(
			__( 'Post Notification by Email', 'notify-users-e-mail' ),
			'Post Notification by Email',
			'manage_options',
			'notify-users-e-mail',
			array( $this, 'display_plugin_welcome_page' ),
			'dashicons-email'
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 */
	public function display_plugin_welcome_page() {
		include_once 'views/welcome.php';
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return   void
	 */
	public function add_plugin_welcome_submenu() {
		add_submenu_page(
			'notify-users-e-mail',
			'Post Notification by Email',
			__( 'Welcome', 'notify-users-e-mail' ),
			'manage_options',
			'notify-users-e-mail'
			//array( $this, '' )
		);
	}

		/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @return   void
	 */
	public function add_plugin_admin_menu() {
		add_submenu_page(
			'notify-users-e-mail',
			'Post Notification by Email Settings',
			__( 'Settings', 'notify-users-e-mail' ),
			'manage_options',
			'notify-users-e-mail-settings',
			array( $this, 'display_plugin_admin_page' )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @return void
	 */
	public function display_plugin_admin_page() {
		include_once 'views/admin.php';
	}

	/**
	 * Plugin settings form fields.
	 *
	 * @return void
	 */
	public function plugin_settings() {
		$settings_section = 'settings_section';
		$placeholders_description_post = sprintf(
			__( '%s You can use the following placeholders:%s %s', 'notify-users-e-mail' ),
			'<p>',
			'</p>',
			sprintf(
				'<ul><li><p><code>{title}</code> %s</p></li><li><p><code>{link_post}</code> %s</p></li><li><p><code>{content_post}</code> %s</p></li><li><p><code>{date}</code> %s</p></li></ul>',
				__( 'to display the title', 'notify-users-e-mail' ),
				__( 'to display the URL', 'notify-users-e-mail' ),
				__( 'to display the content', 'notify-users-e-mail' ),
				__( 'to display the date of publication', 'notify-users-e-mail' )
			)
		);
		$placeholders_description_comment = sprintf(
			__( '%s You can use the following placeholders:%s %s', 'notify-users-e-mail' ),
			'<p>',
			'</p>',
			sprintf(
				'<ul><li><p><code>{title}</code> %s</p></li><li><p><code>{link_comment}</code> %s</p></li><li><p><code>{date}</code> %s</p></li></ul>',
				__( 'to display the post title', 'notify-users-e-mail' ),
				__( 'to display the comment URL', 'notify-users-e-mail' ),
				__( 'to display the date of comment publication', 'notify-users-e-mail' )
			)
		);


		// Set the settings section.
		add_settings_section(
			$settings_section,
			__( 'Email Settings', 'notify-users-e-mail' ),
			'__return_false',
			'notify_users_email'
		);

		// Sent to.
		add_settings_field(
			'send_to',
			__( 'Sent to', 'notify-users-e-mail' ),
			array( $this, 'text_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'send_to',
				'description' => sprintf( '<p>' . __( 'Enter email address (separated by commas) for emails to be sent regardless of settings below. A registered user may receive two emails if you list it here.', 'notify-users-e-mail' ) . '</p>' ),
				'default'     => ''
			)
		);

		// Send to users.
		add_settings_field(
			'send_to_users',
			__( 'Send to users', 'notify-users-e-mail' ),
			array( $this, 'users_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'send_to_users',
				'description' => '<p>' . __( 'Select the type of user that will receive notifications. You can choose more than one type using ctrl+click.', 'notify-users-e-mail' ) . '</p>',
				'default'     => array()
			)
		);


		// Email Subject Post.
		add_settings_field(
			'subject_post',
			__( 'Email subject for new posts, pages and post types.', 'notify-users-e-mail' ),
			array( $this, 'text_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'subject_post',
				'description' => $placeholders_description_post,
				'default'     => ''
			)
		);

		// Email Body Prefix Post.
		add_settings_field(
			'body_post',
			__( 'Email body for new posts, pages and post types.', 'notify-users-e-mail' ),
			array( $this, 'editor_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'body_post',
				'description' => $placeholders_description_post,
				'default'     => ''
			)
		);

		// Email Subject Comment.
		add_settings_field(
			'subject_comment',
			__( 'Email subject for new comments', 'notify-users-e-mail' ),
			array( $this, 'text_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'subject_comment',
				'description' => $placeholders_description_comment,
				'default'     => ''
			)
		);

		// Email Body Prefix Comment.
		add_settings_field(
			'body_comment',
			__( 'Email body for new comments', 'notify-users-e-mail' ),
			array( $this, 'editor_callback' ),
			'notify_users_email',
			$settings_section,
			array(
				'id'          => 'body_comment',
				'description' => $placeholders_description_comment,
				'default'     => ''
			)
		);


		// Set the conditional section.
		add_settings_section(
			'conditional_section',
			__( 'Conditional Settings', 'notify-users-e-mail' ),
			'__return_false',
			'notify_users_email'
		);

                // Select All Post types.
                $post_types_options = array(); 
                $args = array(
				   'public'   => true
				);
                $post_types = get_post_types($args, 'objects'); 
                foreach ($post_types as $post_type) { 
                    $post_types_options[] = array( 
                        'id' => $post_type->name, 
                        'text' => esc_attr__($post_type->labels->name), 
                    );
                } 

                // Email Body Prefix Comment.
		add_settings_field(
			'conditional_post_type',
			esc_attr__( 'Post Types', 'notify-users-e-mail' ),
			array( $this, 'select2_callback' ),
			'notify_users_email',
			'conditional_section',
			array(
				'id'          => 'conditional_post_type',
				'options'     => $post_types_options,
				'description' => esc_attr__( 'Which Post Types will trigger a notification', 'notify-users-e-mail' ),
				'default'     => array( 'post', 'page' ),
				'multiple'    => true,
			)
		);


		$taxonomies = array( 'post_tag', 'category' );

		foreach ( $taxonomies as $taxonomy ) {
			if ( is_string( $taxonomy ) ){
				$taxonomy = get_taxonomy( $taxonomy );
			}

			$options = array();
			$terms =  get_terms( $taxonomy->name, array(
				'hide_empty' => false,
			) );

			foreach ( $terms as $term ) {
				$options[] = array(
					'id' => $term->term_id,
					'text' => $term->name,
					'slug' => $term->slug,
					'taxonomy' => $taxonomy->name,
				);
			}

			// Email Body Prefix Comment.
			add_settings_field(
				'conditional_taxonomy_' . $taxonomy->name,
				$taxonomy->labels->name,
				array( $this, 'select2_callback' ),
				'notify_users_email',
				'conditional_section',
				array(
					'id'          => 'conditional_taxonomy_' . $taxonomy->name,
					'options'     => $options,
					'description' => sprintf( esc_attr__( 'Which terms from %s will send a notification', 'notify-users-e-mail' ), $taxonomy->labels->singular_name ),
					'default'     => '',
					'multiple'    => true,
					'placeholder' => sprintf( esc_attr__( 'Select the %s', 'notify-users-e-mail' ), $taxonomy->labels->name ),
				)
			);

		}

		// Register settings.
		register_setting( 'notify_users_email', 'notify_users_email', array( $this, 'validate_options' ) );

	}

	/**
	 * Get option value.
	 *
	 * @param  string $id      Option ID.
	 * @param  string $default Default option.
	 *
	 * @return array           Option value.
	 */
	protected function get_option_value( $id, $default = '' ) {
		$options = get_option( 'notify_users_email' );

		if ( isset( $options[ $id ] ) ) {
			$default = $options[ $id ];
		}

		return $default;
	}

	/**
	 * Users field callback.
	 *
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Input field HTML.
	 */
	public function users_callback( $args ) {
		$id       = $args['id'];
		$wp_roles = new WP_Roles();
		$roles    = $wp_roles->get_names();

		// Sets current option.
		$current = $this->get_option_value( $id, $args['default'] );

		$html = sprintf( '<select id="%1$s" name="%2$s[%1$s][]" multiple="multiple">', $id, 'notify_users_email' );
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
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Text input field HTML.
	 */
	public function text_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		$html = sprintf( '<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text" />', $id, 'notify_users_email', $current );

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Select2 field callback.
	 *
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Text input field HTML.
	 */
	public function select2_callback( $args ) {
		// Have some defaults boy...
		$defaults = array(
			'multiple' => false,
			'options' => array(),
			'default' => null,
			'id' => null,
			'class' => array(),
			'placeholder' => esc_attr__( 'Choose some Options', 'notify-users-e-mail' ),
		);

		// Parse the args gracefully
		$args = wp_parse_args( $args, $defaults );

		$id = $args['id'];

		// Sets current option.
		$current = implode( ',', array_filter( array_map( 'trim', (array) $this->get_option_value( $id, $args['default'] ) ) ) );
		$options_json = htmlspecialchars( json_encode( $args['options'] ) );
		$classes = (array) $args['class'];

		if ( $args['multiple'] === true ){
			$classes[] = 'input-select2 input-select2-tags';
		} else {
			$classes[] = 'input-select2 input-select2-single';
		}

		$html = sprintf( '<input type="hidden" id="%1$s" name="%2$s[%1$s]" value="%3$s" data-options="%4$s" placeholder="%5$s" class="' . implode( ' ', $classes ) . '" />', $id, 'notify_users_email', $current, $options_json, $args['placeholder'] );

		// Displays the description.
		if ( ! empty( $args['description'] ) ) {
			$html .= sprintf( '<div class="description">%s</div>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Editor field callback.
	 *
	 * @param  array $args Arguments from the option.
	 *
	 * @return string      Editor field HTML.
	 */
	public function editor_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = $this->get_option_value( $id, $args['default'] );

		echo '<div style="width: 600px;">';
				wp_editor( $current, $id, array( 'textarea_name' => 'notify_users_email' . '[' . $id . ']', 'textarea_rows' => 10 ) );
		echo '</div>';

		// Displays the description.
		if ( $args['description'] ) {
			echo sprintf( '<div class="description">%s</div>', $args['description'] );
		}
	}

		/**
	 * Image field callback.
	 *
	 * @param array $args Arguments from the option.
	 *
	 * @return string Image field HTML.
	 */
	public function image_callback( $args ) {
		$id = $args['id'];

		// Sets current option.
		$current = esc_html( $this->get_option_value( $id, $args['default'] ) );

		// Gets placeholder image.
		$image = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
		$html  = '<div class="notify-users-e-mail-upload-image">';
		$html .= '<span class="notify-users-e-mail-default-image">' . $image . '</span>';

		if ( $current ) {
			$image = wp_get_attachment_image_src( $current, 'medium' );
			$image = $image[0];
		}

		$html .= sprintf( '<input id="%1$s" name="%2$s[%1$s]" type="hidden" class="notify-users-e-mail-image" value="%3$s" /><div class="notify-users-e-mail-preview-wrap"><img src="%4$s" class="notify-users-e-mail-preview" style="max-height: 150px; width: auto;" alt="" /><ul class="notify-users-e-mail-actions"><li><a href="#" class="notify-users-e-mail-delete" title="%6$s"><span class="dashicons dashicons-no"></span></a></li></ul></div><input id="%1$s-button" class="button" type="button" value="%5$s" />', $id, $this->settings_name, $current, $image, __( 'Select image', 'notify-users-e-mail' ), __( 'Remove image', 'notify-users-e-mail' ) );

		$html .= '<br class="clear" />';
		$html .= '</div>';

		// Displays the description.
		if ( $args['description'] ) {
			$html .= sprintf( '<p class="description">%s</p>', $args['description'] );
		}

		echo $html;
	}

	/**
	 * Valid options.
	 *
	 * @param  array $input Options to valid.
	 *
	 * @return array        Validated options.
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
				} elseif ( 'conditional_post_type' === $key ) {
					$output[ $key ] = array_filter( array_unique( array_map( 'trim', explode( ',',  $value ) ) ) );
				} elseif ( strrpos( $key, 'conditional_taxonomy_' ) !== false ) {
					$taxonomy = str_replace( 'conditional_taxonomy_', '', $key );
					$output[ $key ] = array_filter( array_unique( array_map( 'absint', array_map( 'trim', explode( ',',  $value ) ) ) ) );
				} elseif ( in_array( $key, array( 'body_post', 'body_comment' ) ) ) {
					//$output[ $key ] = wp_kses( $input[ $key ], array() );
					$output[ $key ] = $input[ $key ];
				} else {
					$output[ $key ] =  $input[ $key ];
				}
			}
		}

		return $output;
	}

	/**
	 * Add settings action link to the plugins page.
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'admin.php?page=notify-users-e-mail' ) . '">' . __( 'Settings', 'notify-users-e-mail' ) . '</a>'
			),
			$links
		);
	}
}

new Notify_Users_EMail_Admin();