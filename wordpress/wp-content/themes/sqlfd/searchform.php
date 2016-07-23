<div id="search">
	<form method="get" id="searchform" action="<?php bloginfo('home'); ?>" target="_blank">
	<input id="s"  type="text" name="s" disableautocomplete autocomplete="off" onfocus="if(this.value=='请输入搜索内容...'){this.value=''};" onblur="if(this.value==''){this.value='请输入搜索内容...'};" value="<?php echo wp_specialchars($s, 1); ?>" />
	<input id="s2" type="submit" name="Submit" value="搜索">	
	</form>
</div>
<div class='clear'></div>	
