<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>付款提示</title>
	<link rel='stylesheet'  href='../static/epd-front.css' type='text/css' media='all' />
</head>

<body>
<?php 
	require_once('../../../../wp-config.php');
	if(!is_user_logged_in()){wp_die('请先登录！');}
	$erphpdown_zfbjk_alipay  = get_option('erphpdown_zfbjk_alipay');
	$erphpdown_zfbjk_name  = get_option('erphpdown_zfbjk_name');
	$erphpdown_zfbjk_qr  = get_option('erphpdown_zfbjk_qr');

	$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
	$price = $wpdb->escape($price);
	if($price && is_user_logged_in()){
		$user_Info   = wp_get_current_user();
		global $wpdb;
		$subject = get_bloginfo('name').'充值订单['.get_the_author_meta( 'user_login', $user_Info->ID ).']';  
		$out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);	
		$time = date('Y-m-d H:i:s');
		if(!empty($price)){
			
			$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
			VALUES ('$price','$out_trade_no','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','')";
			$a=$wpdb->query($sql);
			if(!$a){
				wp_die('系统发生错误，请稍后重试!');
			}else{
				$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$out_trade_no."'");
				?>

<div id="header">
    <div class="header-container fn-clear">
        <div class="header-title">
            <div class="alipay-logo"></div>
            <span class="logo-title">Erphpdown</span>
        </div>
    </div>
</div>
<div id="container">
	<div class="content">
		<div id="J_order" class="order-area">
			<div id="order" class="order order-bow">
				<div class="orderDetail-base">
					<div class="order-extand-explain fn-clear">
						<span class="fn-left explain-trigger-area order-type-navigator" style="cursor: auto" data-role="J_orderTypeQuestion">
			            	<span>转账信息</span>
			            </span>
					</div>
                    <div class="commodity-message-row">
			            <span class="first long-content">
			                收款支付宝账号：<font color="green"><?php echo $erphpdown_zfbjk_alipay?></font> （姓名：<?php echo $erphpdown_zfbjk_name; ?>）
			            </span>
                    </div>
                    <div class="commodity-message-row" style="padding-top:5px">
			            <span class="first long-content">
			                转账金额：<font color="green"><?php echo $price;?> 元</font>
			            </span>
                    </div>
                    <div class="commodity-message-row" style="padding-top:5px">
			            <span class="first long-content">
			                付款说明（必填）：<font color="blue"><?php echo $money_info->ice_id;?></font> 
			                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			                <font color="red">（注意：付款说明不能填其他内容，否则无法自动充值，付款后请等待5-15秒左右）</font>
			            </span>
                    </div>
				</div>
			</div>
		</div>

		<div class="cashier-center-container">
			<div class="cashier-center-view view-qrcode fn-left" id="J_view_qr">
				<div data-role="qrPayArea" class="qrcode-integration qrcode-area" id="J_qrPayArea">
				        <div class="qrcode-header">
				        <div class="ft-center">手机支付宝扫一扫付款</div>
				        <div class="ft-center qrcode-header-money"><?php echo $price;?> 元</div>
				    </div>

				    <div class="qrcode-img-wrapper" data-role="qrPayImgWrapper">
				        <div data-role="qrPayImg" class="qrcode-img-area">
				            
				        <div style="position: relative;display: inline-block;">
				        	<img src="<?php echo $erphpdown_zfbjk_qr;?>" width="172" height="172">
				        </div></div>

				        <div class="qrcode-img-explain fn-clear">
				            <img class="fn-left" src="../static/images/sao.png" alt="扫一扫标识" smartracker="on">
				            <div class="fn-left">打开手机支付宝<br>扫一扫继续付款</div>
				        </div>
				    </div>

				        <div class="qrcode-foot" data-role="qrPayFoot" style="display: block;">
				        <div data-role="qrPayExplain" class="qrcode-explain fn-hide" style="display: block;">
				            电脑转账：登录支付宝账号<br>给支付宝 <?php echo $erphpdown_zfbjk_alipay?> 转账 <?php echo $price;?> 元<br>付款说明（必填）：<font color="blue"><?php echo $money_info->ice_id;?></font> <br><font color="red">（注意：付款说明不能填其他内容，付款后请等待5-15秒左右）</font>
				        </div>
				    </div>
				</div>
				<div class="qrguide-area" id="J_qrguideArea" seed="NewQr_animationClick">
				    <img src="../static/images/faq1.png" class="qrguide-area-img background" seed="J_qrguideArea-qrguideAreaImg" smartracker="on">
				    <img src="../static/images/faq2.png" class="qrguide-area-img active" seed="J_qrguideArea-qrguideAreaImgT1" smartracker="on" style="display: block;">
				</div>
	
            </div>
		</div>
	</div>
</div>

				<?php
			}
		}else{
			wp_die('请输入您要充值的金额');
		}
	}
?>
<script src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
<script>
	setOrder = setInterval(function() {
		$.ajax({  
            type: 'POST',  
            url: '<?php echo ERPHPDOWN_URL;?>/admin/action/order.php',  
            data: {
            	do: 'checkOrder',
            	order: '<?php echo $money_info->ice_id;?>'
            },  
            dataType: 'text',
            success: function(data){  
                if( data == '1' ){
                    clearInterval(setOrder);
                    alert('充值成功！请关闭此页面~~');
                    window.close();
                }  
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
            	//alert(errorThrown);
            }
        });

	}, 5000);
</script>
</body>
</html>