<?php
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in()){
	exit;
}

$total_user  = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id > 0");
$ice_perpage = 20;
$pages = ceil($total_user / $ice_perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $ice_perpage*($page-1);
$list = $wpdb->get_results("SELECT ID,user_login,user_registered,father_id FROM $wpdb->users where father_id > 0 order by ID desc limit $offset,$ice_perpage");

?>
<div class="wrap">
	<h2>所有推广用户记录</h2>
	<table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th width="20%">用户ID</th>
				<th width="20%">推广人ID</th>
                <th width="20%">注册时间</th>
                <th width="20%">VIP类型</th>	    
                <th width="20%">消费额</th>	    
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value)
			{
				$userType= EPD::getUserVipType($value->ID);
				$typeName = '无';
				if($userType==7) $typeName = '包月VIP';elseif($userType==8) $typeName = '包季VIP';elseif($userType==9) $typeName = '包年VIP';elseif($userType==10) $typeName = '终身VIP';
				echo "<tr>\n";
				echo "<td>".$value->user_login."</td>";
				echo "<td>".get_user_by('id',$value->father_id)->user_login."</td>";
				echo "<td>".$value->user_registered."</td>";
				echo "<td>$typeName</td>";
				echo "<td>".erphpGetUserAllXiaofei($value->ID)."</td>";
				echo "</tr>";
			}
		}
		else
		{
			echo '<tr><td colspan="4" align="center"><strong>没有推广记录</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo erphp_admin_pagenavi($total_user,$ice_perpage);?> 
　　
</div>

