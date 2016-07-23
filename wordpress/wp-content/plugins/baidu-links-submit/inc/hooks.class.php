<?php

class Hooks
{
    public static function init()
    {
        Baidu_common::init();

        //设置提醒效果，如果没有填写site和token就显示该消息
        self::baidu_admin_warnings(Baidu_common::$baidu_options);

        //添加设置页面入口连接
        add_filter('plugin_action_links', array(__CLASS__, 'baidu_plugin_action_links'), 10, 2);

        // 注册初始化时候调用的函数
        add_action('init', array(__CLASS__, 'baidu_init'), 1000, 0);

        add_action('publish_post', array(__CLASS__, 'publishPost'), 9999, 1);
        add_action('publish_page', array(__CLASS__, 'publishPost'), 9999, 1);

    }

    //初始化时候调用的函数
    public static function baidu_init()
    {
        add_action('admin_menu', array('Hooks', 'registerAdminPage'));
    }

    //添加设置页面入口连接
    public static function baidu_plugin_action_links($links, $file)
    {
        if ($file == BAIDU_BASEFOLDER . '/baidu-links-submit.php') {
            $links[] = '<a href="admin.php?page=baidu_admin_links">' . __('Settings') . '</a>';
        }
        return $links;
    }

    //注册admin pages菜单
    public static function registerAdminPage()
    {
        add_menu_page(
            'Baidu Links Submit',
            'Baidu Links Submit',
            'manage_options',
            'baidu_admin_links',
            array(__CLASS__, 'showAdminPage')
        );
        add_submenu_page(
            'baidu_admin_links',
            'Baidu Links Submit - Manage',
            __('Settings'),
            'manage_options',
            'baidu_admin_links',
            array(__CLASS__, 'showAdminPage')

        );
        add_submenu_page(
            'baidu_admin_links',
            'Baidu Links Submit - About',
            __('Help'),
            'manage_options',
            'baidu_about',
            array(__CLASS__, 'showAdminAboutPage')
        );
    }

    //显示admin pages,更新参数
    public static function showAdminPage()
    {
        $options = array();
        if (isset($_POST['site'])) {
            $options['site'] = trim(stripslashes($_POST['site']));
        }
        if (isset($_POST['token'])) {
            $options['token'] = trim(stripslashes($_POST['token']));
        }
        if (isset($_POST['log_switch'])) {
            $options['log_switch'] = trim(stripslashes($_POST['log_switch']));
        }

        if ($options !== array()) {
            update_option('baidu_options', $options);
            Baidu_common::$baidu_options = $options;
            echo '<div class="updated"><p><strong>设置已保存！</strong></p></div>';
        }

        $output = Baidu_common::get_limits();
        require BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'show-admin-page.php';
    }


    //显示关于的相关参数 （待完善）
    public static function showAdminAboutPage()
    {
        require BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'show-about-page.php';
    }

    //设置提醒效果，如果没有填写site和token就显示该消息
    public static function baidu_admin_warnings($baidu_options)
    {
        if (!($baidu_options['token'] && $baidu_options['site'])) {
            function baidu_warning()
            {
                echo "<div id='BaiduSubmit-warning' class='updated fade'><p><strong>" . __('Baidu Links Submit is almost ready.') . "</strong> " . sprintf(__('You must enter your Baidu Token for it to work.'), "options-general.php?page=" . BAIDU_BASEFOLDER . "/main.php") . "</p></div>";
            }

            add_action('admin_notices', 'baidu_warning');
            return;
        }
    }

    //注册插件发布时候的动作,数据提交的动作(待完善的部分)
    public static function publishPost($post_id)
    {
        $_url = get_permalink(get_post($post_id));
        Baidu_common::post($_url);
    }

}
