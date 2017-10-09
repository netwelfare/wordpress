<?php
/* *
by mobantu
 */
require_once('../../../../../wp-config.php');

$erphpdown_zfbjk_uid  = get_option('erphpdown_zfbjk_uid');
$erphpdown_zfbjk_key  = get_option('erphpdown_zfbjk_key');

$Gateway = $_POST['Gateway'];
$tradeNo = $_POST['tradeNo']; //充值订单的支付宝交易号
$Money = $_POST['Money'];
$title = $_POST['title']; //订单号ID
$memo = $_POST['memo']; //软件中设置的“附加信息”，可用于标记多个不同的网站
$alipay_account = $_POST['alipay_account'];
$tenpay_account = $_POST['tenpay_account'];
$Sign = $_POST['Sign']; //MD5(商户ID号+商户密钥+tradeNo+Money+title+memo)，字符串组合后做32位MD5加密
$Paytime = $_POST['Paytime'];

if($Sign == strtoupper(md5($erphpdown_zfbjk_uid.$erphpdown_zfbjk_key.$tradeNo.$Money.$title.$memo)) && $Money > 0) {//验证成功
	
	global $wpdb;
	$total_fee=$Money;
	$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_id='".$title."'");
	if($money_info){
		if(!$money_info->ice_success){
			addUserMoney($money_info->ice_user_id, $total_fee*get_option('ice_proportion_alipay'));
		}
		$wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option('ice_proportion_alipay')."', ice_alipay = '".$tradeNo."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_id = '".$title."'");
		echo 'Success';
	}else{
		echo 'IncorrectOrder';
	}
	
   
}else {
	echo 'Fail';
}


?>