<?php 
/**
 * Plugin Name: Notify Users E-Mail
 * Plugin URI: 
 * Description: Notification of new posts by e-mail to all users
 * Author: Valerio Souza
 * Author URI: http://valeriosouza.com.br
 * Version: 0.1.0
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
  
  mail($users, __('New post notification : ', 'notify_users') . get_bloginfo('name') , __('A new post has been published on', 'notify_users') . get_bloginfo('siteurl') );
    return $post_ID;
}
add_action('publish_post', 'notify_users_email');
