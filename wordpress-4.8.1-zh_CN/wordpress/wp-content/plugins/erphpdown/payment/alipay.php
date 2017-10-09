<?php
require_once('../../../../wp-config.php');
require_once("alipay/alipay.config.php");
require_once("alipay/lib/alipay_submit.class.php");
if(!is_user_logged_in()){wp_die('请先登录！');}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>正在前往支付宝...</title>
    <style>input{display:none}</style>
</head>

<?php
$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);
if($price){
	
	global $wpdb;
	$subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';  
	$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);		
	$time = date('Y-m-d H:i:s');
	if(!empty($price)){
		$user_Info   = wp_get_current_user();
		$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
		VALUES ('$price','$out_trade_no','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
		$a=$wpdb->query($sql);
		if(!$a){
			wp_die('系统发生错误，请稍后重试!');
		}
	}else{
		wp_die('请输入您要充值的金额');
	}

	/**************************请求参数**************************/

        //支付类型
        $payment_type = "1";
        //必填，不能修改
        //服务器异步通知页面路径
        $notify_url = constant("erphpdown").'payment/alipay/notify_url.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $return_url = constant("erphpdown").'payment/alipay/return_url.php';
        //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

        //商品数量
        $quantity = "1";
        //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
        //物流费用
        $logistics_fee = "0.00";
        //必填，即运费
        //物流类型
        $logistics_type = "EXPRESS";
        //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
        //物流支付方式
        $logistics_payment = "SELLER_PAY";
        //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
        //订单描述

        $body = '';
        //商品展示地址
        $show_url = '';
        //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html

        $receive_name= "张三"; //收货人姓名，如：张三
		$receive_address= "XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号"; //收货人地址，如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
		$receive_zip= "123456"; //收货人邮编，如：123456
		$receive_phone= "0571-81234567"; //收货人电话号码，如：0571-81234567
		$receive_mobile= "13312341234"; //收货人手机号码，如：13312341234


	/************************************************************/
	
	//构造要请求的参数数组，无需改动
	$parameter = array(
			"service" => get_option('erphpdown_alipay_type')?get_option('erphpdown_alipay_type'):"create_partner_trade_by_buyer",
			"partner" => trim($alipay_config['partner']),
			"seller_email" => trim($alipay_config['seller_email']),
			"payment_type"	=> $payment_type,
			"notify_url"	=> $notify_url,
			"return_url"	=> $return_url,
			"out_trade_no"	=> $out_trade_no,
			"subject"	=> $subject,
			"price"	=> $price,
			"quantity"	=> $quantity,
			"logistics_fee"	=> $logistics_fee,
			"logistics_type"	=> $logistics_type,
			"logistics_payment"	=> $logistics_payment,
			"body"	=> $body,
			"show_url"	=> $show_url,
			"receive_name"	=> $receive_name,
			"receive_address"	=> $receive_address,
			"receive_zip"	=> $receive_zip,
			"receive_phone"	=> $receive_phone,
			"receive_mobile"	=> $receive_mobile,
			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
	);
	
	//建立请求
	$alipaySubmit = new AlipaySubmit($alipay_config);
	$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
	echo $html_text;

}

?>
</body>
</html>