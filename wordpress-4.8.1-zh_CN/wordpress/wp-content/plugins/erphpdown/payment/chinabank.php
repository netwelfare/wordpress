<?php
header("Content-type:text/html;character=utf-8");
require_once('../../../../wp-config.php');
if(!is_user_logged_in()){wp_die('请先登录！');}
//从数据库中查询商品价格
$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$wpdb->escape($_GET['ice_money']) :0;
$subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';  //订单名称，显示在支付宝收银台里的"商品名称"里，显示在支付宝的交易管理的"商品名称"的列表里。
/*以下参数是需要通过下单时的订单数据传入进来获得*/
/////////////////////////////////////////////////www.mobantu.com   82708210@qq.com
//必填参数
$alipay_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);	
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

			$v_mid = get_option('ice_china_bank_uid');								    // 商户号，这里为测试商户号1001，替换为自己的商户号(老版商户号为4位或5位,新版为8位)即可
			$v_url = constant("erphpdown").'payment/chinabank/receive.php';	// 请填写返回url,地址应为绝对路径,带有http协议
			$key   = get_option('ice_china_bank_pwd');
			$v_oid=$alipay_no;
			$v_amount = $price;                   //支付金额
			$v_moneytype = "CNY";                              //币种
			
			$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;        //md5加密拼凑串,注意顺序不能变
			$v_md5info = strtoupper(md5($text));                             //md5函数加密并转化成大写字母
			
			$remark1 = '';					 //备注字段1
			$remark2 = '';                    //备注字段2
			
			$v_rcvname   = ''  ;		// 收货人
			$v_rcvaddr   = ''  ;		// 收货地址
			$v_rcvtel    = ''   ;		// 收货人电话
			$v_rcvpost   = ''  ;		// 收货人邮编
			$v_rcvemail  = '' ;		// 收货人邮件
			$v_rcvmobile = '';		// 收货人手机号
			
			$v_ordername   = ''  ;	// 订货人姓名
			$v_orderaddr   = ''  ;	// 订货人地址
			$v_ordertel    = ''   ;	// 订货人电话
			$v_orderpost   = ''  ;	// 订货人邮编
			$v_orderemail  = '' ;	// 订货人邮件
			$v_ordermobile = '';	// 订货人手机号
			$html_text='
	<form method="post" name="E_FORM" action="https://pay3.chinabank.com.cn/PayGate">
	<input type="hidden" name="v_mid"         value="'.$v_mid.'">
	<input type="hidden" name="v_oid"         value="'.$v_oid.'">
	<input type="hidden" name="v_amount"      value="'.$v_amount.'">
	<input type="hidden" name="v_moneytype"   value="'.$v_moneytype.'">
	<input type="hidden" name="v_url"         value="'.$v_url.'">
	<input type="hidden" name="v_md5info"     value="'.$v_md5info.'">
	<input type="hidden" name="remark1"       value="'.$remark1.'">
	<input type="hidden" name="remark2"       value="'.$remark2.'">
	</form>
	<script>document.E_FORM.submit();</script>';
			$output = "
			<html>
		    <head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
		        <title>正在前往网银在线...</title>
		    </head>
		    <body><div  style='display:none'>$html_text</div>'</body></html>"; 
	echo $output;
?>
    </body>
</html>