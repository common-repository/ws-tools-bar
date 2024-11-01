<?php
/*
Plugin Name: WS Tools Bar
Plugin URI: http://icyleaf.com/projects/ws-tools-bar/
Description: 允许管理员在前台直接访问管理的快速工具条。(This plugin adds a Quick toolbar on the top of blog.)
Version: 1.3
Author: icyleaf
Author URI: http://icyleaf.com
*/

load_plugin_textdomain('wstb', $path = 'wp-content/plugins/ws-tools-bar');

add_action('init', 'wstb_init');

function wstb_init() {
	get_currentuserinfo();
	global $userdata, $user_login, $user_identity;
	add_filter('wp_head', 'wstb_head');
	if($user_login) {
		add_filter('wp_footer', 'wstb_login');
	}else{
		add_filter('wp_footer', 'wstb_nologin');
	}
}

function wstb_head() {
	echo "\n<!--[CSS for WS ToolsBar]-->\n\t";
	echo '<link type="text/css" rel="stylesheet" href="' . get_settings("siteurl") . '/wp-content/plugins/ws-tools-bar/style.css" />';
	echo "\n<!--[/CSS for WS ToolsBar]-->\n";
	echo '<!--WS QuickBar Function-->
		<script language="javascript" type="text/javascript">
		function hidetoolbar() {
		window.parent.document.getElementById("toolbarframe").style.display="none";
		}
		function showtoolbar() {
		document.getElementById("toolbarframe").style.display = "block";
		}
		</script>';
	echo "\n";
}

function wstb_login() {
echo '<!--ToolbarMain-->
	<a href=""><div id="toolbarshowbtn" onMouseMove="showtoolbar()"></div></a>
	<div id="toolbarframe">
	<!--ToolbarContent-->
	<div id="toolbar">
	<div class="right">
	<ul>
    <li><a href="javascript:;" onclick="hidetoolbar();" class="close" title="'.__('Hide Tools Bar', 'wstb').'"></a></li>';
    check_version();
echo '</ul>
	</div>

	<div class="menus">
	<ul>
		<li class="noborder">' . __('Tools Bar', 'wstb') . '</li>';
		if (current_user_can('manage_options')) {
    			echo '<li><a href="'.get_option('siteurl').'/wp-admin/options-general.php" class="options" title="'.__('Hide Tools Bar', 'wstb').'">'.__('Options','wstb').'</a></li>';
		}
		echo '<li><a href="'.get_option('siteurl').'/wp-admin/edit.php" class="manage" title="'.__('Manage Post/Page, etc...', 'wstb').'">' . __('Manage', 'wstb') . '</a></li>
			<li><a href="'.get_option('siteurl').'/wp-admin/post-new.php" class="post">' . __('WritePost','wstb') . '</a></li>
			<li><a href="'.get_option('siteurl').'/wp-admin/themes.php" class="themes">' . __('Themes', 'wstb') . '</a></li>';
		if (current_user_can('activate_plugins')) {
			$update_plugins = get_option( 'update_plugins' );
			$update_count = count( $update_plugins->response );
			if($update_count=="0"){
			echo '<li><a href="'.get_option('siteurl').'/wp-admin/plugins.php" class="plugins">'. __('Plugins', 'wstb') . '</a></li>';
			}else{
			echo '<li><a href="'.get_option('siteurl').'/wp-admin/plugins.php" class="plugins"><span class="plugin-count">'. __('Plugins', 'wstb').'('.number_format_i18n($update_count).')</span></a></li>';
			}
		}
		if (current_user_can('manage_links')) {
			echo'<li><a href="'.get_option('siteurl').'/wp-admin/link-manager.php" class="links">' . __('Blogroll', 'wstb') . '</a></li>';
		}
		if(current_user_can('moderate_comments')) {
			global $wpdb;
			$awaiting_mod = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '0'");
			if($awaiting_mod) {
    			echo '<li><a href="'.get_option('siteurl').'/wp-admin/moderation.php" class="moderation">'.sprintf(__("(%s) Awaiting Moderation",'wstb'), $awaiting_mod).'</a></li>';
			}
		}
		echo '<li><a href="'.get_option('siteurl').'/wp-login.php?action=logout" class="logout">' . __('Logout', 'wstb') . '</a></li>';
echo '</ul></div>
	</div></div>';
echo "\n";
}

function wstb_nologin() {
echo '<!--ToolbarMain-->
	<a href=""><div id="toolbarshowbtn" onMouseMove="showtoolbar()"></div></a>
	<div id="toolbarframe">
	<!--ToolbarContent-->
	<div id="toolbar">
	<div class="right">
	<ul><li><a href="javascript:;" onclick="hidetoolbar();" class="close" title="'.__('Hide Tools Bar', 'wstb').'"></a></li></ul>
	</div>

	<div class="menus">
	<ul>
		<form name="loginform" id="loginform" action="'.get_option('siteurl').'/wp-login.php" method="post">
			<li> '. __('UserName', 'wstb') . ' : <input type="text" id="user_login" name="log" tabindex="1"/>
			' . __('PassWord', 'wstb') . ' : <input type="password" id="user_pass" name="pwd" tabindex="2"/>
			<label for="chksave"><input type="checkbox" id="rememberme" name="rememberme" value="forever" tabindex="3" />' . __('Remeber me', 'wstb') . '</label></li>
			<li><button type="submit" id="wp-submit" name="wp-submit" value="true" tabindex="4">' . __('Login', 'wstb') . ' </button></li>
			</form>
		</ul>';
		if (get_option('users_can_register')){
			echo '<li><a href="'.get_option('siteurl').'/wp-login.php?action=register" class="reg" title="'.__('Register User', 'wstb').'">' . __('Registry', 'wstb') . '</a></li>';
		}
	echo '<li><a href="http://icyleaf.com/projects/ws-tools-bar/" class="help" title="'.__('Konw more about this plug-in', 'wstb').'" target="_blank">' . __('Help', 'wstb') . '</a></li>
	</div>
	</div>
	</div>';
echo "\n";
}

function check_version(){
$cur = get_option( 'update_core' );
if( $cur->version_checked != $cur->updates[0]->current )
  //echo '<li><a href="'.$cur->updates[0]->package .'"  class="version">'.sprintf(__('WordPress %1$s is available!', 'wstb'), $cur->updates[0]->current) . '</a></li>';
  echo '<li><a href="'.get_settings("siteurl").'/wp-admin/update-core.php" class="version">'.sprintf(__('WordPress %1$s is available!', 'wstb'), $cur->updates[0]->current) . '</a></li>';
}
?>
