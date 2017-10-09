<?php
require( dirname(__FILE__) . '/../../../../../wp-load.php' );
if(is_user_logged_in()){
	global $wpdb;
	if($_POST['do']=='checkOrder'){
		global $current_user;
		$id = $wpdb->escape($_POST['order']);
		$result = $wpdb->get_var("select ice_success from $wpdb->icemoney where ice_user_id = '".$current_user->ID."' and ice_id='".$id."'");
		if($result){
			echo '1';
		}else{
			echo '0';
		}
	}
}