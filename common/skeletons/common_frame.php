
 <?php
if (!function_exists("is_course")) {
 function is_course(){
	$path=getcwd();
	if(substr_count($path,"courses/"))
		return true;
	else return false;
 }
}
?>

<!DOCTYPE html>
<html>

<!-- CSLab common frame Head -->
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="icon" type="image/ico" href="<?php echo $server_root?>/common/img/favicon.ico"/>
	<title><?php echo $title?></title>
	<link href="<?php echo $server_root?>/common/css/bootstrap.css" 
	      rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo $server_root?>/common/css/bootstrap-responsive.css" 
	      rel="stylesheet" type="text/css" media="screen" />
	<link href="<?php echo $server_root?>/common/css/cslab_custom.css" 
	      rel="stylesheet" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo $server_root?>/common/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,500,700,400italic,500italic,700italic&subset=latin,greek-ext,greek,latin-ext' rel='stylesheet' type='text/css'>

	<link rel="shortcut icon" href="<?php echo $site_root?>/common/img/favicon.ico" >
	 <meta itemprop="image" content="<?php echo $site_root?>/common/img/favicon.ico">
	 <meta name="viewport" content="width=device-width, initial-scale=1.0"/>  

	 <script>
	function show_hide(father){
		father.getElementsByClassName("easter_egg")[0].style.display=
					father.getElementsByClassName("easter_egg")[0].style.display==="none" ?
						"block" : "none" ;
	}
	</script>
</head>


<!-- CSLab common frame body -->
<body class='container-fluid' style="padding:0">
<div class="row-fluid" style="min-height: 100%">

<!-- Navbar -->
	<?php
	include "$site_root/common/skeletons/navbar.php";
		
	?>

<!-- END Navbar -->
		

<!-- CSLab Common Frame Header  -->
<header class="jumbotron subhead" id="mainheader"> 
			<div class="container">
				<h1 style="color:white !important;"> <?
					if (isset($title_icon))
						echo "<i class='$title_icon'></i>";
					echo $title;
					?>
				</h1>
			</div>
		<? print_breadcrumb(); ?>
</header>

<?if (is_course())
		include "$site_root/common/skeletons/course_nav.php";
?>
<!-- END Common Frame Header  -->

<!-- PAGE CONTENTS  -->
	<?
	if(!isset($main_span))
		$main_span="span12";

	echo "<div id='main' class='$main_span row-fluid'>\n";
		echo $content;
	echo "</div> \n <!-- END Main -->";
	

	if(isset($extra_content)) echo $extra_content;
	?>

<!-- END PAGE  CONTENTS -->
	
<!-- CSLAB Footer -->
	<?
	include "$site_root/common/skeletons/footer.html"; 
	?>
<!-- END Footer -->

<!-- JS modules -->
<script src=<?echo "'$server_root/common/js/jquery.min.js'";?>></script> 	
<script src=<?echo "'$server_root/common/js/bootstrap.js'";?>></script> 


</div>
</body>
<!-- END CSLab Common Frame Body -->

</html>
