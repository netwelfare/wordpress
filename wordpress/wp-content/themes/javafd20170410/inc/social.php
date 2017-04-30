<div id="social">
    <div class="social-main">
        <span class="like">
            <a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" title="好文！一定要点赞！" class="favorite<?php if(isset($_COOKIE['ality_like_'.$post->ID])) echo ' done';?>"><i class="fa fa-thumbs-up"></i>赞<i class="count">
                <?php if( get_post_meta($post->ID,'ality_like',true) ){
                    echo get_post_meta($post->ID,'ality_like',true);
                } else {
                    echo '0';
                }?></i>
            </a>
        </span>
        <span class="shang-p"><a href="#shang" id="shang-main-p" title="好文！一定要打赏！">赏</a></span>
        <span class="share-s"><a href="#share" id="share-main-s" title="好文！一定要分享！"><i class="fa fa-share-alt"></i>分享</a></span>
        <div class="clear"></div>
    </div>
</div>

