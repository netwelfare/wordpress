<?php
	if ( !defined('ABSPATH') ) {exit;}
	function epd_checkout(){
		if(isset($_GET['epd_checkout'])){
			$post_id = is_numeric($_GET['epd_checkout']) ? $_GET['epd_checkout'] : '0';
			$EPD = new EPD();
			if($EPD->is_logged){
				if($EPD->isErphpdown($post_id)){
					if($EPD->getPostPrice($post_id) > 0){

						$price = $EPD->getPostPrice($post_id);
						if(!$EPD->isBought($post_id)){

							$postVipType = $EPD->getPostVipType($post_id);
							$userVipType = $EPD->getUserVipType();
							if($postVipType == 4 && !$userVipType){
								wp_die('此资源仅对VIP开放！');
							}elseif($postVipType == 2 && $userVipType ){
								$price=sprintf("%.2f",$price*0.5);
							}elseif($postVipType == 5 && $userVipType ){
								$price=sprintf("%.2f",$price*0.8);
							}

							if($EPD->getUserMoney() >= $price){
								if($EPD->checkout($price)){
									$postName = get_post($post_id)->post_title;
									$postAuthor = get_post($post_id)->post_author;
									$url = $EPD->addBuyLog($postName, $post_id, $price, 1, $EPD->getPostDownloadUrl($post_id), $postAuthor);
									if($url){
										$EPD->addUserMoney($postAuthor, $price);
										$EPD->doAff($price);
										if($EPD->getPostErphpdownType($post_id) == 'start_down'){
											wp_redirect(home_url().'?epd_download_url='.$url);
										}
									}else{
										if($EPD->checkoutReturn($price)){
											wp_die('购买失败，请稍后重试！');
										}else{
											wp_die('购买失败，请稍后重试！若已扣费，请联系网站客服处理！');
										}
									}
								}else{
									wp_die('购买失败，请稍后重试！');
								}
							}else{
								wp_die('余额不足，请先充值！');
							}

						}else{
							wp_die('您已购买过了！');
						}

					}else{
						wp_die('价格错误！');
					}
				}else{
					wp_die('此资源暂不支持购买！');
				}
			}else{
				wp_die('请先登录！');
			}
		}
	}
	add_action( 'init', 'epd_checkout' );

	function epd_download_url(){
		if(isset($_GET['epd_download_url'])){
			global $wpdb;
			$url = $wpdb->escape($_GET['epd_download_url']);
			$EPD = new EPD();
			if($EPD->is_logged){
				$post_id=$wpdb->get_var("select ice_post from ".$wpdb->icealipay." where ice_url='".$url."' and ice_user_id=".$EPD->user_id);
				if($post_id){
					$data=get_post_meta($post_id, 'down_url', true);
					$downList=explode("\r\n",$data);
					$downHtml='';
					foreach ($downList as $k=>$v){
				    	$downHtml .= "<p>文件".($k+1)."地址：<a href='".home_url()."?epd_download_file=".$post_id."&url=".$url."&key=".($k+1)."' targert='_blank'>点击下载</a></p>";
				    }
					if($EPD->getPostHidden($post_id)){
						$downHtml .= '<h3>隐藏信息:</h3><p>'.$EPD->getPostHidden($post_id).'</p>';
					}
					epd_download_html($downHtml);
				}
			}else{
				wp_die('请先登录！');
			}
			
		}
	}
	add_action( 'init', 'epd_download_url' );

	function epd_download_html($content){
		echo $content;
		exit;
	}

	function erphpdown_install() {
		global $wpdb, $erphpdown_version;
		$charset_collate = $wpdb->get_charset_collate();
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$create_ice_alipay_sql = "CREATE TABLE $wpdb->icealipay (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_num varchar(50) NOT NULL,".
				"ice_title varchar(100) NOT NULL,".
				"ice_post int(11) NOT NULL,".
				"ice_price double(10,2) NOT NULL,".
				"ice_url varchar(32) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_time datetime NOT NULL,".
				"ice_data text NOT NULL ,".
				"ice_success int(11) NOT NULL,".
				"ice_author int(11) NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_alipay_sql );
		
		$create_ice_money_sql="CREATE TABLE $wpdb->icemoney (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_num varchar(50) NOT NULL,".
				"ice_money double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_time datetime NOT NULL,".
				"ice_success int(10) NOT NULL,".
				"ice_note varchar(50) NOT NULL,".
				"ice_success_time datetime NOT NULL,".
				"ice_alipay varchar(200) NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_money_sql );
		
		$create_money_info_sql="CREATE TABLE $wpdb->iceinfo (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_have_money double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_get_money double(10,2) NOT NULL,".
				"userType TINYINT(4) NOT NULL DEFAULT 0,".
				"endTime DATE NOT NULL DEFAULT '1000-01-01',".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_money_info_sql );
		
		$create_get_money_sql="CREATE TABLE $wpdb->iceget (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_alipay varchar(100) NOT NULL,".
				"ice_name varchar(30) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_money double(10,2) NOT NULL,".
				"ice_time datetime NOT NULL,".
				"ice_success int(10) NOT NULL,".
				"ice_note varchar(50) NOT NULL,".
				"ice_success_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_get_money_sql );
		
		$create_ice_vip_sql = "CREATE TABLE $wpdb->vip (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_price double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_user_type tinyint(4) NOT NULL default 0,".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_vip_sql );
		
		$create_ice_aff_sql = "CREATE TABLE $wpdb->aff (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_price double(10,2) NOT NULL,".
				"ice_user_id int(11) NOT NULL,".
				"ice_user_id_visit int(11),".
				"ice_ip varchar(50),".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_aff_sql );

		$create_ice_down_sql = "CREATE TABLE $wpdb->down (".
				"ice_id int(11) NOT NULL auto_increment,".
				"ice_user_id int(11) NOT NULL,".
				"ice_post_id int(11),".
				"ice_ip varchar(50),".
				"ice_time datetime NOT NULL,".
				"PRIMARY KEY (ice_id)) $charset_collate;";
		dbDelta( $create_ice_down_sql );
		
		$up1to2="ALTER TABLE '".$wpdb->users."' ADD  'father_id' INT( 10 ) NOT NULL DEFAULT  '0'";
		$wpdb->query($up1to2);

		$up6to7="ALTER TABLE `".$wpdb->users."` ADD  `reg_ip` varchar( 60 ) DEFAULT  ''";
		$wpdb->query($up6to7);
		
		$up7to8="ALTER TABLE '".$wpdb->icemoney."' modify column ice_num varchar(50)";
		$wpdb->query($up7to8);

		$up8to9="ALTER TABLE '".$wpdb->icealipay."' modify column ice_num varchar(50)";
		$wpdb->query($up8to9);

		if(get_option('erphpdown_version') < 9.00){
			update_option('erphp_post_types',array('post'));
		}

		update_option( 'erphpdown_version', $erphpdown_version );
	}