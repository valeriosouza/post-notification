<?php 
/**
 * Plugin Name: Notify Users E-Mail
 * Plugin URI: http://wordpress.org/plugins/notify-users-e-mail/
 * Description: Notification of new posts by e-mail to all users
 * Author: Valerio Souza
 * Author URI: http://valeriosouza.com.br
 * Version: 0.1.1
 * License: GPLv2 or later
 * Text Domain: notify_users
 * Domain Path: /lang/
 */

load_plugin_textdomain( 'notify_users', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

function notify_users_email($post_ID)  {
    //global $wpdb;
    //$usersarray = $wpdb->get_results("SELECT user_email FROM $wpdb->users;");
  $wp_user_search = new WP_User_Query( array( 'fields' => array('user_email') ) );
  $usersarray = $wp_user_search->get_results();
  $arrUsers = array ();
  for ($arr = $usersarray, $mU = count ($arr), $iU = 0; $iU < $mU; $iU++) {
    $arrUsers[] = $arr[$iU]->user_email;
  } // for
  $users = implode(",", $arrUsers);

  $headers = 'Bcc: '.$users.''.'\r\n';

  $admin_mail = get_option( 'admin_email' );
  
  mail($admin_mail, __('New post notification : ', 'notify_users') . get_bloginfo('name') , __('A new post has been published on ', 'notify_users') . get_bloginfo('siteurl'), $headers );
    return $post_ID;
}
add_action('publish_post', 'notify_users_email');


add_action( 'admin_menu', 'notify_users_email_options' );
 
function notify_users_email_options() {
  // criamos a pagina de opções com esta função
  add_options_page( __('Notify Users E-Mail','notify_users'), __('Notify Users E-Mail Options','notify_users'), 'manage_options', 'notify_users', 'notify_users_email_options_content' );
 
}
 
// Interior da página de Opções.
// Esta função imprime o conteúdo da página no ecrã.
// O HTML necessário encontra-se já escrito.
function notify_users_email_options_content() {
?>
<div class="wrap">
  <?php screen_icon(); ?>
  <h2><?php __('Notify Users E-Mail Options','notify_users') ?></h2>
  <form action="options.php" method="post">
    <p><?php __('Welcome options Plugin Notifying users by e-mail. <br>This plugin will send an email to all registered users, every time a new post is published.','notify_users');?></p>
  </form>
</div>
<?php
}