<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<link href="<?php bloginfo('stylesheet_url'); ?>?20150305" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/includes/<?php echo get_option('mytheme_colour'); ?>.css" type="text/css">
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ntes/css/common.css?201602172119" />
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/ntes/css/core.css?201602172119" />
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.min.js"></script>
	<script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.js">\x3C/script>')</script>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery_cmhello.js"></script>
	
	<?php if ( is_home() ){ ?>
	<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/jquery.cycle.all.min.js"></script>
	<?php } ?>
	<script type="text/javascript">HcmsLazyload("<?php bloginfo('template_url'); ?>/images/space.gif");</script>
	<!--[if IE 6]>
	<link href="<?php bloginfo('template_url'); ?>/ie6.css" rel="stylesheet" type="text/css" />
	<script src="http://letskillie6.googlecode.com/svn/trunk/letskillie6.zh_CN.pack.js"></script>
	<script src="<?php bloginfo('template_url'); ?>/js/PNG.js"></script>
	<script>DD_belatedPNG.fix('.png_bg');</script>
	<![endif]-->
	<title><?php if (is_home() ) {?><?php bloginfo('name'); ?> - <?php bloginfo('description'); ?><?php } else {?><?php wp_title('-', true, 'right'); ?> <?php bloginfo('name'); ?> <?php } ?></title>
	<?php if (is_home())
	{
	$description = get_option('swt_description');
	$keywords = get_option('swt_keywords');
	}
	elseif (is_category())
	{
	$description = category_description();
	$keywords = single_cat_title('', false);
	}
	elseif (is_tag())
	{
	$description = tag_description();
    $keywords = single_tag_title('', false);
	}
	elseif (is_single())
	{
     if ($post->post_excerpt) 
	 {
	  $description = $post->post_excerpt;
	  $keywords = $post->post_excerpt;
	 } 
	 else 
	 {
	  $description = substr(strip_tags($post->post_content),0,240);
	 }
    /**$keywords = "";
    $tags = wp_get_post_tags($post->ID);
    foreach ($tags as $tag ) 
	{
	 $keywords = $keywords . $tag->name . ", ";
	}
	$keywords=substr($keywords,0,-2);**/
	}
	?>
	<meta name="keywords" content="<?php echo $keywords ?>" />
	<meta name="description" content="<?php echo $description?>" />
	<link id="favicon" href="http://www.swiftlet.net/swiftlet64.ico" rel="icon" type="image/x-icon" />
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>
	<script>
		var _hmt = _hmt || [];
		(function() {
		  var hm = document.createElement("script");
		  hm.src = "//hm.baidu.com/hm.js?81515e5f361b45faf4b83570f8e95ff8";
		  var s = document.getElementsByTagName("script")[0]; 
		  s.parentNode.insertBefore(hm, s);
		})();
	</script>

</head>

<body <?php body_class(); ?>>
<div id="zhu_beijin">
<div id="ssmay">   
        <ul id="navleft">		
			
        </ul>
        <div id="navright" class="m-r-8">
            <ul>
			   <?php
                 global $user_ID, $user_identity, $user_email, $user_login;
                 get_currentuserinfo();
                 if (!$user_ID) {
               ?>
				<?php } 
                       else { ?>
				<div id="navrightr" class="m-r-8"><div class="navrightlogin"><ul><li><a href="<?php bloginfo('url') ?>/wp-admin/" target="_blank">控制面板</a></li>
                <li><div class="topline"></div></li>
				<li><a href="<?php bloginfo('url'); ?>/wp-admin/post-new.php" target="_blank">撰写文章</a></li><li><div class="topline"></div></li>
				<li><a href="<?php bloginfo('url'); ?>/wp-admin/edit-comments.php" target="_blank">评论管理</a></li><li><div class="topline"></div></li>
				<li><a href="<?php bloginfo('url'); ?>/wp-login.php?action=logout&amp;redirect_to=<?php echo urlencode($_SERVER['REQUEST_URI']) ?>">登出</a></li></ul></div></div>
				<?php } ?>
             </ul>
        </div>	
</div>
 
<div id="header" class="png_bg">
	<div id="search_bg">
	              <div class="topnav"><!-- 菜单 -->
                                 <?php wp_nav_menu( array( 'container' => '','items_wrap' => '<ul id="nav" class="menu">%3$s</ul>','fallback_cb'     => '' ) ); ?>
								
                                 <?php include (TEMPLATEPATH . '/searchform.php'); ?>	
				  </div>
				  <?php if (get_option('swt_new_pp') == 'Display') { ?><!-- 菜单下面附页 -->
					 <?php include (TEMPLATEPATH .'/includes/header_fuye.php'); ?> 
			<?php } else { echo ''; } ?>  
	      <div class="clearfix"></div>
   </div>
</div>