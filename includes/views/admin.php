<?php
/**
 * Notify Users E-Mail administration view.
 *
 * @package   Notify_Users_EMail_Admin
 * @author    Valerio Souza <eu@valeriosouza.com.br>
 * @license   GPL-2.0+
 * @link      http://wordpress.org/plugins/notify-users-e-mail/
 * @copyright 2013 CodeHost
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

?>

<div class="wrap">

    <?php screen_icon( 'options-general' ); ?>
    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
    <?php echo '<p>' . __( 'This plugin notifies registered users via email of new posts, pages and comments posted.', 'notify-users-e-mail' ) . '</p>' ?>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'notify_users_email' );
            do_settings_sections( 'notify_users_email' );
            submit_button();
        ?>
    </form>

</div>
