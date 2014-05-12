<?php 	

include "../common/tools.php";

if(!isset($_GET["page"]))
	$page="mainpage";
else $page=$_GET["page"];
$page=$page=="main"?"mainpage":$page;

switch ($page){
	case "staff":
		header('Location: ../staff/index.php');
		break;
	case "publications":
		header('Location: ../publications/index.php');
		break;
	case "diplom":
		header('Location: ../diplom/index.php');
		break;
}
	
		$data = load_static("main", $page);
		$title = $data["PageTitle"];
		$content = $data['Content'];
		if($title=="Not Found"){
			ob_start();
			include "$site_root/common/skeletons/404.php";
			$content=  ob_get_clean();
			
		}
		
		switch ($page){
		case "mainpage":
			$title_icon='icon-home';
                        $main_span="span9";
			echo<<<STY
<link rel='stylesheet' href='../common/css/nivo-slider.css' type='text/css' media='screen' />
STY;
		ob_start();
                        
			print_news('main');
			//echo "</div>"; //end side
			//$extra_content= ob_get_clean();
			$extra_content=ob_get_clean();
		break;
		case "research":
			$title_icon='icon-search';
			break;
		case "projects":
			$title_icon='icon-gears';
			break;
		case "contact":
			$title_icon='icon-phone';
			break;

		}

include "$site_root/common/skeletons/common_frame.php";



//sloppy add-on
if($page=="mainpage")echo<<<END
<script>
	document.getElementById('mainheader').style.backgroundImage=
		"url('$server_root/common/img/banner.jpg')";

	document.getElementById('mainheader').style.height="150px";
	
</script>
END;
 
?>
