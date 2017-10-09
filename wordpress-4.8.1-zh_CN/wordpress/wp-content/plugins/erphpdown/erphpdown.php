<?php
/*
Plugin Name: ErphpDown
Plugin URI: http://www.erphpdown.com
Description: 会员推广下载专业版：支持在线支付(支付宝、微信支付、银联、贝宝、财付通)，用户推广、提现，发布收费下载与收费内容查看，下载加密，VIP会员权限等功能的插件。
Version: 9.1.1
Author: 模板兔
Author URI: http://www.mobantu.com
*/
if ( ! defined( 'ABSPATH' ) ) exit;
date_default_timezone_set('PRC');
global $wpdb, $erphpdown_version;
$erphpdown_version = '9.1.1';
$wpdb->icealipay = $wpdb->prefix.'ice_download';
$wpdb->icemoney  = $wpdb->prefix.'ice_money';
$wpdb->iceinfo  = $wpdb->prefix.'ice_info';
$wpdb->iceget  = $wpdb->prefix.'ice_get_money';
$wpdb->vip  = $wpdb->prefix.'ice_vip';
$wpdb->aff  = $wpdb->prefix.'ice_aff';
$wpdb->down  = $wpdb->prefix.'ice_down';
define("erphpdown",plugin_dir_url( __FILE__ ));
define('ERPHPDOWN_URL', plugins_url('', __FILE__));
define('ERPHPDOWN_PATH', dirname( __FILE__ ));

add_action('admin_menu', 'mobantu_erphp_menu');
function mobantu_erphp_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page('erphpdown', 'ErphpDown', 'activate_plugins', 'erphpdown/admin/erphp-settings.php', '','dashicons-admin-network');
		add_menu_page('erphpdown2', '会员推广下载', 'read', 'erphpdown/admin/erphp-my-money.php', '','dashicons-shield');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('erphpdown/admin/erphp-settings.php', '基础设置','基础设置', 'activate_plugins', 'erphpdown/admin/erphp-settings.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '显示设置', '显示设置', 'activate_plugins', 'erphpdown/admin/erphp-front.php');
		if(plugin_check_card()){
			add_submenu_page('erphpdown/admin/erphp-settings.php', '所有充值卡','所有充值卡', 'activate_plugins', 'erphpdown-add-on-card/card-list.php');
			add_submenu_page('erphpdown/admin/erphp-settings.php', '添加充值卡','添加充值卡', 'activate_plugins', 'erphpdown-add-on-card/card-add.php');
		}
		add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP设置','VIP设置','activate_plugins', 'erphpdown/admin/erphp-vip-setting.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP订单','VIP订单','activate_plugins', 'erphpdown/admin/erphp-vip-items.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', 'VIP用户','VIP用户','activate_plugins', 'erphpdown/admin/erphp-vip-users.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '后台充值/扣钱', '后台充值/扣钱', 'activate_plugins', 'erphpdown/admin/erphp-add-money.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '后台赠送VIP', '后台赠送VIP', 'activate_plugins', 'erphpdown/admin/erphp-add-vip.php');
        add_submenu_page('erphpdown/admin/erphp-settings.php', '查询用户', '查询用户', 'activate_plugins', 'erphpdown/admin/erphp-check-users.php');
        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有资源统计', '所有资源统计', 'activate_plugins', 'erphpdown/admin/erphp-items-list.php');
        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有充值统计', '所有充值统计', 'activate_plugins', 'erphpdown/admin/erphp-chong-list.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '所有消费统计', '所有消费统计', 'activate_plugins', 'erphpdown/admin/erphp-orders-list.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '所有提现统计', '所有提现统计', 'activate_plugins', 'erphpdown/admin/erphp-tixian-list.php');
        add_submenu_page('erphpdown/admin/erphp-settings.php', '所有推广统计', '所有推广统计', 'activate_plugins', 'erphpdown/admin/erphp-reference-all.php');
        add_submenu_page('erphpdown/admin/erphp-settings.php', '清理数据表', '清理数据表', 'activate_plugins', 'erphpdown/admin/erphp-clear.php');
		add_submenu_page('erphpdown/admin/erphp-settings.php', '检查更新', '检查更新', 'activate_plugins', 'erphpdown/admin/update.php');
		
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '我的资产', '我的资产', 'read', 'erphpdown/admin/erphp-my-money.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '在线充值', '在线充值', 'read', 'erphpdown/admin/erphp-add-money-online.php');
		if(plugin_check_cred() && get_option('erphp_mycred') == 'yes'){
			add_submenu_page('erphpdown/admin/erphp-my-money.php', '积分兑换','积分兑换', 'read', 'erphpdown-add-on-mycred/erphp-to-mycred.php');
		}
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '充值记录', '充值记录', 'read', 'erphpdown/admin/erphp-add-money-list.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '升级VIP', '升级VIP', 'read', 'erphpdown/admin/erphp-update-vip.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '消费清单', '消费清单', 'read', 'erphpdown/admin/erphp-get-items.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '销售订单', '销售订单', 'edit_posts', 'erphpdown/admin/erphp-items.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '提现列表', '提现列表', 'read', 'erphpdown/admin/erphp-money-list.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '申请提现', '申请提现', 'read', 'erphpdown/admin/erphp-money.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广注册', '推广注册', 'read', 'erphpdown/admin/erphp-reference.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广下载', '推广下载', 'read', 'erphpdown/admin/erphp-reference-list.php');
		add_submenu_page('erphpdown/admin/erphp-my-money.php', '推广VIP', '推广VIP', 'read', 'erphpdown/admin/erphp-reference-vip-list.php');
		
    }
    
}

require_once ERPHPDOWN_PATH . '/includes/mobantu.php';
require_once ERPHPDOWN_PATH . '/includes/metabox.php';
require_once ERPHPDOWN_PATH . '/includes/shortcode.php';
require_once ERPHPDOWN_PATH . '/includes/show.php';//前端购买显示核心文件
require_once ERPHPDOWN_PATH . '/includes/functions.erphp.php';
require_once ERPHPDOWN_PATH . '/includes/class.erphp.php';
require_once ERPHPDOWN_PATH . '/diy.php';

register_activation_hook(__FILE__, 'erphpdown_install');
//register_deactivation_hook(__FILE__, 'erphpdown_uninstall');