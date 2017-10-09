<?php
$spname="erphpdown财付通";
$partner = get_option('erphpdown_tenpay_uid');                                  	//财付通商户号
$key = get_option('erphpdown_tenpay_pwd');											//财付通密钥

$return_url = constant("erphpdown").'payment/tenpay/payReturnUrl.php';			//显示支付结果页面,*替换成payReturnUrl.php所在路径
$notify_url = constant("erphpdown").'payment/tenpay/payNotifyUrl.php';			//支付完成后的回调处理页面,*替换成payNotifyUrl.php所在路径
?>