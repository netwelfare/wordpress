<?php

// 公共函数类

class Baidu_common
{
    public static $baidu_options;
    public static $params;

    /**
     * 初始化常量
     */
    public static function init()
    {
        self::$params = require BAIDU_APPS_PATH . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'config.inc.php';
        self::$baidu_options = get_option('baidu_options');;
    }

    /**
     * 对象转换成熟组
     * @param $obj
     * @return array
     */
    public static function obj_to_array($obj)
    {
        $arr = array();
        foreach ($obj as $key => $value) {
            if (gettype($value) == "array" || gettype($value) == "object") {
                $arr[$key] = self::obj_to_array($value);
            } else {
                $arr[$key] = $value;
            }
        }
        return $arr;
    }

    /**
     * 数组合并链接
     * @param $arr_before
     * @param $arr_attend
     * @return mixed
     */
    public static function map_array_merge(&$arr_before, $arr_attend)
    {
        foreach ($arr_attend as $key => $value) {
            $arr_before[$key] = $value;
        }
        return $arr_before;
    }

    /**
     * 获取每日限额
     * @return mixed
     * @throws Exception
     */
    public static function get_limits()
    {
        $api = Baidu_common::$params['url'] . "limits?site=" . self::$baidu_options['site'] . "&token=" . self::$baidu_options['token'];
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_BINARYTRANSFER => true
        );

        $http = new SimpleHttpClient();
        $http->init();
        $http->method('GET')->send($options);
        return json_decode($http->result(), true);
    }

    /**
     * 提交链接
     * @param $_url
     * @throws Exception
     */
    public static function post($_url)
    {
        $api = Baidu_common::$params['url'] . '/urls?site=' . self::$baidu_options['site'] . '&token=' . self::$baidu_options['token'];
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $_url,
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        $http = new SimpleHttpClient();
        $http->init();
        $http->method('POST')->send($options);
        $result = $http->result();

        //如果打开了日志的记录
        if (isset(self::$baidu_options['log_switch']) && self::$baidu_options['log_switch'] == 1) {
            if (!$error = $http->error()) {
                $ret = self::obj_to_array(json_decode($result, true));
                if (isset($ret['error'])) {
                    self::log('error', var_export($http->result(), true));
                } else {
                    self::log('success', var_export($http->result(), true));
                }

            } else {
                //self::log('error', $error . ', ' . json_encode($http->info(), true));
            }
        }

    }

    /**
     * 处理日志的函数
     * @param $code
     * @param $content
     */
    public static function log($code, $content = null)
    {
        date_default_timezone_set('Asia/Shanghai');
        file_put_contents(self::$params['log.path'] . DIRECTORY_SEPARATOR . self::$params['log.name'], date('Y-m-d H:i:s') . "\t" . $code . ":" . $content . ".\r\n\r\n", FILE_APPEND);
    }

    /**
     * 获取插件连接
     */
    public static function get_plugin_url()
    {
        $url = trailingslashit(trailingslashit(WP_PLUGIN_URL) . plugin_basename(dirname(__FILE__)));
        $url = preg_replace('/(\/plugins\/[^\/]+?\/).+\//', '\1', $url);
        return $url;
    }

    /**
     * 获取图片的链接
     */
    public static function get_image_url($name)
    {
        return self::get_plugin_url() . 'images/' . $name;
    }
}