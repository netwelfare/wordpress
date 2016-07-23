<style>
    .titlebar { background:#F6F6F6; padding:5px;}
</style>
<script>
</script>
<div class="wrap" style="margin: 10px;">
    <div class="titlebar"><h2>Baidu Links Submit</h2></div>
    <h3>介绍 & 描述</h3>
    <div>
        <ul>Baidu Links Submit 帮助您更快，更方便的向百度Spider提交链接:
            <li>　1) 通过Baidu Links Submit您可以将站点当天新产出链接立即推送给百度，以保证新链接可以及时被百度收录。</li>
            <li>　2) Baidu Search 百度搜索将从您的网站更好的显示网页结果。</li>
            <li>　3) <a href="https://zhanzhang.baidu.com/linksubmit/index"  target="_black">site & token </a> 即您提交链接时候用于身份认证的串。</li>
            <li>使用本插件时，如果问题，请向<a href="https://zhanzhang.baidu.com/" target="_blank">百度站长平台</a>管理员反馈中心发出意见或建议。</li>
        </ul>
    </div>
    <h3>当日限额 & 提交量</h3>
    <div>
        <?php
            if(isset($output['limit'])) {
                $out = "今日提交限额 " . $output['limit'] . ", 已提交 " . $output['success'] . ", 剩余 " . ($output['limit'] - $output['success']) . ".";
                echo $out;
            }
            else {
                echo "您的site和token可能不正确！！！";
            }
        ?>
    </div>
    <div>
        <h3><?php echo '设置'?></h3>
        <form name="form1" method="post" action="<?php echo wp_nonce_url("./admin.php?page=" . "baidu_admin_links"); ?>">
            <hr>
            <fieldset>
                <legend>
                    Site:　　　 　<input type="text" name="site" value="<?php echo Baidu_common::$baidu_options['site']; ?>" placeholder="已验证的站点"/>
                </legend>
            </fieldset>
            <br>
            <fieldset>
                <legend>
                    Token:　　　<input type="text" name="token" value="<?php echo Baidu_common::$baidu_options['token']; ?>" placeholder="用户token值"/>
                </legend>
            </fieldset>
            <br>
            <fieldset>
                <legend>
                    Log:　　　　 <input type="checkbox" name="log_switch" <?php if(isset(Baidu_common::$baidu_options['log_switch']) &&Baidu_common::$baidu_options['log_switch']==1 ) echo 'checked'?> value="1" />
                </legend>
            </fieldset>
            <br>
            <fieldset class="submit">
                <input type="submit" name="submit" value="更新"/>
            </fieldset>
        </form>
    </div>
</div>