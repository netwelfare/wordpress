			<div class="cat_list">
			<div class="page_navi"><?php par_pagenavi(9); ?></div>
			<ul>
			<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>
			<li>
			<h2><span class="date fr"><?php post_views(' ', '<small> 次 </small>'); ?> | <?php the_time('Y-m-d') ?></span> <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" target="_blank"><?php echo cut_str($post->post_title,80); ?></a> 
			</h2>
			</li>
			<?php endwhile; ?>
			</ul> 
			<?php else : ?>
			<h3>什么也找不到！</h3>
			<p>抱歉，你要找的文章不在这里，请联系管理员补充相关内容，谢谢！<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=805246820&amp;site=qq&amp;menu=yes"><img border="0" src="http://pub.idqqimg.com/qconn/site/images/intro/wpa_style_51.gif" title="在线交谈"></a> </p>
			
			<?php endif; ?>
			<div class="page_navi"><?php par_pagenavi(20); ?></div>
			</div>