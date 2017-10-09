<META http-equiv=Content-Type content="text/html; charset=utf-8">
<?php

//---------------------------------------------------------
//财付通即时到帐支付页面回调示例，商户按照此文档进行开发即可
//---------------------------------------------------------
require_once('../../../../../wp-config.php');
require_once ("./classes/ResponseHandler.class.php");
require_once ("./classes/function.php");
require_once ("./tenpay_config.php");

log_result("进入前台回调页面");


/* 创建支付应答对象 */
$resHandler = new ResponseHandler();
$resHandler->setKey($key);

//判断签名
if($resHandler->isTenpaySign()) {
	
	//通知id
	$notify_id = $resHandler->getParameter("notify_id");
	//商户订单号
	$out_trade_no = $resHandler->getParameter("out_trade_no");
	//财付通订单号
	$transaction_id = $resHandler->getParameter("transaction_id");
	//金额,以分为单位
	$total_fee = $resHandler->getParameter("total_fee");
	//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
	$discount = $resHandler->getParameter("discount");
	//支付结果
	$trade_state = $resHandler->getParameter("trade_state");
	//交易模式,1即时到账
	$trade_mode = $resHandler->getParameter("trade_mode");
	
	global $wpdb;
	
	if("1" == $trade_mode ) {
		if( "0" == $trade_state){ 
		
			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num=".$out_trade_no);
			if($money_info)
			{
				if(!$money_info->ice_success)
				{
					$user_info=wp_get_current_user();
					addUserMoney($user_info->ID, $total_fee*get_option(ice_proportion_alipay));
				}
				$wpdb->query("UPDATE $wpdb->icealipay SET ice_success=1 WHERE ice_num = '$out_trade_no'");
				$wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option(ice_proportion_alipay)."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '$out_trade_no'");
			}
					
			echo "success";
	
		} else {
			//当做不成功处理
			echo "<br/>" . "即时到帐支付失败" . "<br/>";
		}
	}elseif( "2" == $trade_mode  ) {
		if( "0" == $trade_state) {
		
		
			
			echo "<br/>" . "中介担保支付成功" . "<br/>";
		
		} else {
			//当做不成功处理
			echo "<br/>" . "中介担保支付失败" . "<br/>";
		}
	}
	
} else {
	echo "<br/>" . "认证签名失败" . "<br/>";
	echo $resHandler->getDebugInfo() . "<br>";
}

?>