<?php
session_start();
header("Content-type:text/html;character=utf-8");
require_once('../../../../wp-config.php');
date_default_timezone_set('Asia/Shanghai');
if(!is_user_logged_in()){wp_die('请先登录！');}
?>
<?php
$msg='';
/********************************************/
require_once('paypal/CallerService.php');
require_once("paypal/APIError.php");

/*  www.mobantu.com  */

/* An express checkout transaction starts with a token, that
identifies to PayPal your transaction
In this example, when the script sees a token, the script
knows that the buyer has already authorized payment through
paypal.  If no token was found, the action is to send the buyer
to PayPal to first authorize payment
*/

$token = $_REQUEST['token'];

if(! isset($token))
{
	/* The servername and serverport tells PayPal where the buyer
	should be directed back to after authorizing payment.
	In this case, its the local webserver that is running this script
	Using the servername and serverport, the return URL is the first
	portion of the URL that buyers will return to after authorizing payment
	*/
	$serverName = $_SERVER['SERVER_NAME'];
	$serverPort = $_SERVER['SERVER_PORT'];
	$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);

	$currencyCodeType='USD';
	$paymentType='Sale';

	$personName        = 'username';
	$SHIPTOSTREET      = 'xiamen';
	$SHIPTOCITY        = 'xiamen';
	$SHIPTOSTATE          = 'fujian';
	$SHIPTOCOUNTRYCODE = '86';
	$SHIPTOZIP         = '361000';
	$L_NAME0           = '预付款';
	$L_AMT0            = $wpdb->escape($_REQUEST['ice_money']);
	$L_QTY0            =    1;

	/* The returnURL is the location where buyers return when a
	payment has been succesfully authorized.
	The cancelURL is the location buyers are sent to when they hit the
	cancel button during authorization of payment during the PayPal flow
	*/

	$returnURL =urlencode($url.'/paypal.php?currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType);
	$cancelURL =urlencode(get_bloginfo('url').'/wp-admin/admin.php?page=erphpdown/alipay-add-money.php.php?paymentType='.$paymentType);

	/* Construct the parameter string that describes the PayPal payment
	the varialbes were set in the web form, and the resulting string
	is stored in $nvpstr
	*/
	$itemamt = 0.00;
	$itemamt = $L_QTY0*$L_AMT0;
	$amt = $itemamt;
	$maxamt= $amt+25.00;
	$nvpstr="";

	/*
	* Setting up the Shipping address details
	*/
	$shiptoAddress = "&SHIPTONAME=$personName&SHIPTOSTREET=$SHIPTOSTREET&SHIPTOCITY=$SHIPTOCITY&SHIPTOSTATE=$SHIPTOSTATE&SHIPTOCOUNTRYCODE=$SHIPTOCOUNTRYCODE&SHIPTOZIP=$SHIPTOZIP";

	// $nvpstr="&ADDRESSOVERRIDE=1$shiptoAddress&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&CALLBACKTIMEOUT=4&SHIPPINGAMT=0.00&SHIPDISCAMT=0.00&TAXAMT=0.00&L_NUMBER0=1000&L_DESC0=Size: 8.8-oz&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType;
	$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
	$price = $wpdb->escape($price);
	$price=sprintf("%.2f",$price);
	if($price<1)
	{
		echo "<script>window.close();alert('订单金额错误')</script>";exit;
	}
	$num=date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);	
	if($price && mbtcheck() > 0)
	{
		$user_Info   = wp_get_current_user();
		$rmbPrice=round(get_option('ice_payapl_api_rmb')*$price,2);
		$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
		VALUES ('$rmbPrice','$num','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
		$a=$wpdb->query($sql);
		if(!$a)
		{
			wp_die('系统发生错误，请稍后重试!');
		}
	}
	$_SESSION["payapl_num"]=$num;
	$nvpstr="&L_AMT0=".$price."&SHIPPINGAMT=0.00&L_NAME0=网站充值预付款&L_NUMBER0=1000&L_QTY0=1&CURRENCYCODE=USD&NOSHIPPING=1&INVNUM=".$num."&AMT=".$price."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType;

	$nvpstr = $nvpHeader.$nvpstr;

	/* Make the call to PayPal to set the Express Checkout token
	If the API call succeded, then redirect the buyer to PayPal
	to begin to authorize payment.  If an error occured, show the
	resulting errors
	*/
	$resArray=hash_call("SetExpressCheckout",$nvpstr);
	$_SESSION['reshash']=$resArray;

	$ack = strtoupper($resArray["ACK"]);

	if($ack=="SUCCESS"){
		// Redirect to paypal.com here
		$token = urldecode($resArray["TOKEN"]);
		$payPalURL = PAYPAL_URL.$token;
		header("Location: ".$payPalURL);
	} 
	else
	{
		$msg=showPaypalError($resArray);
	}
}
else
{
	/* At this point, the buyer has completed in authorizing payment
	at PayPal.  The script will now call PayPal with the details
	of the authorization, incuding any shipping information of the
	buyer.  Remember, the authorization is not a completed transaction
	at this state - the buyer still needs an additional step to finalize
	the transaction
	*/

	$token =urlencode( $_REQUEST['token']);

	/* Build a second API request to PayPal, using the token as the
	ID to get the details on the payment authorization
	*/
	$nvpstr="&TOKEN=".$token;

	$nvpstr = $nvpHeader.$nvpstr;
	/* Make the API call and store the results in an array.  If the
	call was a success, show the authorization details, and provide
	an action to complete the payment.  If failed, show the error
	*/
	$resArray=hash_call("GetExpressCheckoutDetails",$nvpstr);
	$_SESSION['reshash']=$resArray;
	$ack = strtoupper($resArray["ACK"]);

	if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING')
	{
		//require_once "GetExpressCheckoutDetails.php";

		$_SESSION['token']=$_REQUEST['token'];
		$_SESSION['payer_id'] = $_REQUEST['PayerID'];

		$_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
		$_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
		$_SESSION['paymentType']=$_REQUEST['paymentType'];

		$resArray=$_SESSION['reshash'];
		$_SESSION['TotalAmount']= $resArray['AMT'] + $resArray['SHIPDISCAMT'];

		ini_set('session.bug_compat_42',0);
		ini_set('session.bug_compat_warn',0);

		$token =urlencode( $_SESSION['token']);
		$paymentAmount =urlencode ($_SESSION['TotalAmount']);
		$paymentType = urlencode($_SESSION['paymentType']);
		$currCodeType = urlencode($_SESSION['currCodeType']);
		$payerID = urlencode($_SESSION['payer_id']);
		$serverName = urlencode($_SERVER['SERVER_NAME']);

		$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;
		/* Make the call to PayPal to finalize payment
		If an error occured, show the resulting errors
		*/
		$resArray=hash_call("DoExpressCheckoutPayment",$nvpstr);

		/* Display the API response back to the browser.
		If the response from PayPal was a success, display the response parameters'
		If the response was an error, display the errors received using APIError.php.
		*/
		$ack = strtoupper($resArray["ACK"]);
		if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')
		{
			$msg=showPaypalError($resArray);
		}
		else
		{
			$num=$_SESSION["payapl_num"];
			$money=$_SESSION['TotalAmount']*get_option('ice_payapl_api_rmb');
			$money=round($money,2);
			//查询订单号
			if(erphpCheckAlipayReturnNum($num,$money) && mbtcheck()>0)
			{
				erphpSetUserOrderIsSuccess($num,$money);//更新充值订单为完成，并增加总资产
				$msg="<div>恭喜您充值成功</div>";
			}
			else
			{
				$msg="<div>订单金额检验失败!联系管理员!</div>";
			}
		}
	}
	else
	{
		$msg=showPaypalError($resArray);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo get_option(blogname); ?></title>
<style type="text/css">
<
style>html {
background: #F2F2F2;
}

body {
background: #fff;
color: #333;
font-family: "Lucida Grande", Verdana, Arial, "Bitstream Vera Sans",
sans-serif;
margin: 2em auto;
width: 700px;
padding: 1em 2em;
-moz-border-radius: 11px;
-khtml-border-radius: 11px;
-webkit-border-radius: 11px;
border-radius: 14px;
border: 1px solid #dfdfdf;
text-shadow: 1px 2px 3px rgba(0, 0, 0, 0.5);
}

a {
color: #2583ad;
text-decoration: none;
}

a:hover {
color: #d54e21;
}

h1 {
text-align: center;
border-bottom: 1px solid #dadada;
clear: both;
font: 26px Georgia, "Times New Roman", Times, serif;
margin: 5px 0 0 -4px;
padding: 0;
padding-bottom: 7px;
font-weight: bold;
}

h2 {
color: #FF0080;
font-size: 16px;
}

p {
padding-bottom: 2px;
font-size: 14px;
line-height: 18px;
}

#logo {
text-align: left;
}

.clear {
clear: both;
font-size: 0;
height: 1px;
}

li {
background: url(images/li.gif) no-repeat 0 4px !important;
padding: 0 0 0 20px;;
list-style-type: none;
}

h3 {
background: none repeat scroll 0 0 #F2F2F2;
border: 1px solid #BFBFBF;
color: #666666;
font-size: 15px;
margin: 15px 0 10px;
padding: 2px 5px;
}

.time {
float: left;
margin: 0 10px 0 0;
}

div {
font-size: 13px;
}

.header {
padding: 0;
text-align: center;
}

.list {
background: none repeat scroll 0 0 #F2F2F2;
border: 1px solid #BFBFBF;
color: #666666;
font-size: 15px;
margin: 15px 0 10px;
padding: 10px 0 0 10px;
}

.sm {
background: none repeat scroll 0 0 #F2F2F2;
border: 1px solid #BFBFBF;
color: #666666;
font-size: 15px;
margin: 15px 0 10px;
padding: 10px 0 0 10px;
}
</style>
</head>
<body>
<div class="list">
<div style="text-align: center">
<?php echo $msg?><br />
<a href="<?php echo get_bloginfo('wpurl')?>/wp-admin/admin.php?page=erphpdown/admin/alipay-my-money.php">查看我的账户余额</a>
</div>
</div>
<div style="text-align: center">
Copyright ©
<?php echo date('Y'); ?>
<a href="<?php echo get_option(siteurl); ?>/"><?php echo get_option(blogname); ?>
</a> 版权所有.
</div>
</body>
</html>
