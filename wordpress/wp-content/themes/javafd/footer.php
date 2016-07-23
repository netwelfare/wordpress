<!-- //底部模板 -->
<div id="footer" class="clearfix">
    <div class="con_box ">
	      <?php wp_reset_query();?>
          <div class="footer_bug">
			 <a>版权所有：Java辅导网</a> | <a>运行时间：
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

    </div>
   
   <div class="footer_right">
    
   </div>
   <?php wp_footer(); ?>
</div>

</div>
</body>
</html>