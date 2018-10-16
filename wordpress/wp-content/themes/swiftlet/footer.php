<!-- //底部模板 -->
<div id="footer" class="clearfix">
    <div class="con_box ">
	            <?php wp_reset_query();if ( is_home()){ ?>
 	            <?php } ?>
          <div class="footer_bug">
			 <a>版权所有：金丝燕网</a> | <a>运行时间：
														<?php 
														function count_days($a,$b)
														{
														$startdate=strtotime($a);   
														$enddate=strtotime($b);    
														$days=round(abs($enddate-$startdate)/3600/24) ;
														return $days; 
														}
														$datetime1 = "now";  
														$datetime2 = "2015-01-25";  
														$interval = count_days($datetime1, $datetime2);  
														echo $interval; 
														?>天</a> 
          </div>
    </div>

    <div class="copyright">
		<p>
			免责声明：本站所有pdf书籍和教程视频均来自于互联网，由热心读者共同搜集，仅限于个人学习与研究，严禁用于商业用途。<br />
			原作者如果认为本站侵犯了您的版权,请及时告知,本站会立即删除!
		</p>
        <p>
            金丝燕网站 Copyright (c) 2018  www.swiftlet.net  All rights reserved 
        </p>
<!-- /powered -->
   </div>
   

   <div class="footer_right">
    <a target="_blank" href="http://www.swiftlet.net/"><img atl="金丝燕网" src="/wp-content/themes/swiftlet/images/logo.png" width="150" height="60"></a>
	<p>Powered by <a href="http://www.swiftlet.net"  target="_blank">swiftlet</a>.</p>
	</div>
   <?php wp_footer(); ?>
</div>

</div>
	<script type="text/javascript" src="http://swiftlet.net/wp-content/themes/swiftlet/ntes/js/easyCore.js"></script>
	<script type="text/javascript" src="http://swiftlet.net/wp-content/themes/swiftlet/ntes/js/dialog.js"></script>
	
	<script type="text/javascript">
	(function(window, $, undefined){
		$("body").delegate("#closePop","click",function(){$.dialog();});
		function showDialog(title,content){
					return $.dialog({
						title: title,
						content: "<div class='popo_msg'>" +content+'<p class="t_c mt20"><input type="button" value="关闭" id="closePop" class="urs_login_btn"/></p></div>',
						width: 496,
						button: ''
					});
				};
		
		var ck = $.cookie("hasVisitedx");
		if(ck==1) return;
		setTimeout(function(){showDialog("网站公告", "<p><a href='http://swiftlet.net/archives/2704' target='_blank'>您好，欢迎关注：《冲击名企，冲击架构师》......</a></p><p class='mtb10'></p>");},30000);
		$.cookie("hasVisitedx","1",{expires:2, path:"/"});
	})(window, jQuery);
	</script>
	
</body>
</html>