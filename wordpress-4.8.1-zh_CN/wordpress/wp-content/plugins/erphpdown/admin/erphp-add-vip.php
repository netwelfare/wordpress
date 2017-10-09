<div class="wrap">
<?php
if ( !defined('ABSPATH') ) {exit;}
if(isset($_POST['Submit']) && $_POST['Submit']=='确认赠送')
{
	//check
	$user_info=get_user_by('login', $wpdb->escape($_POST['vipusername']));
	$uid=$user_info->ID;
	$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
	if($userType >6 && $userType < 11 && $uid)
	{
		$priceArr=array('7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
		$priceType=$priceArr[$userType];
		$price=get_option($priceType);
		if(empty($price) || $price<1)
		{
			echo '<div class="error settings-error"><p>此类型的会员价格错误，请稍候重试！</p></div>';
		}
		addUserMoney($uid,'0'); // 添加记录
		if(userSetMemberSetData($userType,$uid))
		{
			addVipLogByAdmin($price, $userType, $uid);
			echo '<div class="updated settings-error"><p>赠送VIP成功！</p></div>';
		}
		else
		{
			echo '<div class="error settings-error"><p>赠送VIP失败！</p></div>';
		}
		
		
	}
	else
	{
		echo '<div class="error settings-error"><p>会员类型错误！</p></div>';
	}
}

$ciphp_life_price    = get_option('ciphp_life_price');
$ciphp_year_price    = get_option('ciphp_year_price');
$ciphp_quarter_price = get_option('ciphp_quarter_price');
$ciphp_month_price  = get_option('ciphp_month_price');

	?>
<form method="post"
	action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>"
	style="width: 70%; float: left;">

	<h2>后台赠送VIP服务</h2>
	<table class="form-table">
		
		<tr>
			<td valign="top" width="30%"><strong>VIP类型</strong><br />
			</td>
			<td><input type="radio" id="userType" name="userType" value="10"/>终身会员 --- <?php echo $ciphp_life_price.get_option('ice_name_alipay')?> <br /><input type="radio" id="userType" name="userType" value="9"/>包年会员 --- <?php echo $ciphp_year_price.get_option('ice_name_alipay')?> <br /> <input
				type="radio" id="userType" name="userType" value="8" />包季会员 --- <?php echo $ciphp_quarter_price.get_option('ice_name_alipay')?> <br />
				<input type="radio" id="userType" name="userType" value="7" checked />包月会员 --- <?php echo $ciphp_month_price.get_option('ice_name_alipay')?> 
			</td>
		</tr>
        <tr>
			<td valign="top" width="30%"><strong>被赠送用户登录名</strong><br />
			</td>
			<td><input type="text" name="vipusername">
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="Submit" value="确认赠送"
				onclick="return confirm('确认赠送?')" class="button-primary" />
			</td>
		</tr>
	</table>
</form>
</div>