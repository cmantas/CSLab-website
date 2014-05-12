<?php

	echo getcwd()."<br>";
	$fullpath_dirs = explode('/', getcwd());
	
	$dirs = array_slice($fullpath_dirs, 0,count($fullpath_dirs)-1);
	echo implode("/",$dirs);
	$site_root = substr(str_repeat("../",  count($dirs)-1 ),0,-1);
	if($site_root=="") $site_root='.';
?>
