<!-- //底部模板 -->
<div id="footer" class="clearfix">
    <div class="con_box ">
	      <?php wp_reset_query();?>
          <div class="footer_bug">
			 <a>版权所有：木秀林网</a> | <a>运行时间：
				<?php 
				function count_days($a,$b)
				{
					$startdate=strtotime($a);   
					$enddate=strtotime($b);    
					$days=round(abs($enddate-$startdate)/3600/24) ;
					return $days; 
				}
				$datetime1 = "now";  
				$datetime2 = "2015-08-15";  
				$interval = count_days($datetime1, $datetime2);  
				echo $interval; 
				?>
				天</a> 
          </div>
    </div>

    <div class="copyright">
		<p>
		<strong>友情链接：<a target="_blank" href="http://www.nameshow.com/">域名秀</a>&nbsp;|&nbsp;<a target="_blank" href="http://www.mimidi.com/">米米地</a>&nbsp;|&nbsp;<a target="_blank" href="http://www.zhangmimi.com/">张米米博客</a>&nbsp;|&nbsp;<a target="_blank" href="http://www.mirenblog.com/">米人与米博客</a>&nbsp;|&nbsp;<a target="_blank" href="http://suanbing.com/">酸饼博客</a>&nbsp;|&nbsp;<a target="_blank" href="http://xiaodayou.com/">萧打油博客</a>&nbsp;|&nbsp;<a target="_blank" href="http://www.shuoyuming.com/">池远说域名</a><br />
		
		
	
		</p>
    </div>
   
   <div class="footer_right">
    <a target="_blank" href="http://www.muxiulin.com/"><img atl="木秀林" src="<?php bloginfo('template_url'); ?>/images/bottom-logo.png" width="150" 
	   height="60">
	</a>
	<p>Powered by <a href="http://www.muxiulin.com/"  target="_blank">muxiulin.com</a>.</p>
   </div>
   <?php wp_footer(); ?>
</div>

</div>
</body>
</html>