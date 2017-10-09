<?php function showMsg($msg){?>
<html lang="zh-CN">
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css" type="text/css" />
        <title>文件下载</title>
    </head>
    <body>
    	<div id="erphpdown-download">
    		<h1>文件下载：</h1>

            <!-- 以下内容不要动 -->
    		<div class="msg"><?php echo $msg;?></div>
            <!-- 以上内容不要动 -->

        </div>
        <div id="erphpdown-download-copyright">
        	&copy;<?php echo date("Y")." <a href='".get_bloginfo("url")."'>".get_bloginfo("name")."</a>";?>
        </div>
    </body>
</html>
<?php exit;}
