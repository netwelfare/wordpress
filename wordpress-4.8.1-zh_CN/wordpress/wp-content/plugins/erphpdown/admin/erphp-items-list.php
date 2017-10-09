<?php
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in()){
	exit;
}

$total_trade   = $wpdb->get_var("SELECT COUNT(a.ID) FROM $wpdb->posts a left join $wpdb->postmeta b on a.ID=b.post_id WHERE a.post_status='publish' and (b.meta_key='start_down' or b.meta_key='start_see' or b.meta_key='start_see2') and b.meta_value='yes' ");
/////////////////////////////////////////////////www.mobantu.com   82708210@qq.com
$ice_perpage = 20;
$pages = ceil($total_trade / $ice_perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $ice_perpage*($page-1);
$list = $wpdb->get_results("SELECT a.post_title as ice_title,a.ID as ice_id,a.post_date as ice_time FROM $wpdb->posts a left join $wpdb->postmeta b on a.ID=b.post_id WHERE a.post_status='publish' and (b.meta_key='start_down' or b.meta_key='start_see' or b.meta_key='start_see2') and b.meta_value='yes' order by a.post_date DESC limit $offset,$ice_perpage");

?>
<div class="wrap">
	<h2>资源统计</h2>
	<p><?php printf(('共<strong>%s</strong>个.'), $total_trade); ?></p>
	<table class="wp-list-table widefat fixed posts">
		<thead>
			<tr>
				<th width="40%">商品名称</th>
				<th width="15%">价格</th>
                <th width="10%">销量</th>
                <th width="10%">下载次数</th>
				<th width="15%">发布时间</th>
				<th width="10%">管理</th>		
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				$ice_price = get_post_meta($value->ice_id,"down_price",true);
				$down_times = get_post_meta($value->ice_id,'down_times',true);
				$down_times = $down_times?$down_times:'0';
				echo "<tr>\n";
				echo "<td><a target=_blank href='".get_permalink($value->ice_id)."'>$value->ice_title</a></td>\n";
				echo "<td><input type=text name=p_price_$value->ice_id id=p_price_$value->ice_id value=$ice_price style=\"width:80px;\" /><input type=button id=editpricebtn_$value->ice_id onclick=editPrice($value->ice_id) value=修改 style=border:0px;color:#21759b;cursor:pointer></td>\n";
				echo "<td>".getProductSales($value->ice_id)."</td>";
				echo "<td>".$down_times."</td>";
				echo "<td>$value->ice_time</td>\n";
				echo "<td><a target=_blank href='".get_bloginfo('wpurl')."/wp-admin/post.php?post=".$value->ice_id."&action=edit'>编辑</a></td>\n";
				echo "</tr>";  
			}
		}else{
			echo '<tr><td colspan="4" align="center"><strong>没有交易记录</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo erphp_admin_pagenavi($total_trade,$ice_perpage);?>
</div>
<script type="text/javascript">
function editPrice(id){	
	jQuery("#editpricebtn_"+id).val("修改中..");
	jQuery.ajax({
		type: "post",
		url: "<?php echo constant("erphpdown");?>admin/action/price.php",
		data: "do=editprice&postid=" + id + "&new_price=" + jQuery("#p_price_"+id).val(),
		date:"",
		dataType: "html",
		success: function (data) {
			if(data == 'success'){
				jQuery("#editpricebtn_"+id).val("修改成功");
				setTimeout("editsuccess("+id+")",3000)
			}
		},
		error: function (request) {
			jQuery("#editpricebtn_"+id).val("修改");
			alert("修改失败");
		}
	});
}

function editsuccess(id){
	jQuery("#editpricebtn_"+id).val("修改");
}
</script>
