<div class="wrap">
<?php
if(isset($_POST['Submit']) && $_POST['Submit']=='确认购买')
{
	//check
	if(!checkUsreMemberType())
	{
		return false;
	}
	$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
	$userType = $wpdb->escape($userType);
	if($userType >6 && $userType < 11)
	{
		$okMoney=erphpGetUserOkMoney();
		$priceArr=array('7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
		$priceType=$priceArr[$userType];
		$price=get_option($priceType);
		if(empty($price) || $price<1)
		{
			echo '<div class="error settings-error"><p>此类型的会员价格错误，请稍候重试！</p></div>';
		}
		elseif($okMoney < $price)
		{
			echo '<div class="error settings-error"><p>当前可用余额不足完成此次交易！请充值后重试！</p></div>';
		}
		elseif($okMoney >=$price)
		{
			if(erphpSetUserMoneyXiaoFei($price))//扣钱
			{
				if(userPayMemberSetData($userType))
				{
					addVipLog($price, $userType);
					//写入提成
					$user_info=wp_get_current_user();
					$RefMoney=$wpdb->get_row("select * from ".$wpdb->users." where ID=".$user_info->ID);
					if($RefMoney->father_id > 0){
						addUserMoney($RefMoney->father_id,$price*get_option('ice_ali_money_ref')*0.01);
					}
					echo '<div class="updated settings-error"><p>购买成功，您即可享受高级会员服务！</p></div>';
				}
				else
				{
					echo '<div class="error settings-error"><p>系统发生错误，请联系管理员！</p></div>';
				}
			}
			else
			{
				echo '<div class="error settings-error"><p>系统发生错误，请稍后重试！</p></div>';
			}
		}
		else
		{
			echo '<div class="error settings-error"><p>系统发生错误！</p></div>';
		}
	}
	else
	{
		echo '<div class="error settings-error"><p>会员类型错误！</p></div>';
	}
}
/////////////////////////////////////////////////www.mobantu.com   82708210@qq.com
$ciphp_life_price    = get_option('ciphp_life_price');
$ciphp_year_price    = get_option('ciphp_year_price');
$ciphp_quarter_price = get_option('ciphp_quarter_price');
$ciphp_month_price  = get_option('ciphp_month_price');

	$okMoney=erphpGetUserOkMoney();//判断余额
	?>
<form method="post"
	action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>"
	style="width: 70%; float: left;">

	<h2>购买VIP服务</h2>
	<table class="form-table">
		<tr>
			<td valign="top" width="30%"><strong>当前类型</strong><br /></td>
			<td><?php 
			$userTypeId=getUsreMemberType();
			if($userTypeId==7)
			{
				echo "包月会员";
			}
			elseif ($userTypeId==8)
			{
				echo "包季会员";
			}
			elseif ($userTypeId==9)
			{
				echo "包年会员";
			}
			elseif ($userTypeId==10)
			{
				echo "终身会员";
			}
			else 
			{
				echo '未购买任何会员服务';
			}
			?>,&nbsp;&nbsp;&nbsp;<?php if($userTypeId>6 && $userTypeId<10){?>到期时间：<?php echo $userTypeId>0 ?getUsreMemberTypeEndTime() :''?></td><?php }?>
		</tr>
		
		
		<tr>
			<td valign="top" width="30%"><strong>VIP类型</strong><br />
			</td>
			<td>
				<input type="radio" id="userType" name="userType" value="10" checked />终身会员 --- <?php echo $ciphp_life_price?><?php echo get_option('ice_name_alipay')?><br /> 
				<input type="radio" id="userType" name="userType" value="9" />包年会员 --- <?php echo $ciphp_year_price?><?php echo get_option('ice_name_alipay')?><br /> 
				<input type="radio" id="userType" name="userType" value="8" />包季会员 --- <?php echo $ciphp_quarter_price?><?php echo get_option('ice_name_alipay')?><br />
				<input type="radio" id="userType" name="userType" value="7" />包月会员 --- <?php echo $ciphp_month_price?><?php echo get_option('ice_name_alipay')?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="30%"><strong>可用余额</strong><br />
			</td>
			<td><?php echo sprintf("%.2f",$okMoney)?><?php echo get_option('ice_name_alipay')?>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" name="Submit" value="确认购买"
				onclick="return confirm('确认购买?')" class="button-primary" />
			</td>
		</tr>
		
		
	</table>
</form>
</div>