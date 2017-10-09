<?php 
	if ( !defined('ABSPATH') ) {exit;}
	class EPD{

		public $user_id = 0;
		public $is_logged = 0;

		public function __construct(){

			if(is_user_logged_in()){
				$this->user_id = get_current_user_id();
				$this->is_logged = 1;
			}
		
		}

		public function isErphpdown($post_id){
			if(!$post_id)
				return false;

			$start_down = get_post_meta($post_id,'start_down',true);
			$start_see = get_post_meta($post_id,'start_see',true);
			$start_see2 = get_post_meta($post_id,'start_see2',true);
			if($start_see2 == 'yes' || $start_see == 'yes' || $start_down == 'yes')
				return true;
		}

		public function isBought($post_id, $user_id = null){
			if(!$post_id)
				return false;

			if($user_id){
				$ice_user_id = $user_id;
			}else{
				$ice_user_id = $this->user_id;
			}

			global $wpdb;
			$isBought = $wpdb->get_var("select ice_id from ".$wpdb->icealipay." where ice_post='".$post_id."' and ice_success=1 and ice_user_id=".$ice_user_id);
			return $isBought;
		}

		public function checkout($money){
			if(!$this->is_logged)
				return false;

			if($money > 0){
				global $wpdb;
				return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$money." where ice_user_id=".$this->user_id);
			}else{
				return false;
			}
		}

		public function checkoutReturn($money){
			if(!$this->is_logged)
				return false;

			if($money > 0){
				global $wpdb;
				return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money-".$money ." where ice_user_id=".$this->user_id);
			}else{
				return false;
			}
		}

		public function doAff($money){
			if(!$this->is_logged)
				return false;

			global $wpdb;
			$RefMoney=$wpdb->get_row("select father_id from ".$wpdb->users." where ID=".$this->user_id);
			if($RefMoney->father_id > 0){
				$this->addUserMoney($RefMoney->father_id, $money*get_option('ice_ali_money_ref',0)*0.01);
			}
		}

		public function addUserMoney($user_id, $money){
			if(!$user_id)
				return false;

			global $wpdb;
			$myinfo=$wpdb->get_row("select ice_id from ".$wpdb->iceinfo." where ice_user_id=".$user_id);
			if(!$myinfo){
				return $wpdb->query("insert into $wpdb->iceinfo(ice_have_money,ice_user_id,ice_get_money)values('$money','$user_id',0)");
			}else{
				return $wpdb->query("update $wpdb->iceinfo set ice_have_money=ice_have_money+".$money." where ice_user_id=".$user_id);
			}
		}

		public function addBuyLog($postName,$post_id,$price,$success,$postDownloadUrl,$postAuthor){
			if(!$this->is_logged)
				return false;

			if($price > 0){
				global $wpdb;
				$postName = str_replace("'","",$postName);
				$postName = str_replace("‘","",$postName);
				$url       = md5(date("YmdHis").$post_id.mt_rand(1000000, 9999999));
				$orderNum  = mt_rand(100, 999).date("mdH");
				$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,
				ice_author)VALUES ('$orderNum','$postName','$post_id','$price','$success','$url','".$this->user_id."','".date("Y-m-d H:i:s")."','".$postDownloadUrl."','$postAuthor')";
				if($wpdb->query($sql)){
					return $url;
				}
			}
			return false;
		}

		public function getPostErphpdownType($post_id){
			if(!$post_id)
				return false;

			$start_down = get_post_meta($post_id,'start_down',true);
			$start_see = get_post_meta($post_id,'start_see',true);
			$start_see2 = get_post_meta($post_id,'start_see2',true);
			if($start_see2 == 'yes')
				return 'start_see2';
			if($start_see == 'yes')
				return 'start_see';
			if($start_down == 'yes')
				return 'start_down';
		}

		public static function getPostPrice($post_id){
			if(!$post_id)
				return false;

			$down_price = get_post_meta($post_id,'down_price',true);
			return $down_price;
		}

		public static function getPostDownloadUrl($post_id){
			if(!$post_id)
				return false;

			$down_url = get_post_meta($post_id,'down_url',true);
			return $down_url;
		}

		public static function getPostHidden($post_id){
			if(!$post_id)
				return false;

			$hidden_content = get_post_meta($post_id,'hidden_content',true);
			return $hidden_content;
		}

		public static function getPostVipType($post_id){
			if(!$post_id)
				return false;

			$member_down = get_post_meta($post_id,'member_down',true);
			return $member_down;
		}

		public static function getUserVipType($user_id = null){
			if($user_id){
				$ice_user_id = $user_id;
			}else{
				if(!is_user_logged_in())
					return false;
				$ice_user_id = get_current_user_id();
			}

			global $wpdb;
			$userTypeInfo=$wpdb->get_row("select endTime, userType from ".$wpdb->iceinfo." where ice_user_id=".$ice_user_id);
			if($userTypeInfo){
				if(time() > strtotime($userTypeInfo->endTime) + 24*3600){
					$wpdb->query("update $wpdb->iceinfo set userType=0, endTime='1000-01-01' where ice_user_id=".$ice_user_id);
					return false;
				}
				return $userTypeInfo->userType;
			}
			return false;
		}

		public static function getUserMoney($user_id = null){
			if($user_id){
				$ice_user_id = $user_id;
			}else{
				if(!is_user_logged_in())
					return false;
				$ice_user_id = get_current_user_id();
			}

			global $wpdb;
			$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$ice_user_id);
			return $userMoney == false ? 0 : ($userMoney->ice_have_money - $userMoney->ice_get_money);
		}

	}