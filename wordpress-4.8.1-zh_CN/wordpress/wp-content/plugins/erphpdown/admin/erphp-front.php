<?php
if ( !defined('ABSPATH') ) {exit;}
if(isset($_POST['Submit'])) {
	update_option('erphp_url_front_vip', esc_sql(trim($_POST['erphp_url_front_vip'])));
	update_option('erphp_url_front_recharge', esc_sql(trim($_POST['erphp_url_front_recharge'])));
	update_option('erphp_url_front_login', esc_sql(trim($_POST['erphp_url_front_login'])));
	update_option('erphp_url_front_noadmin', esc_sql(trim($_POST['erphp_url_front_noadmin'])));
	update_option('erphp_url_front_userpage', esc_sql(trim($_POST['erphp_url_front_userpage'])));
    update_option('erphp_url_front_success', esc_sql(trim($_POST['erphp_url_front_success'])));
    update_option('erphp_post_types', $_POST['erphp_post_types']);
	echo'<div class="updated settings-error"><p>更新成功！</p></div>';
}

$erphp_url_front_vip = get_option('erphp_url_front_vip');
$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
$erphp_url_front_login = get_option('erphp_url_front_login');
$erphp_url_front_noadmin = get_option('erphp_url_front_noadmin');
$erphp_url_front_userpage = get_option('erphp_url_front_userpage');
$erphp_url_front_success = get_option('erphp_url_front_success');
$erphp_post_types = get_option('erphp_post_types');
?>
<style>.form-table th{font-weight: 400}</style>
<div class="wrap">
<h1>显示设置</h1>
    <form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
    <h3>文章类型设置</h3>
        <p>选择你所需要支持erphpdown的文章类型。</p>
        <table class="form-table">
        <tr>
        <th valign="top">文章类型</th>
        <td>
            <?php 
                $args = array('public' => true,);
                $post_types = get_post_types($args);
                foreach ( $post_types  as $post_type ) {
                    if($post_type != 'attachment'){
                        $postType = get_post_type_object($post_type);
            ?>
            <label>
                <input type="checkbox" name="erphp_post_types[]" value="<?php echo $post_type;?>" <?php if($erphp_post_types) {if(in_array($post_type,$erphp_post_types)) echo 'checked';}?>> <?php echo $postType->labels->singular_name;?>&nbsp;&nbsp;&nbsp;&nbsp;
            </label>
            <?php
                    }
                }
            ?>
        </td>
        </tr>
        </table>
        <br><br>
        <h3>前端设置</h3>
        <p>假如你主题集成了前端用户中心，并且包含了erphpdown插件功能，可以把相应链接填在此处！没有设置可不填！</p>
        <table class="form-table">
        	<tr>
                <th valign="top">禁止进后台</th>
                <td>
					<input type="checkbox" id="erphp_url_front_noadmin" name="erphp_url_front_noadmin" value="yes" <?php if($erphp_url_front_noadmin == 'yes') echo 'checked'; ?> /> （普通用户无法进后台，若开启此项，下面的升级VIP与充值地址均得设置为非后台的地址）
                </td>
            </tr>
            <tr>
                <th valign="top">前端用户中心地址</th>
                <td>
                <input type="text" id="erphp_url_front_userpage" name="erphp_url_front_userpage" value="<?php echo $erphp_url_front_userpage;?>" class="regular-text" placeholder="http://"/> 例如：http://www.mobantu.com/profile
                </td>
            </tr>
            <tr>
                <th valign="top">前端升级VIP地址</th>
                <td>
                <input type="text" id="erphp_url_front_vip" name="erphp_url_front_vip" value="<?php echo $erphp_url_front_vip ; ?>" class="regular-text" placeholder="http://" />
                </td>
            </tr>
            <tr>
                <th valign="top">前端充值地址</th>
                <td>
                <input type="text" id="erphp_url_front_recharge" name="erphp_url_front_recharge" value="<?php echo $erphp_url_front_recharge ; ?>" class="regular-text" placeholder="http://"/>
                </td>
            </tr>
            <tr>
                <th valign="top">支付成功跳转地址</th>
                <td>
                <input type="text" id="erphp_url_front_success" name="erphp_url_front_success" value="<?php echo $erphp_url_front_success;?>" class="regular-text" placeholder="http://"/>（必填）
                </td>
            </tr>
            <tr>
                <th valign="top">前端登录地址</th>
                <td>
                <input type="text" id="erphp_url_front_login" name="erphp_url_front_login" value="<?php echo $erphp_url_front_login ; ?>" class="regular-text" placeholder="http://"/>（不填则显示默认wp-login.php登录地址；链接的class为erphp-login-must）
                </td>
            </tr>
            
           
    </table><table> <tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>

        </tr> </table>
        

    </form>
</div>