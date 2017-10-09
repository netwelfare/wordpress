<?php
/**
author: www.mobantu.com
QQ: 82708210
email: 82708210@qq.com
*/
if ( !defined('ABSPATH') ) {exit;}
function erphpdown_content_show($content){
	$content2 = $content;
	if(is_singular()){
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
		$start_see=get_post_meta(get_the_ID(), 'start_see', true);
		$start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
		$price=get_post_meta(get_the_ID(), 'down_price', true);
		$url=get_post_meta(get_the_ID(), 'down_url', true);
		$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
		$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
		$userType=getUsreMemberType();
		
		$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
		if(get_option('erphp_url_front_vip')){
			$erphp_url_front_vip = get_option('erphp_url_front_vip');
		}
		$erphp_url_front_login = wp_login_url();
		if(get_option('erphp_url_front_login')){
			$erphp_url_front_login = get_option('erphp_url_front_login');
		}
		
		if($start_down){
			$content.='<div class="erphpdown" id="erphpdown"><div class="down-detail">';
			$content.='<h5>资源下载</h5>';
			if(is_user_logged_in())
			{
				if($hidden){
					$content.='<p class="down-hidden">隐藏内容：******，购买后可见！</p>';
				}
				if($price){
					$content.='<p class="down-price">下载价格：<span>'.$price.'</span>&nbsp;'.get_option("ice_name_alipay").'</p>';
				}else{
					if($memberDown != 4)
						$content.='<p class="down-price">免费下载</p>';
				}
				if($price || $memberDown == 4){
					$content.='<p class="down-ordinary">';
					global $wpdb;
					$user_info=wp_get_current_user();
					$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3 && $down_info==null)
						{
							$content.='<strong>VIP用户免费下载</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==2 && $down_info==null)
						{
							$content.='<strong>VIP用户5折下载</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==5 && $down_info==null)
						{
							$content.='<strong>VIP用户8折下载</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==6 && $down_info==null)
						{
							$content.='<strong>包年VIP用户免费下载</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==7 && $down_info==null)
						{
							$content.='<strong>终身VIP用户免费下载</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
					}
					if($memberDown==4 && $userType==FALSE)
					{
						$content.='仅对VIP用户开放下载，<a href="'.$erphp_url_front_vip.'">升级VIP</a><br/>';
					}
					else 
					{
						
						if($userType && $memberDown > 1)
						{
							$msg='下载地址：&nbsp;';
							if($memberDown==3 || $memberDown==4)
							{
								$msg.='您是VIP用户，可以免费下载此资源！';
								$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." target='_blank'>进入下载页面</a>";
							}
							elseif ($memberDown==2 && $down_info==null)
							{
								$msg.='您是VIP用户，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买下载此资源！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='您是VIP用户，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买下载此资源！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType == 9){
									$msg.='您是包年VIP用户，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." target='_blank'>进入下载页面</a>";
										
								}elseif($userType == 10){
									$msg.='您是终身VIP用户，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." target='_blank'>进入下载页面</a>";
										
								}else{
									$msg.='您是VIP用户，原价购买下载此资源！（年费VIP用户免费）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType == 10){
									$msg.='您是终身VIP用户，可以免费下载此资源！';
									$content.=$msg."<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()." target='_blank'>进入下载页面</a>";
										
								}else{
									$msg.='您是VIP用户，原价购买下载此资源！（终身VIP用户免费）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif($down_info)
							{
								$ice_url = $wpdb->get_var("SELECT ice_url FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID and ice_post=".get_the_ID());
								$content.='<a href='.constant("erphpdown").
							'download.php?url='.$ice_url.' target="_blank">您已购买过，直接去下载</a>';
							}
						}
						else 
						{
							
							if($down_info && $down_info->ice_price > 0)
							{
								$ice_url = $wpdb->get_var("SELECT ice_url FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID and ice_post=".get_the_ID());
								$content.='<a href='.constant("erphpdown").
							'download.php?url='.$ice_url.' target="_blank">您已购买过，直接去下载</a>';
							}
							else 
							{
								$content.='<strong>请先购买后下载资源</strong>，<a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
							}
						}
					}
					$content.='</p>';
				}else{
					$content.='<p class="down-ordinary"><a href="'.constant("erphpdown").'download.php?postid='.get_the_ID().'" target="_blank">进入下载页面</a></p>';
				}
				
				
			}
			else {
				$content.='<p class="down-hidden">隐藏内容：******，购买后可见！</p>';
				$content.='<p class="down-price">下载价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content.='<p class="down-ordinary">';
				$content.='您需要先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后，才能购买资源</p>';
				
			}
			
			if(get_option('ice_tips')) $content.='<p class="down-tip">'.get_option('ice_tips').'</p>';
			$content.='</div></div>';
			
		}elseif($start_see){
			
			if(is_user_logged_in())
			{
				global $wpdb;
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
				if( ($userType && ($memberDown==3 || $memberDown==4)) || ($down_info && $down_info->ice_price > 0) || ($memberDown==6 && $userType >= 9) || ($memberDown==7 && $userType == 10) ){
					return $content;
				}else{
				
					$content2='<div class="erphpdown" id="erphpdown"><div class="down-detail">';
					$content2.='<h5>内容查看</h5>';
					if($price){
						$content2.='<p class="down-price">查看价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
					}
					$content2.='<p class="down-ordinary">';
					
					
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3)
						{
							$content2.='<strong>VIP用户免费查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==2)
						{
							$content2.='<strong>VIP用户5折查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==5)
						{
							$content2.='<strong>VIP用户8折查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==6)
						{
							$content2.='<strong>包年VIP用户免费查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==7)
						{
							$content2.='<strong>终身VIP用户免费查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
					}
					
					if($memberDown==4 && $userType==FALSE)
					{
						$content2.='<strong>仅对VIP用户开放查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
					}
					else 
					{
						if($userType && $memberDown > 1)
						{
							if ($memberDown==2 && $down_info==null)
							{
								$msg.='您是VIP用户，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买查看此内容！';
								$content2.=$msg.'<a class=\'iframe\' href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='您是VIP用户，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买查看此内容！';
								$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType < 9){
										$msg.='您是VIP用户，原价购买查看此内容！（包年VIP用户免费查看）';
										$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType < 10){
										$msg.='您是VIP用户，原价购买查看此内容！（终身VIP用户免费查看）';
										$content2.=$msg.'<a class=\'iframe\'  href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
						}
						else 
						{
							if($down_info  && $down_info->ice_price > 0){
								
							}else {
								$content2.='<strong>请先购买后查看此隐藏内容</strong>，<a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().'>立即购买</a>';
							}
						}
					}
				}	
			}else{
				$content2='<div class="erphpdown" id="erphpdown"><div class="down-detail">';
				$content2.='<h5>内容查看</h5>';
				$content2.='<p class="down-price">查看价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content2.='<p class="down-ordinary">';
				$content2.='您需要先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后，才能购买查看内容</p>';
			}
			$content2.='</div></div>';
			return $content2;
			
		}elseif($start_see2){

			if(is_user_logged_in())
			{
				global $wpdb;
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID);
				if( (($memberDown==3 || $memberDown==4) && $userType) || ($down_info && $down_info->ice_price > 0) || ($memberDown==6 && $userType >= 9) || ($memberDown==7 && $userType == 10)){
					//
				}else{
					$content.='<div class="erphpdown" id="erphpdown"><div class="down-detail">';
					$content.='<h5>内容查看</h5>';
					if($price){
						$content.='<p class="down-price">查看价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
					}
					$content.='<p class="down-ordinary">';
					
					if($memberDown > 1 && $userType==false)
					{
						if($memberDown==3 && $down_info==null)
						{
							$content.='<strong>VIP用户免费查看隐藏内容</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==2 && $down_info==null)
						{
							$content.='<strong>VIP用户5折查看隐藏内容</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==5 && $down_info==null)
						{
							$content.='<strong>VIP用户8折查看隐藏内容</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==6 && $down_info==null)
						{
							$content.='<strong>包年VIP用户免费查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
						elseif ($memberDown==7 && $down_info==null)
						{
							$content.='<strong>终身VIP用户免费查看</strong>，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
						}
					}
					if($memberDown==4 && $userType==FALSE){
						$content.='仅对VIP用户开放查看隐藏内容，<a href="'.$erphp_url_front_vip.'" target="_blank">升级VIP</a><br/>';
					}
					else 
					{
						
						if($userType && $memberDown > 1)
						{
							if ($memberDown==2 && $down_info==null)
							{
								$msg.='您是VIP用户，可以5折（价格为：'.($price*0.5).get_option('ice_name_alipay').'）购买查看此隐藏内容！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==5 && $down_info==null)
							{
								$msg.='您是VIP用户，可以8折（价格为：'.($price*0.8).get_option('ice_name_alipay').'）购买查看此隐藏内容！';
								$content.=$msg.'<a class=iframe href='.constant("erphpdown").
							'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
							}
							elseif ($memberDown==6 && $down_info==null)
							{
								if($userType < 9){
										$msg.='您是VIP用户，原价购买查看此隐藏内容！（包年VIP用户免费查看）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							elseif ($memberDown==7 && $down_info==null)
							{
								if($userType < 10){
										$msg.='您是VIP用户，原价购买查看此隐藏内容！（终身VIP用户免费查看）';
										$content.=$msg.'<a class=iframe href='.constant("erphpdown").
									'icealipay-pay-center.php?postid='.get_the_ID().' target=\'_blank\'>立即购买</a>';
								}
							}
							
						}
						else 
						{
							$content.='<strong>请先购买后查看此隐藏内容</strong>，<a class=iframe href='.constant("erphpdown").'icealipay-pay-center.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
						}
					}
					$content.='</p>';
					if(get_option('ice_tips')) $content.='<p class="down-tip">'.get_option('ice_tips').'</p>';
					$content.='</div></div>';
				
				}
				
			}
			else {
				$content.='<div class="erphpdown" id="erphpdown"><div class="down-detail">';
				$content.='<h5>内容查看</h5>';
				$content.='<p class="down-price">查看价格：<span>'.$price.'</span>&nbsp;'.get_option('ice_name_alipay').'</p>';
				$content.='<p class="down-ordinary">';
				$content.='您需要先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>后，才能购买查看隐藏内容！</p>';
				if(get_option('ice_tips')) $content.='<p class="down-tip">'.get_option('ice_tips').'</p>';
				$content.='</div></div>';
				
			}

		}
		
	}
	
	return $content;
}
add_action('the_content','erphpdown_content_show');
