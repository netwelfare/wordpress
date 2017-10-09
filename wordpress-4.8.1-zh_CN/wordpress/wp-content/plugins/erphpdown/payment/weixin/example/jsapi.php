<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once('../../../../../../wp-config.php');
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
//require_once 'log.php';

$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
$price = $wpdb->escape($price);
if($price && is_user_logged_in()){


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
		}else{
			$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$out_trade_no."'");
		}
	}else{
		wp_die('请输入您要充值的金额');
	}

	//初始化日志
	//$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
	//$log = Log::Init($logHandler, 15);

	//打印输出数组信息
	function printf_info($data)
	{
	    foreach($data as $key=>$value){
	        echo "<font color='#00ff55;'>$key</font> : $value <br/>";
	    }
	}

	//①、获取用户openid
	$tools = new JsApiPay();
	$openId = $tools->GetOpenid();

	//②、统一下单
	$input = new WxPayUnifiedOrder();
	$input->SetBody($subject);
	$input->SetAttach("ERPHP");
	$input->SetOut_trade_no($out_trade_no);
	$input->SetTotal_fee($price*100);
	$input->SetTime_start(date("YmdHis"));
	$input->SetTime_expire(date("YmdHis", time() + 600));
	$input->SetGoods_tag("MBT");
	$input->SetNotify_url(constant("erphpdown").'payment/weixin/example/notify.php');
	$input->SetTrade_type("JSAPI");
	$input->SetOpenid($openId);
	$order = WxPayApi::unifiedOrder($input);
	//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
	//printf_info($order);
	$jsApiParameters = $tools->GetJsApiParameters($order);

	//获取共享收货地址js函数参数
	$editAddress = $tools->GetEditAddressParameters();

	//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
	/**
	 * 注意：
	 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
	 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
	 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
	 */
	?>

	<html>
	<head>
	    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
	    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
	    <title>微信支付</title>
	    <script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+res.err_desc+res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok" ){
						alert("支付成功！");
						/*window.location.href = "";*/
					}
				}
			);
		}

		function callpay()
		{
			if (typeof(WeixinJSBridge) == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
		</script>
		<script type="text/javascript">
		//获取共享地址
		function editAddress()
		{
			WeixinJSBridge.invoke(
				'editAddress',
				<?php echo $editAddress; ?>,
				function(res){
					var value1 = res.proviceFirstStageName;
					var value2 = res.addressCitySecondStageName;
					var value3 = res.addressCountiesThirdStageName;
					var value4 = res.addressDetailInfo;
					var tel = res.telNumber;
					
					alert(value1 + value2 + value3 + value4 + ":" + tel);
				}
			);
		}
		
		window.onload = function(){
			if (typeof(WeixinJSBridge) == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', editAddress, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', editAddress); 
			        document.attachEvent('onWeixinJSBridgeReady', editAddress);
			    }
			}else{
				editAddress();
			}
		};
		
		</script>
	</head>
	<body>
	    <br/>
	    <div align="center"><font color="#9ACD32"><b>订单支付金额为<span style="color:#f00;font-size:50px"><?php echo $price;?></span>元</b></font></div><br/><br/>
		<div align="center">
			<button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="javascript:callpay();return false;" >立即支付</button>
		</div>
	</body>
	</html>
<?php }?>