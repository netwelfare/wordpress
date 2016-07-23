<!-- //底部模板 -->
<div id="footer" class="clearfix">
    <div class="con_box ">
	      <?php wp_reset_query();?>
          <div class="footer_bug">
			 <a>copyright：javasker</a> | <a>run days：
				<?php 
				function count_days($a,$b)
				{
					$startdate=strtotime($a);   
					$enddate=strtotime($b);    
					$days=round(abs($enddate-$startdate)/3600/24) ;
					return $days; 
				}
				$datetime1 = "now";  
				$datetime2 = "2015-08-08";  
				$interval = count_days($datetime1, $datetime2);  
				echo $interval; 
				?>
				day</a> 
          </div>
    </div>

    <div class="copyright">
		<p>
		<br />
		Copyright (c) 2015  www.javasker.com  All rights reserved 
		</p>
    </div>
   

   <div class="footer_right">
    <!--<a target="_blank" href="http://www.muxiulin.com/"><img atl="木秀林" src="<?php bloginfo('template_url'); ?>/images/bottom-logo.png" width="150" 
	   height="60">
	</a>
	<p>Powered by <a href="http://www.muxiulin.com/"  target="_blank">muxiulin.com</a>.</p>-->
   </div>
   <?php wp_footer(); ?>
</div>

</div>
</body>
</html>