<?php 
//---------------------------------------------------------
//财付通即时到帐支付请求示例，商户按照此文档进行开发即可 mobantu.com
//---------------------------------------------------------

require_once('../../../../wp-config.php');
require_once ("tenpay/classes/RequestHandler.class.php");
require_once ("tenpay/tenpay_config.php");

$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$wpdb->escape($_GET['ice_money']) :0;
global $wpdb;
$subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';
$erphp_tenpay_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);	
$time      = date('Y-m-d H:i:s');
if(!empty($price))
{
	$user_Info   = wp_get_current_user();
	$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
	VALUES ('$price','$alipay_no','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
	$a=$wpdb->query($sql);
	if(!$a)
	{
		wp_die('系统发生错误，请稍后重试!');
	}
}
else
{
	wp_die('请输入您要充值的金额');
}
	

$out_trade_no = $erphp_tenpay_no;
$product_name = $subject;
$order_price = $price;
$remarkexplain = '正在使用模板兔开发的会员推广下载专业版插件';
$trade_mode='1';

$strDate = date("Ymd");
$strTime = date("His");

/* 商品价格（包含运费），以分为单位 */
$total_fee = $order_price*100;

/* 商品名称 */
$desc = "商品：".$product_name.",备注:".$remarkexplain;

/* 创建支付请求对象 */
$reqHandler = new RequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

//----------------------------------------
//设置支付参数 
//----------------------------------------
$reqHandler->setParameter("partner", $partner);
$reqHandler->setParameter("out_trade_no", $out_trade_no);
$reqHandler->setParameter("total_fee", $total_fee);  //总金额
$reqHandler->setParameter("return_url", $return_url);
$reqHandler->setParameter("notify_url", $notify_url);
$reqHandler->setParameter("body", $desc);
$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
//用户ip
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
$reqHandler->setParameter("fee_type", "1");               //币种
$reqHandler->setParameter("subject",$product_name);          //商品名称，（中介交易时必填）

//系统可选参数
$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
$reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号

//业务可选参数
$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
$reqHandler->setParameter("product_fee", $total_fee);        	  //商品费用
$reqHandler->setParameter("transport_fee", "0");      	  //物流费用
$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
$reqHandler->setParameter("time_expire", "");             //订单失效时间
$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
$reqHandler->setParameter("goods_tag", "mbt");               //商品标记
$reqHandler->setParameter("trade_mode","1");              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
$reqHandler->setParameter("transport_desc","");              //物流说明
$reqHandler->setParameter("trans_type","1");              //交易类型
$reqHandler->setParameter("agentid","");                  //平台ID
$reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
$reqHandler->setParameter("seller_id",get_option('erphpdown_tenpay_uid'));                //卖家的商户号



//请求的URL
$reqUrl = $reqHandler->getRequestURL();

//获取debug信息,建议把请求和debug信息写入日志，方便定位问题
/**/
$debugInfo = $reqHandler->getDebugInfo();
//echo "<br/>" . $reqUrl . "<br/>";
//echo "<br/>" . $debugInfo . "<br/>";


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>财付通即时到帐在线充值-powered by mobantu.com</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">财付通支付</a>
<form action="<?php echo $reqHandler->getGateUrl() ?>" method="post" target="_blank">
<?php
$params = $reqHandler->getAllParameters();
foreach($params as $k => $v) {
	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
}
?>
<input type="submit" value="财付通支付">
</form>
</body>
</html>

