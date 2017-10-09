<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);
require_once('../../../../../../wp-config.php');
require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
//require_once 'log.php';

//初始化日志
//$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			global $wpdb;
			$total_fee=$result["total_fee"]*0.01;
			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num=".$result["out_trade_no"]);
			if($money_info){
				if(!$money_info->ice_success){
					addUserMoney($money_info->ice_user_id, $total_fee*get_option('ice_proportion_alipay'));
				}
				$wpdb->query("UPDATE $wpdb->icemoney SET ice_money = '".$total_fee*get_option('ice_proportion_alipay')."', ice_alipay = '".$result["openid"]."',ice_success=1, ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '".$result["out_trade_no"]."'");
			}
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		//Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

//Log::DEBUG("begin notify erphp");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
