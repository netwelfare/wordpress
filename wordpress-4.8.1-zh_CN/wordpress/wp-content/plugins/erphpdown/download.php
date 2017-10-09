<?php
header("Content-type:text/html;character=utf-8");
require_once('../../../wp-config.php');
function assignPageTitle(){
  	return "文件下载提示 | ";
}
add_filter('wp_title', 'assignPageTitle');
if(!is_user_logged_in())
{
	showMsg('请先登录系统');
}
$urlid=isset($_GET['urlid']) && strlen($_GET['urlid'])==32 ?$_GET['urlid'] :0;
$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
$url=isset($_GET['url']) ? $_GET['url'] :FALSE;
$key=isset($_GET['key']) ? $_GET['key'] :FALSE;

$urlid = esc_sql($urlid);
$postid = esc_sql($postid);
$url = esc_sql($url);
$key = esc_sql($key);
if($urlid==false && $postid==false && $url==false )
{
	showMsg("来路错误");
}
if($key)
{
	if(is_numeric($key))
	{
		$key=intval($key);
	}
	else 
	{
		showMsg('下载的文件地址错误');
	}
}
if($urlid)
{
	$user_info=wp_get_current_user();
	if($user_info->ID)
	{
		$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_url='".$urlid."' and ice_user_id=".$user_info->ID);
		if(!$down_info)
		{
			showMsg('请先完成充值');
		}
		$order_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num=".$down_info->ice_num.' and ice_user_id='.$user_info->ID);
		if($order_info==false || $order_info->ice_success==0)
		{
			showMsg("订单尚未支付完成!");
		}
		if(time()-strtotime($order_info->ice_success_time) > 25*60)
		{
			showMsg('下载地址已经过期！');
		}
		//update
		$price=get_post_meta($down_info->ice_post, 'down_price', true);
		$pp = $down_info->ice_post;
		$wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$price." where ice_user_id=".$user_info->ID);
		$postUserId=get_post($down_info->ice_post)->post_author;
		addUserMoney($postUserId,$price);
	}
	else 
	{
		showMsg("用户信息获取失败");
	}
}
elseif ($postid)
{
	$isDown=FALSE;
	$data=get_post_meta($postid, 'down_url', true);
	$price=get_post_meta($postid, 'down_price', true);
	if(strlen($data) > 2)
	{
		$memberDown=get_post_meta($postid,'member_down',TRUE);
		$user_info=wp_get_current_user();
		$userType=getUsreMemberType();
		if($user_info && $userType && ($memberDown ==3 || $memberDown ==4))
		{
			$isDown=true;
			$pp = $postid;
		}
		elseif($user_info && ($userType == 9 || $userType == 10) && $memberDown ==6 )
		{
			$isDown=true;
			$pp = $postid;
		}
		elseif($user_info && $userType == 10 && $memberDown ==7 )
		{
			$isDown=true;
			$pp = $postid;
		}
		else 
		{
			if(empty($price) || $price==0)
			{
				$isDown=true;
				$pp = $postid;
			}
		}
	}
	if(!$isDown)
	{
		showMsg('下载地址不存在!');
	}
}
if($url)
{
	$user_info=wp_get_current_user();
	if($user_info->ID)
	{
		$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_url='".$url."' and ice_user_id=".$user_info->ID);
		if($down_info->ice_price > 0){
			$downPostId=$down_info->ice_post;
			$pp = $downPostId;
			$data=get_post_meta($downPostId, 'down_url', true);
		}
	}
	if(!$down_info || !$data)
	{
		showMsg('下载信息错误');
	}
}
?>
<?php 
    $data=$data ?$data :$down_info->ice_data;
    $downList=explode("\r\n",$data);
    $downMsg='';
    if($key)
    {
		$user_info=wp_get_current_user();
    	$file=$downList[$key-1];
		$times=time();
		$md5key=md5($user_info->ID.get_option('erphpdown_downkey','erphpdown').$key.$times);
		$enstr = new enstr();
		$entemp = $enstr->enstrhex($file,get_option('erphpdown_downkey','erphpdown'));
		header("Location:downloadfile.php?id=".$pp."&filename=".$key."&md5key=".$md5key."&times=".$times."&session_name=".$entemp);
		exit;
    	$info=download(ABSPATH.'/'.$file);
    	if(!$info)
    	{
    		showMsg('文件不存在!');
    	}
    }
    foreach ($downList as $k=>$v)
    {
    	$downMsg.="文件".($k+1)."地址：<a href='download.php?postid=".$postid."&url=".$down_info->ice_url."&key=".($k+1)."' targert='_blank'>点击下载</a><br/>";
    }
	$hiddens = get_post_meta($pp,'hidden_content',true);
	if($hiddens){
		$downMsg .='<br /><p><h3>隐藏信息:</h3></p><p>'.$hiddens.'</p>';
	}
    showMsg($downMsg);
?>