<?php require_once '../../../wp-config.php';?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" lang="zh-CN">
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" lang="zh-CN">
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
</head>
<?php
if(!is_user_logged_in()){
	wp_die('请先登录系统');
}
?>
<body style="margin:8px;">
 <div id="erphpdown-paybox" class="clearfix">
            

<?php
$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
$postid = esc_sql($postid);
if($postid){
	$user_info=wp_get_current_user();
	if($user_info->ID){
		//检查用户是否已经购买过
		$downInfo=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_user_id=".$user_info->ID ." and ice_post=".$postid." and ice_success=1");
		if($downInfo){
			?>
			<a class="ss-button skyOrange" href="<?php echo constant("erphpdown");?>download.php?url=<?php echo $downInfo->ice_url?>">您已经购买过，点击直接下载</a>	
			<?php
		}else{
			$data=get_post_meta($postid, 'down_url', true);
			$price=get_post_meta($postid, 'down_price', true);
			$price_old = $price;
			$hidden=get_post_meta($postid, 'hidden_content', true);
			//if(($data || $hidden) && $price)
			if($price){
				$okMoney=erphpGetUserOkMoney();
				//vip
				$vip=false;
				$memberDown=get_post_meta($postid, 'member_down',TRUE);
				$userType=getUsreMemberType();
				if($memberDown==4 && $userType==false)
				{
					echo "access error";exit;
				}
				if($userType && $memberDown==2)
				{
					$vip=TRUE;
					$price=$price*0.5;
				}
				if($userType && $memberDown==5)
				{
					$vip=TRUE;
					$price=$price*0.8;
				}
				
				$erphp_url_front_recharge = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-add-money-online.php';
				if(get_option('erphp_url_front_recharge')){
					$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
				}
				?>
			   
				<table id="paycenter" class="box erphpdown_paycenter" width="344" align="center">
					<tr>
						<td>资源名称：<?php echo get_post($postid)->post_title?></td>
					</tr>
					<tr>
						<td>下载价格：<?php echo sprintf("%.2f",$price)?><?php echo  $vip==TRUE?'(原价:'.sprintf("%.2f",$price_old).')' :''?> <?php echo get_option('ice_name_alipay')?></td>
					</tr>
					<tr>
						<td>账户余额：<?php echo sprintf("%.2f",$okMoney)?> <?php echo get_option('ice_name_alipay')?></td>
					</tr>
					<tr>
					<td>
					<?php if($okMoney >= $price) {?>
					<a class="ss-button skyOrange" href="<?php echo constant("erphpdown").'checkout.php?postid='.$postid?>"
					 target="_blank">使用余额支付</a>
					 <?php }else{echo "余额不足以完成此次付款，<a target=_blank class=\"skyOrange ss-button\" href=\"".$erphp_url_front_recharge."\">点击充值</a>";}?>
					 </td>
					</tr>
					<tr>
					</tr>
				</table>
			<?php
			}else{
				echo "获取文章价格出错!";
			}
		}
	}else{
		echo "用户信息获取失败";
	}
}else{
	echo "文章ID错误";
}
?>
            
</div>
</body>
</html>
