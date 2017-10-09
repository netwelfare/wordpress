<?php
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请登录系统');
}
?>
<?php 
////www.mobantu.com    82708210@qq.com
if($_POST)
{
	$money=$wpdb->escape($_POST['ice_money']);
	$user_name=$wpdb->escape($_POST['user_id']);
	$user_info=get_user_by('login', $user_name);
	//get_the_author_meta( 'user_login', wp_get_current_user()->ID )
	$okMoney=erphpGetUserOkMoney();
	if($okMoney < $money)
	{
		showMsgNotice("当前可用余额不足完成此次转账！请充值后重试!");
	}
	elseif($money > 0)
	{
		if($user_info)
		{
			$user_id=$user_info->ID;
			if(addUserMoney(wp_get_current_user()->ID, '-'.$money))
			{
				$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
				VALUES ('-$money','".date("y").mt_rand(10000000,99999999)."','".wp_get_current_user()->ID."','".date("Y-m-d H:i:s")."',1,'3','".date("Y-m-d H:i:s")."','')";
				$wpdb->query($sql);
				if(addUserMoney($user_id, $money))
				{
					$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
					VALUES ('$money','".date("y").mt_rand(10000000,99999999)."','".$user_id."','".date("Y-m-d H:i:s")."',1,'2','".date("Y-m-d H:i:s")."','')";
					$wpdb->query($sql);
					echo "<font color='green'>转账成功!</font>";
				}
				else
				{
					showMsgNotice("转账失败，如果您的金额已被扣除，请联系管理员！");
				}
			}
			else
			{
				showMsgNotice("转账失败");
			}
	
		}
		else
		{
			
			showMsgNotice("不存在的用户");
		}
	}
	else
	{
		showMsgNotice("请输入正确金额");
	}
}
?>
<div class="wrap">
<script type="text/javascript">
function checkFm()
{
	if(document.getElementById("ice_money").value=="")
	{
		alert('请输入金额');
		return false;
	}

}
</script>
<form action="" method="post" onsubmit="return checkFm();">

        <h2>站内转账</h2>
        <table class="form-table">
			<tr>
                <td valign="top" width="30%"><strong>对方用户名</strong><br />
                </td>
                <td>
                 <input type="text" id="user_id" name="user_id" maxlength="50" size="50" />
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>转账金额</strong><br />
                </td>
                <td>
                <input type="text" id="ice_money" name="ice_money" maxlength="50" size="50" />
                    (请输入一个整数)
                </td>
            </tr>
            
    </table>
        <br /> <br />
        <table> <tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="转账" class="button-primary" onclick="return confirm('确认转账?');"/>
            </p>
        </td>

        </tr> </table>

</form>
			</div>
