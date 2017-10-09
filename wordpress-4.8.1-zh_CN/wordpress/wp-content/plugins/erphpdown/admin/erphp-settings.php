<?php
/**
 * setting
 www.mobantu.com
 E-mail:82708210@qq.com
 */
if ( !defined('ABSPATH') ) {exit;}

if(isset($_POST['Submit'])) {
	
	update_option('erphpdown_alipay_type', trim($_POST['erphpdown_alipay_type']));
	update_option('ice_ali_partner', trim($_POST['ice_ali_partner']));
	update_option('ice_ali_security_code', trim($_POST['ice_ali_security_code']));
	update_option('ice_ali_seller_email', trim($_POST['ice_ali_seller_email']));
	update_option('ice_ali_seller_name', trim($_POST['ice_ali_seller_name']));
	update_option('ice_ali_money_limit', trim($_POST['ice_ali_money_limit']));
	update_option('ice_ali_money_site', trim($_POST['ice_ali_money_site']));
	update_option('ice_ali_money_ref', trim($_POST['ice_ali_money_ref']));
    update_option('ice_ali_money_reg', trim($_POST['ice_ali_money_reg']));
	//update_option('erphp_aff_url', $erphp_aff_url);
	//update_option('ice_ali_money_url', $ice_ali_money_url);
	update_option('erphp_mycred', trim($_POST['erphp_mycred']));
	update_option('erphp_to_mycred', trim($_POST['erphp_to_mycred']));
	update_option('ice_tips', trim($_POST['ice_tips']));
    update_option('erphpdown_downkey', trim($_POST['erphpdown_downkey']));
	update_option('ice_name_alipay', trim($_POST['ice_name_alipay']));
	update_option('ice_proportion_alipay', trim($_POST['ice_proportion_alipay']));
	update_option('ice_payapl_api_uid', trim($_POST['ice_payapl_api_uid']));
	update_option('ice_payapl_api_pwd', trim($_POST['ice_payapl_api_pwd']));
	update_option('ice_payapl_api_md5', trim($_POST['ice_payapl_api_md5']));
	update_option('ice_payapl_api_rmb', trim($_POST['ice_payapl_api_rmb']));   
	update_option('erphpdown_xhpay_appid', trim($_POST['erphpdown_xhpay_appid']));
	update_option('erphpdown_xhpay_appsecret', trim($_POST['erphpdown_xhpay_appsecret']));
	update_option('ice_china_bank_uid', trim($_POST['ice_china_bank_uid']));
	update_option('ice_china_bank_pwd', trim($_POST['ice_china_bank_pwd']));
	update_option('erphpdown_tenpay_uid', trim($_POST['erphpdown_tenpay_uid']));
	update_option('erphpdown_tenpay_pwd', trim($_POST['erphpdown_tenpay_pwd']));
	update_option('ice_weixin_mchid', trim($_POST['ice_weixin_mchid']));
	update_option('ice_weixin_appid', trim($_POST['ice_weixin_appid']));
	update_option('ice_weixin_key', trim($_POST['ice_weixin_key']));
	update_option('ice_weixin_secret', trim($_POST['ice_weixin_secret']));
    update_option('erphpdown_zfbjk_uid', trim($_POST['erphpdown_zfbjk_uid']));
    update_option('erphpdown_zfbjk_key', trim($_POST['erphpdown_zfbjk_key']));
    update_option('erphpdown_zfbjk_alipay', trim($_POST['erphpdown_zfbjk_alipay']));
    update_option('erphpdown_zfbjk_name', trim($_POST['erphpdown_zfbjk_name']));
    update_option('erphpdown_zfbjk_qr', trim($_POST['erphpdown_zfbjk_qr']));
	
	echo'<div class="updated settings-error"><p>更新成功！</p></div>';
	
}

$erphpdown_alipay_type = get_option('erphpdown_alipay_type');
$ice_ali_partner       = get_option('ice_ali_partner');
$ice_ali_security_code = get_option('ice_ali_security_code');
$ice_ali_seller_email  = get_option('ice_ali_seller_email');
$ice_ali_seller_name   = get_option('ice_ali_seller_name');
$ice_ali_money_limit    = get_option('ice_ali_money_limit');
$ice_ali_money_site    = get_option('ice_ali_money_site');
$ice_ali_money_ref    = get_option('ice_ali_money_ref');
$ice_ali_money_reg    = get_option('ice_ali_money_reg');
//$erphp_aff_url    = get_option('erphp_aff_url');
//$ice_ali_money_url    = get_option('ice_ali_money_url');
$erphp_mycred    = get_option('erphp_mycred');
$erphp_to_mycred    = get_option('erphp_to_mycred');
$ice_tips    = get_option('ice_tips');
$erphpdown_downkey    = get_option('erphpdown_downkey')?get_option('erphpdown_downkey'):'erphpdown';
$ice_name_alipay    = get_option('ice_name_alipay');
$ice_proportion_alipay    = get_option('ice_proportion_alipay');
$ice_payapl_api_uid    = get_option('ice_payapl_api_uid');
$ice_payapl_api_pwd    = get_option('ice_payapl_api_pwd');
$ice_payapl_api_md5    = get_option('ice_payapl_api_md5');
$ice_payapl_api_rmb    = get_option('ice_payapl_api_rmb');
$erphpdown_xhpay_appid    = get_option('erphpdown_xhpay_appid');
$erphpdown_xhpay_appsecret    = get_option('erphpdown_xhpay_appsecret');
$ice_china_bank_uid  = get_option('ice_china_bank_uid');
$ice_china_bank_pwd  = get_option('ice_china_bank_pwd');
$erphpdown_tenpay_uid  = get_option('erphpdown_tenpay_uid');
$erphpdown_tenpay_pwd  = get_option('erphpdown_tenpay_pwd');
$ice_weixin_mchid  = get_option('ice_weixin_mchid');
$ice_weixin_appid  = get_option('ice_weixin_appid');
$ice_weixin_key  = get_option('ice_weixin_key');
$ice_weixin_secret  = get_option('ice_weixin_secret');
$erphpdown_zfbjk_uid  = get_option('erphpdown_zfbjk_uid');
$erphpdown_zfbjk_key  = get_option('erphpdown_zfbjk_key');
$erphpdown_zfbjk_alipay  = get_option('erphpdown_zfbjk_alipay');
$erphpdown_zfbjk_name  = get_option('erphpdown_zfbjk_name');
$erphpdown_zfbjk_qr  = get_option('erphpdown_zfbjk_qr');
?>
<style>.form-table th{font-weight: 400}</style>
<div class="wrap">
    <h1>Erphpdown设置</h1>
    <form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
        <h2>【基础设置】</h2>
        <table class="form-table">
            <tr>
                <th valign="top">货币昵称</th>
                <td>
                <input type="text" id="ice_name_alipay" name="ice_name_alipay" value="<?php echo $ice_name_alipay;?>" class="regular-text"/> （例如：模板兔币）
                </td>
            </tr>
            <tr>
                <th valign="top">充值比例</th>
                <td>
                <input type="number" id="ice_proportion_alipay" name="ice_proportion_alipay" value="<?php echo $ice_proportion_alipay;?>" required="required" class="regular-text"/> （请输入一个整数，例如：10，代表1元=10 模板兔币）
                </td>
            </tr> 
            <tr>
                <th valign="top">mycred积分兑换</th>
                <td>
                    <input type="checkbox" id="erphp_mycred" name="erphp_mycred" value="yes" <?php if($erphp_mycred == 'yes') echo 'checked'; ?> /> （需安装<a href="https://wordpress.org/plugins/mycred/" target="_blank">mycred插件</a>与<a href="http://www.mobantu.com/6017.html" target="_blank">erphpdown集成mycred插件</a>） 兑换比例：
                    <input type="number" id="erphp_to_mycred" name="erphp_to_mycred" value="<?php echo $erphp_to_mycred; ?>" style="width:100px" />（输入100则为 100积分 = 1模板兔币）
                </td>
            </tr>
            <tr>
                <th valign="top">推广消费提成（百分点）</th>
                <td>
                <input type="number" id="ice_ali_money_ref" name="ice_ali_money_ref" value="<?php echo $ice_ali_money_ref; ?>" required="required" class="regular-text"/>% （请输入一个整数）
                </td>
            </tr>
            <tr>
                <th valign="top">推广注册奖励</th>
                <td>
                <input type="number" id="ice_ali_money_reg" name="ice_ali_money_reg" value="<?php echo $ice_ali_money_reg; ?>" required="required" class="regular-text"/>模板兔币 （请输入一个整数）
                </td>
            </tr>
			<tr>
                <th valign="top">提现规则</th>
                <td>
                <input type="number" id="ice_ali_money_limit" name="ice_ali_money_limit" value="<?php echo $ice_ali_money_limit; ?>" required="required" class="regular-text"/> 模板兔币以上方可提现 （请输入一个整数）
                </td>
            </tr>
        	<tr>
                <th valign="top">提现手续费（百分点）</th>
                <td>
                <input type="number" id="ice_ali_money_site" name="ice_ali_money_site" value="<?php echo $ice_ali_money_site; ?>" required="required" class="regular-text"/>% （请输入一个整数）
                </td>
            </tr>
            <tr>
                <th valign="top">购买说明</th>
                <td>
                <textarea id="ice_tips" name="ice_tips" placeholder="客服QQ：82708210" rows="5" cols="70"><?php echo $ice_tips; ?></textarea>
                </td>
            </tr>
			<tr>
                <th valign="top">下载标识码</th>
                <td>
                <input type="text" id="erphpdown_downkey" name="erphpdown_downkey" value="<?php echo $erphpdown_downkey;?>" class="regular-text"/> （建议设置一个随机字符串，长度为8位左右即可，不要告知他人）
                </td>
            </tr>
        </table>
        <br />
        <h2>【支付接口设置】</h2>
        <div>以下所有接口均属于第三方服务，我们仅提供技术集成服务</div>
        <h3>支付宝</h3>
        <table class="form-table">
        	<tr>
                <th valign="top">接口类型</th>
                <td>
                    <select name="erphpdown_alipay_type">
                      <option value="create_direct_pay_by_user" <?php if($erphpdown_alipay_type == 'create_direct_pay_by_user') echo 'selected="selected"';?>>即时到账</option>
                      <option value ="create_partner_trade_by_buyer" <?php if($erphpdown_alipay_type == 'create_partner_trade_by_buyer') echo 'selected="selected"';?>>担保交易</option>
                      <option value ="trade_create_by_buyer" <?php if($erphpdown_alipay_type == 'trade_create_by_buyer') echo 'selected="selected"';?>>双接口</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th valign="top">合作者身份(Partner ID)</th>
                <td>
                <input type="text" id="ice_ali_partner" name="ice_ali_partner" value="<?php echo $ice_ali_partner ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">安全校验码(Key)</th>
                <td>
                <input type="text" id="ice_ali_security_code" name="ice_ali_security_code" value="<?php echo $ice_ali_security_code; ?>" class="regular-text"/>
                </td>
            </tr>
                        <tr>
                <th valign="top">支付宝收款账号</th>
                <td>
                <input type="text" id="ice_ali_seller_email" name="ice_ali_seller_email" value="<?php echo $ice_ali_seller_email; ?>" class="regular-text"/>
                </td>
            </tr>
                                    <tr>
                <th valign="top">收款方名称</th>
                <td>
                <input type="text" id="ice_ali_seller_name" name="ice_ali_seller_name" value="<?php echo $ice_ali_seller_name; ?>" class="regular-text"/>
                </td>
            </tr>
    </table>
        <br />
        <h3>微信支付</h3>
        <div style="color:red">由于微信支付的接口在php类里是const常量，无法修改，所以这里的配置无效（但还是需填写上哦），需要用户自行修改以下文件<br><br>
        /wp-content/plugins/erphpdown/payment/weixin/lib/WxPay.Config.php 文件里的 APPID、MCHID、KEY、APPSECRET</div>
        <table class="form-table">
            <tr>
                <th valign="top">商户号(MCHID)</th>
                <td>
                <input type="text" id="ice_weixin_mchid" name="ice_weixin_mchid" value="<?php echo $ice_weixin_mchid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">APPID</th>
                <td>
                <input type="text" id="ice_weixin_appid" name="ice_weixin_appid" value="<?php echo $ice_weixin_appid; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">商户支付密钥(KEY)</th>
                <td>
                <input type="text" id="ice_weixin_key" name="ice_weixin_key" value="<?php echo $ice_weixin_key; ?>" class="regular-text"/><br>
                设置地址：<a href="https://pay.weixin.qq.com/index.php/account/api_cert" target="_blank">https://pay.weixin.qq.com/index.php/account/api_cert </a>，建议为32位字符串
                </td>
            </tr>
            <tr>
                <th valign="top">公众帐号Secret</th>
                <td>
                <input type="text" id="ice_weixin_secret" name="ice_weixin_secret" value="<?php echo $ice_weixin_secret; ?>" class="regular-text"/>
                </td>
            </tr>
    </table>
        <br />
		        <h3>PayPal</h3>
		        <div style="color:red">此接口暂时暂停使用</div>
        <table class="form-table">
            <tr>
                <th valign="top">API帐号</th>
                <td>
                <input type="text" id="ice_payapl_api_uid" name="ice_payapl_api_uid" value="<?php echo $ice_payapl_api_uid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">API密码</th>
                <td>
                <input type="text" id="ice_payapl_api_pwd" name="ice_payapl_api_pwd" value="<?php echo $ice_payapl_api_pwd; ?>" class="regular-text"/>
                </td>
            </tr>
                        <tr>
                <th valign="top">API签名</th>
                <td>
                <input type="text" id="ice_payapl_api_md5" name="ice_payapl_api_md5" value="<?php echo $ice_payapl_api_md5; ?>" class="regular-text"/>
                </td>
            </tr>
			 <tr>
                <th valign="top">汇率</th>
                <td>
                <input type="text" id="ice_payapl_api_rmb" name="ice_payapl_api_rmb" value="<?php echo $ice_payapl_api_rmb; ?>" class="regular-text"/>
                </td>
            </tr>
    </table>
    
    <br />
		        <h3>银联</h3>
        <table class="form-table">
            <tr>
                <th valign="top">商户号</th>
                <td>
                <input type="text" id="ice_china_bank_uid" name="ice_china_bank_uid" value="<?php echo $ice_china_bank_uid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">MD5密钥</th>
                <td>
                <input type="text" id="ice_china_bank_pwd" name="ice_china_bank_pwd" value="<?php echo $ice_china_bank_pwd; ?>" class="regular-text"/>
                </td>
            </tr>
    </table>
    <br />
		        <h3>财付通</h3>
                <p style="color:red">此接口暂时暂停使用</p>
        <table class="form-table">
            <tr>
                <th valign="top">商户号</th>
                <td>
                <input type="text" id="erphpdown_tenpay_uid" name="erphpdown_tenpay_uid" value="<?php echo $erphpdown_tenpay_uid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">密钥</th>
                <td>
                <input type="text" id="erphpdown_tenpay_pwd" name="erphpdown_tenpay_pwd" value="<?php echo $erphpdown_tenpay_pwd; ?>" class="regular-text"/>
                </td>
            </tr>
    </table>
    <br />
		        <h3>WP开放平台支付（非WP官方）</h3>
		        <div>关于此接口的安全稳定性，请使用者自行把握（接口方可能跑路给你带来的损失），我们只提供技术集成服务，接口申请地址：<a href="http://mp.wordpressopen.com/sign-up.html" target="_blank" rel="nofollow">点击查看</a></div>
        <table class="form-table">
            <tr>
                <th valign="top">appid</th>
                <td>
                <input type="text" id="erphpdown_xhpay_appid" name="erphpdown_xhpay_appid" value="<?php echo $erphpdown_xhpay_appid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">appsecret</th>
                <td>
                <input type="text" id="erphpdown_xhpay_appsecret" name="erphpdown_xhpay_appsecret" value="<?php echo $erphpdown_xhpay_appsecret; ?>" class="regular-text"/>
                </td>
            </tr>
			 
    </table>
    <br />
                <h3>支付宝免签即时到账</h3>
                <p>关于此接口的安全稳定性，请使用者自行把握，我们只提供技术集成服务，接口申请地址：<a href="http://t.cn/RV1hAgJ" target="_blank">点击查看</a></p>
                <font color="red">通知网址：<?php bloginfo('url')?>/wp-content/plugins/erphpdown/payment/alipay_jk/notify_url.php</font>
        <table class="form-table">
            <tr>
                <th valign="top">商户id</th>
                <td>
                <input type="text" id="erphpdown_zfbjk_uid" name="erphpdown_zfbjk_uid" value="<?php echo $erphpdown_zfbjk_uid ; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">商户秘钥</th>
                <td>
                <input type="text" id="erphpdown_zfbjk_key" name="erphpdown_zfbjk_key" value="<?php echo $erphpdown_zfbjk_key; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">支付宝账号</th>
                <td>
                <input type="text" id="erphpdown_zfbjk_alipay" name="erphpdown_zfbjk_alipay" value="<?php echo $erphpdown_zfbjk_alipay; ?>" class="regular-text"/>
                </td>
            </tr>
            <tr>
                <th valign="top">收款人姓名</th>
                <td>
                <input type="text" id="erphpdown_zfbjk_name" name="erphpdown_zfbjk_name" value="<?php echo $erphpdown_zfbjk_name; ?>" class="regular-text"/>（用于校验真实姓名）
                </td>
            </tr>
            <tr>
                <th valign="top">收款二维码图片地址</th>
                <td>
                <input type="text" id="erphpdown_zfbjk_qr" name="erphpdown_zfbjk_qr" value="<?php echo $erphpdown_zfbjk_qr; ?>" placeholder="http://"  class="regular-text"/>（用于手机扫码转账）
                </td>
            </tr>
    </table>
    <p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            <div >技术支持：mobantu.com <a href="http://www.mobantu.com/6658.html" target="_blank">使用教程>></a></div>
    </p>      
    </form>
</div>