<?php
require_once('../../../wp-config.php');
if(!is_user_logged_in())
{
	showMsg('请先登录系统');
}
if(intval(mbtcheck())<1){die();}
$user_info=wp_get_current_user();
$filename=$_GET['filename'];
$md5key=$_GET['md5key'];
$times=$_GET['times'];
$session_name=$_GET['session_name'];

$filename = esc_sql($filename);
$md5key = esc_sql($md5key);
$times = esc_sql($times);
$session_name = esc_sql($session_name);

if($_GET['id'] > 0){
	$pid = esc_sql($_GET['id']);
	$g=(int)get_post_meta($pid,'down_times',true);
	if(!$g)$g=0;
	update_post_meta($pid,'down_times',$g+1);
	$memberDown=get_post_meta($pid, 'member_down',TRUE);

	if($memberDown == 3 || $memberDown == 4 || $memberDown == 6 || $memberDown == 7){
		$userType=getUsreMemberType();
		if($userType){
			
			$erphp_life_times    = get_option('erphp_life_times');
			$erphp_year_times    = get_option('erphp_year_times');
			$erphp_quarter_times = get_option('erphp_quarter_times');
			$erphp_month_times  = get_option('erphp_month_times');

			if($userType == 7 && $erphp_month_times > 0){
				if( checkDownLog($user_info->ID,$pid,$erphp_month_times,erphpGetIP()) ){
					addDownLog($user_info->ID,$pid,erphpGetIP());
				}else{
					wp_die("包月VIP用户每天只能免费下载".$erphp_month_times."次！");
				}
			}elseif($userType == 8 && $erphp_quarter_times > 0){
				if( checkDownLog($user_info->ID,$pid,$erphp_quarter_times,erphpGetIP()) ){
					addDownLog($user_info->ID,$pid,erphpGetIP());
				}else{
					wp_die("包季VIP用户每天只能免费下载".$erphp_quarter_times."次！");
				}
			}elseif($userType == 9 && $erphp_year_times > 0){
				if( checkDownLog($user_info->ID,$pid,$erphp_year_times,erphpGetIP()) ){
					addDownLog($user_info->ID,$pid,erphpGetIP());
				}else{
					wp_die("包年VIP用户每天只能免费下载".$erphp_year_times."次！");
				}
			}elseif($userType == 10 && $erphp_life_times > 0){
				if( checkDownLog($user_info->ID,$pid,$erphp_life_times,erphpGetIP()) ){
					addDownLog($user_info->ID,$pid,erphpGetIP());
				}else{
					wp_die("终身VIP用户每天只能免费下载".$erphp_life_times."次！");
				}
			}
		}
	}
}

if(abs(time()-$times) < 100)
{
	$md5my=md5($user_info->ID.get_option('erphpdown_downkey','erphpdown').$filename.$times);
	if($md5key==$md5my)
	{
		$enstr = new enstr();
		$file = $enstr->destrhex($session_name,get_option('erphpdown_downkey','erphpdown'));
		if(substr($file,0,7) == 'http://' || substr($file,0,8) == 'https://' || substr($file,0,10) == 'thunder://' || substr($file,0,7) == 'magnet:')
		{
			$info=download($file);
		}
		else
		{
			$info=download(ABSPATH.'/'.$file);
		}
		if(!$info)
		{
			showMsg('出错了，请联系管理员提供下载！');
			exit;
		}
	}
}
exit('404 not found');
?>
