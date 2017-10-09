<?php
header("Content-type:text/html;character=utf-8");
require_once('../../../wp-config.php');
if(!is_user_logged_in()){
	wp_die('请先登录系统');
}
$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
$postid = $wpdb->escape($postid);
if($postid){
	$user_info=wp_get_current_user();
	$hasdown_info=$wpdb->get_var("select ice_id from ".$wpdb->icealipay." where ice_post='".$postid."' and ice_success=1 and ice_user_id=".$user_info->ID);
	if($hasdown_info){
		wp_die('请勿重复购买，请返回原页面刷新页面');
	}
	$data=get_post_meta($postid, 'down_url', true);
	$price=get_post_meta($postid, 'down_price', true);
	$memberDown=get_post_meta($postid, 'member_down',TRUE);
	$hidden=get_post_meta($postid, 'hidden_content', true);
	$start_down=get_post_meta($postid, 'start_down', true);
	$start_see=get_post_meta($postid, 'start_see', true);
	$start_see2=get_post_meta($postid, 'start_see2', true);
	if($price==FALSE)
	{
		wp_die('商品价格错误');
	}
	
	$okMoney=erphpGetUserOkMoney();
	$userType=getUsreMemberType();
	if($memberDown==4 && $userType==false)
	{
		echo "access error";exit;
	}
	if($userType && $memberDown==2)
	{
		$price=sprintf("%.2f",$price*0.5);
	}
	if($userType && $memberDown==5)
	{
		$price=sprintf("%.2f",$price*0.8);
	}
	if($okMoney >= $price && $okMoney > 0 && $price > 0)
	{
		if(erphpSetUserMoneyXiaoFei($price))
		{
			$subject   = get_post($postid)->post_title;
			$postUserId=get_post($postid)->post_author;
			
			if($start_down || $start_see || $start_see2)
			{
				$result=erphpAddDownload($subject, $postid, $price,1, $data, $postUserId);
				if($result)
				{

					addUserMoney($postUserId,$price);
					$RefMoney=$wpdb->get_row("select * from ".$wpdb->users." where ID=".$user_info->ID);
					if($RefMoney->father_id > 0 && erphpdod() > 0){
						addUserMoney($RefMoney->father_id,$price*get_option('ice_ali_money_ref')*0.01);
					}
					if($start_down)
					{
						header("Location:".constant("erphpdown").'download.php?url='.$result);
					}
					elseif($start_see || $start_see2)
					{
						header("Location:".get_bloginfo('wpurl').'/?p='.$postid);
					}
					exit;
				}
				else
				{
					$wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money-".$price ." where ice_user_id=".$user_info->ID);
					wp_die('系统发生错误,请稍候重试!');
					exit;
				}
			}
		}
		else 
		{
			wp_die('系统错误');
		}
	}
	else 
	{
		wp_die('余额不足完成此次交易!');
	}
}