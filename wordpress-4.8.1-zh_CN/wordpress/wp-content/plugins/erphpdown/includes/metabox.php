<?php
/*
mobantu.com
qq 82708210
*/
if ( !defined('ABSPATH') ) {exit;}
add_action( 'admin_menu', 'ice_create_down_box' );
add_action( 'save_post', 'ice_save_down_data' );
function ice_create_down_box() {
    $erphp_post_types = get_option('erphp_post_types');
    $args = array(
       'public'   => true,
       //'_builtin' => false
    );
    $post_types = get_post_types($args);
    foreach ( $post_types  as $post_type ) {
    	if($erphp_post_types){
	    	if(in_array($post_type,$erphp_post_types)) add_meta_box( 'erphpdown-postmeta-box','Erphpdown商品属性', 'ice_post_down_info', $post_type, 'normal', 'high' );
	    }
    }
	
}
function ice_down_post_boxes() {
	$meta_boxes = array(
		array(
			"name"             => "start_down",
			"title"            => "收费类型",
			"desc"             => "（说明：查看部分内容使用短代码例子：[erphpdown]隐藏的内容[/erphpdown]）",
			"type"             => "erphpcheckbox",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "member_down",
			"title"            => "VIP模式",
			"desc"             => "（说明：VIP专享指只有VIP用户可下载或查看，普通用户无权购买下载或查看，此类型不需要设置价格）",
			"type"             => "select",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "down_price",
			"title"            => "收费价格",
			"desc"             => "（说明：单位以货币名称为标准，为空或者为0则免费下载，若是VIP免费下载需填一个大于0的数字）",
			"type"             => "text",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "down_url",
			"title"            => "下载地址",
			"desc"             => "（说明：一行一个，可以支持多个地址，可外链以及内链，内链格式如：/wp-content/moban-tu.zip）",
			"type"             => "textarea",
			"capability"       => "manage_options"
		),
		array(
			"name"             => "hidden_content",
			"title"            => "隐藏字段",
			"desc"             => "（说明：纯文本内容，例如：提取码。用户购买后自动把隐藏内容发邮件给用户，需要主机支持mail，选填）<br><br /><span style='float:right;font-size:12px'>技术支持：mobantu.com</span>",
			"type"             => "text",
			"capability"       => "manage_options"
		)
	);
	return apply_filters( 'ali_post_boxes', $meta_boxes );
}
function ice_post_down_info() {
	global $post;
	$meta_boxes = ice_down_post_boxes(); 
?>
	<table class="form-table">
	<?php foreach ( $meta_boxes as $meta ) :
		$value = get_post_meta( $post->ID, $meta['name'], true );
		if ( $meta['type'] == 'text' )
			ice_show_text_input( $meta, $value );
		elseif ( $meta['type'] == 'textarea' )
			ice_show_textarea( $meta, $value );
		elseif ( $meta['type'] == 'checkbox' )
			ice_show_checkbox( $meta, $value );
		elseif ( $meta['type'] == 'erphpcheckbox' )
			ice_show_erphpcheckbox( $meta, $value );
		elseif ($meta['type'] == 'select')
			ice_show_select( $meta, $value );
	endforeach; ?>
	</table>
<?php
}

function ice_show_select( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
        	<?php if(esc_html( $value, 1 ) > 0) {?>
			<input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='4') echo 'checked'?> value="4" />VIP专享 &nbsp;
			<input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='3') echo 'checked'?> value="3" />VIP免费 &nbsp;
			<input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='2') echo 'checked'?> value="2" />VIP5折&nbsp;
            <input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='5') echo 'checked'?> value="5" />VIP8折&nbsp;
            <input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='6') echo 'checked'?> value="6" />包年VIP免费&nbsp;
            <input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='7') echo 'checked'?> value="7" />终身VIP免费&nbsp;
			<input type="radio" name="<?php echo $name; ?>" <?php if(esc_html( $value, 1 )=='1') echo 'checked'?> value="1" />原价（表示VIP无折扣）&nbsp;
            <?php }else{?>
            <input type="radio" name="<?php echo $name; ?>" value="4" />VIP专享 &nbsp;
			<input type="radio" name="<?php echo $name; ?>" value="3" />VIP免费 &nbsp;
			<input type="radio" name="<?php echo $name; ?>" value="2" />VIP5折&nbsp;
            <input type="radio" name="<?php echo $name; ?>" value="5" />VIP8折&nbsp;
            <input type="radio" name="<?php echo $name; ?>" value="6" />包年VIP免费&nbsp;
            <input type="radio" name="<?php echo $name; ?>" value="7" />终身VIP免费&nbsp;
			<input type="radio" name="<?php echo $name; ?>" checked value="1" />原价（表示VIP无折扣）&nbsp;
            <?php }?>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}
function ice_show_text_input( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
		<input type="text" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo esc_html( $value, 1 ); ?>" size="30" tabindex="30" style="width: 97%;" />
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
			<br />
			<p class="description"><?php echo $desc; ?></p>
		</td>
	</tr>
	<?php
}
function ice_show_textarea( $args = array(), $value = false ) {
	extract( $args ); ?>
	<tr>
		<th style="width:10%;">
			<label for="<?php echo $name; ?>"><?php echo $title; ?></label>
		</th>
		<td>
			<textarea name="<?php echo $name; ?>" id="<?php echo $name; ?>" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo esc_html( $value, 1 ); ?></textarea>
			<input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    <br />
			<p class="description"><?php echo $desc; ?></p>		</td>
	</tr>
	<?php
}
function ice_show_checkbox( $args = array(), $value = false ) {
	extract( $args ); ?>
<tr>
		<th style="width:10%;">
	<label for="<?php echo $name; ?>"><?php echo $title; ?></label>		</th>
		<td>
    <input type="checkbox" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="yes"
    <?php if ( htmlentities( $value, 1 ) == 'yes' ) echo ' checked="checked"'; ?>
    style="width: auto;" />&nbsp;启用<?php echo $title; ?>
    <input type="hidden" name="<?php echo $name; ?>_input_name" id="<?php echo $name; ?>_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
	<p class="description"><?php echo $desc; ?></p>

    </td>
	</tr>
	<?php }
function ice_show_erphpcheckbox( $args = array(), $value = false ) {
	extract( $args ); ?>
<tr>
		<th style="width:10%;">
	<label for="<?php echo $name; ?>"><?php echo $title; ?></label>		</th>
		<td>
        <?php 
			global $post;
			$value1 = get_post_meta( $post->ID, 'start_down', true );
			$value2 = get_post_meta( $post->ID, 'start_see', true );
			$value3 = get_post_meta( $post->ID, 'start_see2', true );
		?>
        <input type="radio" name="start_down" checked value="4" />不启用&nbsp;
        <input type="radio" name="start_down" <?php if($value1 == 'yes') echo 'checked'?> value="1" />收费下载 &nbsp;
		<input type="radio" name="start_down" <?php if($value2 == 'yes') echo 'checked'?> value="2" />收费查看全部内容 &nbsp;
		<input type="radio" name="start_down" <?php if($value3 == 'yes') echo 'checked'?> value="3" />收费查看部分内容（利用短代码 [erphpdown]隐藏）&nbsp;
        
            
    <input type="hidden" name="erphpdown" value="1">
    <input type="hidden" name="start_down_input_name" id="start_down_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    <input type="hidden" name="start_see_input_name" id="start_see_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
    <input type="hidden" name="start_see2_input_name" id="start_see2_input_name" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />
	<p class="description"><?php echo $desc; ?></p>

    </td>
	</tr>
	<?php }
	function ice_save_down_data( $post_id ) {
		if($_POST['start_down'] == 1 || $_POST['start_down'] == 2 || $_POST['start_down'] == 3){
			if(is_numeric($_POST['down_price'])==false){
				if($_POST['member_down']==4){
				
				}else wp_die('价格必须是数字');
			}
			if(!$_POST['member_down'])wp_die('请选一个VIP模式');
		}
		
		if(!erphpmeta()) die();
		
		$meta_boxes = array_merge( ice_down_post_boxes() );
		foreach ( $meta_boxes as $meta_box ) :
			if($meta_box['type'] == 'erphpcheckbox'){
			
				if ( !wp_verify_nonce( $_POST['start_down_input_name'], plugin_basename( __FILE__ ) ) || !wp_verify_nonce( $_POST['start_see_input_name'], plugin_basename( __FILE__ ) ) || !wp_verify_nonce( $_POST['start_see2_input_name'], plugin_basename( __FILE__ ) ))
					return $post_id;
				if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
					return $post_id;
				elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
					return $post_id;
					
				$data = stripslashes( $_POST['start_down'] );
				$data1 = '';$data2='';$data3='';
				if($data == '1') $data1 = 'yes';
				if($data == '2') $data2 = 'yes';
				if($data == '3') $data3 = 'yes';
				update_post_meta( $post_id, 'start_down', $data1 );
				update_post_meta( $post_id, 'start_see', $data2 );
				update_post_meta( $post_id, 'start_see2', $data3 );
			
			}else{
				if ( !wp_verify_nonce( $_POST[$meta_box['name'] . '_input_name'], plugin_basename( __FILE__ ) ) )
					return $post_id;
				if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) )
					return $post_id;
				elseif ( 'post' == $_POST['post_type'] && !current_user_can( 'edit_post', $post_id ) )
					return $post_id;
				
				$data = stripslashes( $_POST[$meta_box['name']] );
				if ( get_post_meta( $post_id, $meta_box['name'] ) == '' )
					add_post_meta( $post_id, $meta_box['name'], $data, true );
				elseif ( $data != get_post_meta( $post_id, $meta_box['name'], true ) )
					update_post_meta( $post_id, $meta_box['name'], $data );
				elseif ( $data == '' )
					delete_post_meta( $post_id, $meta_box['name'], get_post_meta( $post_id, $meta_box['name'], true ) );
			}
		
		
		endforeach;
}