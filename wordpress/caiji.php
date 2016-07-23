<?php
header( 'Content-Type: text/html; charset=utf-8' );
$url = "http://www.cnblogs.com/gaiyang/archive/2011/04/01/2002452.html";
include("./Snoopy.class.php");

$snoopy = new Snoopy;
$snoopy->fetchtext($url); 		//获取文本内容
echo $snoopy->results; 		//显示结果
?>