<?php 
/**
 * 调用支付
 * 
 * 实现微信、支付宝支付的接口
 * @date 2017年3月13日
 * @copyright 重庆迅虎网络有限公司
 */
require_once('../../../../wp-config.php');
require_once 'xhpay/api.php';
if(!is_user_logged_in()){wp_die('请先登录！');}

$trade_order_id = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);

$subject = 'order['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';  


if($price > 0){
    $user_Info   = wp_get_current_user();
    $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
    VALUES ('$price','$trade_order_id','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
    $a=$wpdb->query($sql);
    if(!$a){
        wp_die('系统发生错误，请稍后重试!');
    }
}else{
    wp_die('请输入您要充值的金额');
}

$payment = 'alipay';

$mob = 'N';
if(wp_is_mobile()){
    $mob = 'Y';
}

$data=array(
    'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
    'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
    'plugins'   => 'erphpdown-xhpay',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+ 
    'appid'     => get_option('erphpdown_xhpay_appid'), //必须的，APPID
    'trade_order_id'=> $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+ 
    'payment'   => $payment,//必须的，支付接口标识：wechat(民生银行微信接口)|alipay(民生银行支付宝接口)
    'is_app'    => $mob, //必须的，Y|N 是否是移动端
    'total_fee' => $price,//必须的，支付金额，人民币
    'title'     => $subject, //必须的，订单标题，长度32或以内
    'description'=> '',//可选，订单描述，长度5000或以内
    'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
    'notify_url'=>  constant("erphpdown").'payment/xhpay/notify.php', //必须的，支付成功异步回调接口
    'return_url'=> get_option('erphp_url_front_success'),//必须的，支付成功后的跳转地址
    'callback_url'=>get_option('erphp_url_front_success'),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
    'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
);

$hashkey          = get_option('erphpdown_xhpay_appsecret');//appsecret
$data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);
$url              = 'https://pay.wordpressopen.com/payment/do.html';

try {
    $response     = XH_Payment_Api::http_post($url, json_encode($data));
    $result       = $response?json_decode($response,true):null;
    if(!$result){
        throw new Exception('Internal server error',500);
    }
     
    $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
    if(!isset( $result['hash'])|| $hash!=$result['hash']){
        throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
    }

    if($result['errcode']!=0){
        throw new Exception($result['errmsg'],$result['errcode']);
    }
    
    /**
     * 支付回调数据
     * @var array
     *  array(
     *      order_id,//支付系统订单ID
     *      url,//支付跳转地址
     *  )
     */
    $pay_url =$result['url'];
    header("Location: $pay_url");
    exit;
} catch (Exception $e) {
    echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
    //TODO:处理支付调用异常的情况
}
?>