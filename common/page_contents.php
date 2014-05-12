<?php

if (!function_exists("store_static")) {
	function store_static($context, $title, $page_title, $content) {
		global $con;	
		$content=urlencode($content);
		$query="DELETE FROM `Static_Content` WHERE `Title`='$title' AND `Context`='$context'";;
		mysqli_query($con, $query);
		$query="INSERT INTO Static_Content Values('$context','$title', '$page_title', '$content')";
		if(!mysqli_query($con, $query))
			alert("could not store staic content", "error");
	}

}

if (!function_exists("load_static")) {
	function load_static($context, $title) {
		global $con;	
		$query="SELECT Content,PageTitle FROM Static_Content WHERE Context='$context' AND Title='$title'";
		$result=mysqli_query($con, $query);
		if ($result != null && $row = mysqli_fetch_array($result)) {
			$row['Content']=urldecode($row['Content']);
			return $row;	
		}
		else return array("Content"=>"<h3>nothing found  for $context :: $title</h3>", "PageTitle"=>"Not Found");
	}

}





if (!function_exists("print_news")) {
function print_news($context){
	global $con, $news_limit, $server_root;
	echo "<div class='hero-unit row-fluid blocklist span3 pull-right rightpanel' id='news'> \n
		<h2><span><i class='icon-list-alt'></i>News</span></h2>";
	echo"<ul class='unstyled'>";
		$query = "SELECT * FROM News WHERE Context='$context' ORDER BY Added_Datetime DESC
					LIMIT $news_limit";
		$result = mysqli_query($con, $query);
		while ($result != null && $row = mysqli_fetch_array($result)) {
			$ntitle = $row['Title'];
			$link = $row['Link'];
			$fid = $row["File_ID"];

			if ($link == "" && $fid != "") {
				$link = "$server_root/common/file_from_DB.php?fid=$fid";
			}
			echo "<li><a href=\"$link\"><i class='icon-asterisk span1' style='padding-top: 3%;'></i>$ntitle</a></li>";
		}
		
		echo "</ul>\n</div>";
}
}

function print_breadcrumb(){
		echo "<ul class='breadcrumb pull-right hidden-phone' id='breadcrumb'>";
		$cwd = getcwd();
		$fullpath_dirs = explode('/', $cwd);
		$home_index = array_search("cslab", $fullpath_dirs);
		$dirs = array_slice($fullpath_dirs, $home_index);
		for ($i = 0; $i < count($dirs); $i++) {
			$path = str_repeat("../", count($dirs) - 1 - $i);
			echo "<li><a href=\"$path\">$dirs[$i]</a></li>";
		}
		echo "</ul>";
}



?>

