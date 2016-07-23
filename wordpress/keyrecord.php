<?php 
header( 'Content-Type: text/html; charset=utf-8' );
require( dirname(__FILE__) . '/wp-load.php' );
global $wpdb;
$sql = $wpdb->prepare("SELECT keywords,ip,create_time FROM wp_search_record order by create_time desc LIMIT %d,%d", 1, 200 );
$results = $wpdb->get_results( $sql );
echo "最近搜索记录:<br />";
	if ( !empty( $results ) ) 
	{
		foreach ( $results as $r ) 
		{
			echo $r->keywords.",".$r->ip.",".$r->create_time."<br />";	
		}
	}
?> 