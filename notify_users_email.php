<?php 
/**
 * Plugin Name: Notify Users E-Mail
 * Plugin URI: http://wordpress.org/plugins/notify-users-e-mail/
 * Description: Notification of new posts by e-mail to all users
 * Author: Valerio Souza, CodeHost
 * Author URI: http://valeriosouza.com.br
 * Version: 1.0.4
 * License: GPLv2 or later
 * Text Domain: notify_users
 * Domain Path: /lang/
 */

load_plugin_textdomain( 'notify_users', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

function notify_users_email($post_ID)  {
  $wp_user_search = new WP_User_Query( array( 'fields' => array('user_email') ) );
  $usersarray = $wp_user_search->get_results();
  $arrUsers = array ();
  for ($arr = $usersarray, $mU = count ($arr), $iU = 0; $iU < $mU; $iU++) {
    $arrUsers[] = $arr[$iU]->user_email;
  } 
  $users = implode(",", $arrUsers);

  $headers = 'Bcc: '.$users.''.'\r\n';
  /*$headers = 'Bcc: '.get_option( 'admin_email' ).''.'\r\n';*/

  $admin_mail = get_option('notify_users_mail');
  
  wp_mail($admin_mail, get_option('notify_users_subject').' | '. get_bloginfo('name') , get_option('notify_users_body'), $headers );
    return $post_ID;
}
add_action('publish_post', 'notify_users_email');


add_action('admin_menu', 'add_global_custom_options');

function add_global_custom_options()
{
    add_options_page('Opções de Notificações por E-mail', 'Opções de Notificações por E-mail', 'manage_options', 'notify_users','notify_users_options');
}

function notify_users_options()
{
?>
    <div class="wrap">
        <h2>Página de Opções</h2>
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options') ?>
            <p><strong>Endereço de E-mail</strong><br />
                <input type="text" name="notify_users_mail" size="150" value="<?php echo get_option('notify_users_mail'); ?>" />
            </p>
            <p><strong>Texto do Assunto do E-mail</strong><br />
                <input type="text" name="notify_users_subject" size="45" value="<?php echo get_option('notify_users_subject'); ?>" />
            <p><strong>Texto do Corpo do E-mail</strong><br />
                <input type="text" name="notify_users_body" size="150" value="<?php echo get_option('notify_users_body'); ?>" />
            </p>
            <p><input type="submit" name="Submit" value="Salvar" /></p>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="notify_users_mail,notify_users_body,notify_users_subject" />
        </form>
    </div>
<?php
}


/*Aviso*/

function notify_users_showMessage($message, $errormsg = false)
{
    if ($errormsg) {
        echo '<div id="message" class="error">';
    }
    else {
        echo '<div id="message" class="updated fade">';
    }
    echo "<p><strong>$message</strong></p></div>";
} 
 
function notify_users_showAlertMessage()
{
  $screen = get_current_screen();
  $post_type = $screen->id;
  if ( 'post' == $post_type ) :
    notify_users_showMessage(__('Warning: The Plugin is active of Notification. All published posts will be sent to all users.','notify_users'), true);
    endif;
}
add_action('admin_notices', 'notify_users_showAlertMessage');