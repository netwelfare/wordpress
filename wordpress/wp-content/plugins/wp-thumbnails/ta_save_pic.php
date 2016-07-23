<?php
/*
* 如果是远程图片，下载后制作缩略图。
*/

//文章内容下载图片
function ta_save_pic($url,$path,$filename)
{
	if ($url == "") return false;
	if ($path == "") return false;
	if ($filename == "") return false;
	
	//分析后缀,只下载gif，jpg，png图片
	$ext = strrchr($url,"."); //最后出现的.
	if($ext!=".gif" && $ext!=".jpg" && $ext!=".jpeg" && $ext!=".png" && $ext!=".GIF" && $ext!=".JPG" && $ext!=".PNG" && $ext!=".JPEG") 
		return false;
	$filename = $filename.$ext; 
	if(!function_exists('file_get_contents')) 
		return false;
		
	$content=@file_get_contents($url); //读取远程图片内容到字符串
	if ($content == false) return false;
	
	$filename=$path.$filename;
	
	//将指定的filename资源绑定到一个流fp上,读写方式打开.文件不存在则创建，存在则覆盖.
	$fp=fopen($filename,"w+");
	if (@fwrite($fp,$content)) //content的内容写入文件指针fp处
	{
		@fclose($fp);
		return $filename;
	}
	else 
	{
		@fclose($fp);
		return false;
	}
}

?>