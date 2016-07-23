<?php 
header( 'Content-Type: text/html; charset=utf-8' );
require( dirname(__FILE__) . '/wp-load.php' );
$categories = get_categories('hide_empty=0&orderby=name');
foreach ($categories as $category_list ) {
        echo $category_list->cat_ID.' '.$category_list->cat_name.'</br>';
}
get_header();
print_r(wp_print_scripts());
echo get_search_form();
echo wp_login_form();
echo bloginfo();
echo the_permalink();
echo wp_check_php_mysql_versions();
echo get_post_thumbnail_id(1497);
echo "<br />";
echo get_the_post_thumbnail(1497);
echo "<br />";
echo get_the_title(1497);
echo "<br />";
echo get_404_template();
echo "<br />";
echo get_author_template();
echo "<br />";
echo wp_get_theme();
echo "<br />";
echo get_stylesheet_directory_uri();
echo "<br />";
//wp_logout();
$datetime1 = "now";   
$datetime3 = "2013-04-10";  
$interval = count_days($datetime1, $datetime3);  
echo "在网易待了".$interval."天."."<br />";
$datetime4 = "2016-04-09";  
$interval = count_days($datetime1, $datetime4);  
echo "距离合同结束还有".$interval."天"."<br />";
function count_days($a,$b){
$startdate=strtotime($a);   
$enddate=strtotime($b);    
$days=round(abs($enddate-$startdate)/3600/24) ;
return $days;
}
//@wp_mail("805246820@qq.com", "test", "hello world", "Content-Type: text/html; charset=UTF-8");


//echo date("Y-m-d H:i:s");
global $wpdb;
$user_ip = $_SERVER['REMOTE_ADDR'];  
/*$wpdb->insert( 'swift_search_record', array(
		'keywords' => 'update',
		'create_time' =>date("Y-m-d H:i:s") 
	) );*/
	$sql = $wpdb->prepare("SELECT keywords,ip,create_time FROM swift_search_record order by create_time desc LIMIT %d,%d", 1, 5 );
	
	$results = $wpdb->get_results( $sql );
	
	if ( !empty( $results ) ) 
	{
		foreach ( $results as $r ) 
		{
			echo $r->keywords."<br />";
			echo $r->create_time."<br />";		
		}
	}
	$i=0;  
	while ($i< count($results))
	{  
	echo $results[$i]->keywords."<br />";  
	$i++;  
	}  
?> 