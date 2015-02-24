<?php
	$badword = array('肏','肏干');
	$buf = get_included_files();
	if (count($buf) == 1) {
	header('Content-Type:text/html;charset=utf-8;');
	echo '<div style="width:960px;margin:0 auto;border:1px #CCC solid;background-color:#F6F6F6;padding:10px;line-height:18px;word-break:break-all;word-wrap:break-word ">';
	highlight_file(__FILE__);
	echo '</div>';
}
?>