<?php
$course_title = "";
include  "../../common/tools.php"; 

//find out the course alias
$course = basename(dirname($_SERVER['PHP_SELF']));

//if subpage is not given from url, then let it be main
if (!isset($_GET['page']))	$page = "main";
//else get it as an argument (passed from url by .htaccess
else	$page = strtolower(trim($_GET["page"]));


//load the content according to the page
$content = "";
ob_start();
// choose the page to display according to the argument given from the url
switch ($page) {
	case "lectures" :
		$material_type="lectures";
		include '../course_material.php';
		$content = ob_get_clean();
		break;
	case "homework" :
		$material_type="homework";
		include '../course_material.php';
		$content = ob_get_clean();
		break;
	case "exams" :
		$material_type="exams";
		include '../course_material.php';
		$content = ob_get_clean();
		break;
	case "local_uploader":
		$title = "upload $course material";
		//this is the folder where the files will be stored
		$folderpath = "files";
		//this piece of code will exit if you just need to stash a file 
		if (isset($_POST["filename"])) {
			include "$site_root/common/stash.php";
			exit();
		} else {
			include "$site_root/courses/lecture_notes_upload.php";
		}
		$content=ob_get_clean();
		break;
	default:
		$data = \load_static($course, $page);
		$title = $data["PageTitle"];
		$content = $data['Content'];
		if($title=="Not Found"){
			ob_start();
			include "$site_root/common/skeletons/404.php";
			$content=  ob_get_clean();
		}
		if($page=="main"){
			$main_span="span8";
			$title_icon="icon-home";
			ob_start();
			print_news($course);
			print_latest_material();
			$extra_content= ob_get_clean();
		}
		
}



include "$site_root/common/skeletons/common_frame.php";


function print_latest_material(){
	global $course, $con;
	$year=now();
	//construct the query for the speciffied course where date is not ZERO 
	// ZERO date indicates a sticky topic
	$query = "SELECT Topic,Type  FROM CourseMaterial WHERE Course='$course'
		AND Date<>'1970-01-01' AND Year='$year';";
	$result = \mysqli_query($con, $query);
	echo "<div class='hero-unit span3 blocklist pull-right rightpanel' style:'min-height:250px;'>
	<h2><span><i class='icon-file-text-alt'></i>Latest Material</span></h2>	
	<ul class='unstyled'>";
	while ($result != null && $row = mysqli_fetch_array($result)) {
		$topic=  urldecode($row["Topic"]);
		$type= $row['Type'];
		//choose the appropriate icon and link
		$icon='icon-file-text';
		$link=$type;
		switch ($type){
			case "homework":$icon='icon-keyboard';break;
			case "lectures":$icon='icon-calendar';break;
			case "exams":$icon='icon-pencil';break;
		}
		
		echo"<li><a href='$link'><i class='$icon'></i>$topic </a></li>"; 
	}
	echo "</div>\n</ul>";
}	

	
END:



?>
