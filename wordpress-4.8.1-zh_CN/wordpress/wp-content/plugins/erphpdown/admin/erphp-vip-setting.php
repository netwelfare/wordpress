<div class="wrap">
<?php
if ( !defined('ABSPATH') ) {exit;}
if(isset($_POST['Submit']) && current_user_can('administrator') && $_POST['Submit']=='保存设置'){
	
	update_option('ciphp_life_price', $_POST['life_price']);
	update_option('ciphp_year_price', $_POST['year_price']);
    update_option('ciphp_quarter_price', $_POST['quarter_price']);
	update_option('ciphp_month_price', $_POST['month_price']);
	update_option('erphp_life_times', $_POST['life_times']);
	update_option('erphp_year_times', $_POST['year_times']);
    update_option('erphp_quarter_times', $_POST['quarter_times']);
	update_option('erphp_month_times', $_POST['month_times']);

	echo'<div class="updated settings-error"><p>更新成功！</p></div>';

}
/////////////////////////////////////////////////www.mobantu.com   82708210@qq.com
$ciphp_life_price    = get_option('ciphp_life_price');
$ciphp_year_price    = get_option('ciphp_year_price');
$ciphp_quarter_price = get_option('ciphp_quarter_price');
$ciphp_month_price  = get_option('ciphp_month_price');
$erphp_life_times    = get_option('erphp_life_times');
$erphp_year_times    = get_option('erphp_year_times');
$erphp_quarter_times = get_option('erphp_quarter_times');
$erphp_month_times  = get_option('erphp_month_times');
if(current_user_can('administrator')){
?>

	<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">

		<h2>会员价格设置</h2>
		<p>如不需要某个VIP类型，可留空价格</p>
		<table class="form-table">
			<tr>
				<th valign="top" width="30%"><strong>终身VIP</strong></th>
				<td><input type="number" id="life_price" name="life_price"
					value="<?php echo $ciphp_life_price ; ?>" class="regular-text" /><?php echo get_option('ice_name_alipay');?>
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包年VIP</strong></th>
				<td><input type="number" id="year_price" name="year_price"
					value="<?php echo $ciphp_year_price ; ?>" class="regular-text" /><?php echo get_option('ice_name_alipay');?>
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包季VIP</strong></th>
				<td><input type="number" id="quarter_price" name="quarter_price"
					value="<?php echo $ciphp_quarter_price; ?>" class="regular-text" /><?php echo get_option('ice_name_alipay');?>
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包月VIP</strong></th>
				<td><input type="number" id="month_price" name="month_price"
					value="<?php echo $ciphp_month_price; ?>" class="regular-text" /><?php echo get_option('ice_name_alipay');?>
				</td>
			</tr>
		</table>

		<h2>会员每天下载资源个数限制</h2>
		<p>留空则不限制，这里的下载个数指所有资源合计每天下载的资源个数，仅对VIP有免费下载权限的资源有效，单独购买的资源无效</p>
		<table class="form-table">
			<tr>
				<th valign="top" width="30%"><strong>终身VIP</strong></th>
				<td><input type="number" id="life_times" name="life_times"
					value="<?php echo $erphp_life_times ; ?>" class="regular-text" min="0" step="1"/>个
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包年VIP</strong></th>
				<td><input type="number" id="year_times" name="year_times"
					value="<?php echo $erphp_year_times ; ?>" class="regular-text" min="0" step="1"/>个
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包季VIP</strong></th>
				<td><input type="number" id="quarter_times" name="quarter_times"
					value="<?php echo $erphp_quarter_times; ?>" class="regular-text" min="0" step="1"/>个
				</td>
			</tr>
			<tr>
				<th valign="top" width="30%"><strong>包月VIP</strong></th>
				<td><input type="number" id="month_times" name="month_times"
					value="<?php echo $erphp_month_times; ?>" class="regular-text" min="0" step="1"/>个
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<p class="submit">
						<input type="submit" name="Submit" value="保存设置" class="button-primary" />
					</p>
				</td>
			</tr>
		</table>
	</form>
</div>
<?php }?>