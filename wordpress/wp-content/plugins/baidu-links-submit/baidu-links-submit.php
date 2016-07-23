<?php
/**
 * 入口文件
 *
 * Plugin Name: Baidu Links Submit
 * Plugin URI: http://zhanzhang.baidu.com/
 * Version: v1.0
 * Author: Baidu Inc.
 * Description: 安装了Baidu Links Submit工具后，确保您的网站以快速的提交方式，以保证新链接可以及时被百度收录。
 * Text Domain: Baidu Links Submit工具是基于百度站长平台ping2.0的链接提交接口，为广大使用wordpress建站的站长朋友设计和开发的一款wordpress插件。本插件只需您简单的配置下您的site和token即可使用。确保您的网站以快速的提交方式，以保证新链接可以及时被百度收录。
 * Domain Path: /lang/
 */

//  插件对应的 url
define('BAIDU_BASEFOLDER', plugin_basename(dirname(__FILE__)));
//  应用目录
define('BAIDU_APPS_PATH',  plugin_dir_path( __FILE__ ));


require_once BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'hooks.class.php';
require_once BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'common.class.php';
require_once BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'simple-http-client.class.php';

Hooks::init();




