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

<div class="wrap about-wrap">

    <?php screen_icon( 'options-general' ); ?>
    <h1><?php echo __( 'Welcome to', 'notify-users-e-mail' ); ?> <?php echo esc_html( get_admin_page_title() ); ?></h1>
    <div class="about-text"><?php echo __( 'This plugin notifies registered users via email of new posts, comments, and any post type posted.', 'notify-users-e-mail' ); ?></div>
    <hr>
    <div class="changelog">
        <div class="return-to-dashboard">
                <a href="<?php get_admin_url(); ?>/wp-admin/admin.php?page=notify-users-e-mail-settings"><?php echo __( 'Go to Configuration Plugin', 'notify-users-e-mail' ); ?></a>
        </div>
        <div class="feature-section col two-col">
            <div class="col-1">
                <h3><?php echo __( "What's new in this release.", 'notify-users-e-mail' ); ?></h3>
                <p><?php echo __( 'Now it is possible to choose which post type and taxonomy which emails are sent.', 'notify-users-e-mail' ); ?></p>
            </div>
            <div class="col-2 last-feature">
                <h3><?php echo __( 'More beautiful with HTML content!', 'notify-users-e-mail' ); ?></h3>
                <p><?php echo __( 'New HTML editors allow you to customize the contents of the email, and even embed images, videos and what more do you want to. ', 'notify-users-e-mail' ); ?></p>
            </div>
        </div>
        <div class="feature-section col two-col">
            <div class="col-1">
                <h3><?php echo __( 'Contents of the post directly in the body of the email!', 'notify-users-e-mail' ); ?></h3>
                <p><?php echo __( 'It is now possible to send the entire contents of the post directly in your email. This is amazing.', 'notify-users-e-mail' ); ?></p>
            </div>
            <div class="col-2 last-feature">
                <h3><?php echo __( 'Keep up with the news', 'notify-users-e-mail' ); ?></h3>
                <p><?php echo __( 'We are preparing many new features, stay tuned by subscribing to our email Marketing. We promise not to send Spam.', 'notify-users-e-mail' ); ?></p>
                <!-- Begin MailChimp Signup Form -->
                    <style type="text/css">
                    /*#mc_embed_signup{background:#fff; clear:left; font:14px Helvetica,Arial,sans-serif; }*/
                    /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                    We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
                    </style>
                    <div id="mc_embed_signup">
                    <form action="//valeriosouza.us5.list-manage.com/subscribe/post?u=ca9d9abf6c437e15a8c81ab8d&amp;id=ea36b8afd2" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">

                    <div class="mc-field-group">
                    <label for="mce-EMAIL"><?php echo __( 'Email Address', 'notify-users-e-mail' ); ?></label>
                    <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                    </div>
                    <div id="mce-responses" class="clear">
                    <div class="response" id="mce-error-response" style="display:none"></div>
                    <div class="response" id="mce-success-response" style="display:none"></div>
                    </div><!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_ca9d9abf6c437e15a8c81ab8d_ea36b8afd2" tabindex="-1" value=""></div>
                    <div class="clear"><input type="submit" value="<?php echo __( 'Subscribe', 'notify-users-e-mail' ); ?>" name="subscribe" id="mc-embedded-subscribe" class="button button-primary"></div>
                    </div>
                    </form>
                    </div>

                <!--End mc_embed_signup-->
            </div>
        </div>
        <div class="return-to-dashboard">
                <a href="<?php get_admin_url(); ?>/wp-admin/admin.php?page=notify-users-e-mail-settings"><?php echo __( 'Go to Configuration Plugin', 'notify-users-e-mail' ); ?></a>
        </div>
    </div>
</div>
