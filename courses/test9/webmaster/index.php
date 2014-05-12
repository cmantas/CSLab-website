<?
$title="Course Webmaster Page";
$title_icon="icon-user-md";
include  "../../../common/tools.php";
$year=now();


function get_static_pages() {
	global $con,$course;	
	$query = "SELECT Title, PageTitle FROM Static_Content WHERE
		Context='$course' AND Title NOT IN('main', 'info', 'homework',
			'exams', 'links', 'grades');";

	$result = mysqli_query($con, $query);
	$pages = array();

	while ($result != null && $row = mysqli_fetch_array($result)) {
		$pages[$row['Title']]=$row['PageTitle'];
	}

	return $pages;
}

function change_year($year){
	global $con, $course;
	$year=  urlencode($year);
	$query="UPDATE Courses SET Year='$year' WHERE Alias='$course';";
	\mysqli_query($con, $query);
	if( mysqli_affected_rows($con)==0){
		alert("no rows were affeced, something went wrong", 'error');
	}else
		alert("the year was changed", "success");
	
}

ob_start();

//find out the course alias
$fullpath_dirs = explode('/', getcwd());
$home_index = array_search("webmaster", $fullpath_dirs);
$course=$fullpath_dirs[$home_index-1];


if(isset($_GET["add_content"])){
	$_GET['context']=$course;
	include "$site_root/common/static_editor.php";
	include "$site_root/common/upload.html";
}

$page="main";
if(isset($_GET['page'])) $page=$_GET['page'];

switch ($page) {
	case "local_uploader":
		$title = "upload $course $_GET[type]";
		//this is the folder where the files will be stored
		$folderpath = "$site_root/courses/$course/files";
		//this piece of code will exit if you just need to stash a file 
		if (isset($_POST["filename"])) {
			include "$site_root/common/stash.php";
			exit();
		} else {
			include "$site_root/courses/course_material_upload.php";
		}
		break;
	case "add_content":
		 header( "Location: $server_root/common/static_editor.php?context=$course&upload=yes");
		break;
	case "add_news":
			$context=$course;
			include "$site_root/common/add_news.php";
			goto END;
	case "add_admin":
		$name=$_POST['admin_name'];
		$pass=$_POST['admin_pass'];
		//create the .htpasswd file in the webmaster dir
		$out=exec("htpasswd -b .htpasswd $name $pass");
		if(!$out=="")alert($out);
	case "change_year":
		change_year($_POST['year']);
		break;
	case "download_exercises":
		unlink("homework_$no.zip");
		$no=$_GET["exerciseNo"];
		zip_and_download("../files/homework_files/$year/exercise_$no", "homework_$no.zip");
		echo "kalhmera";
		break;
	case "main":
		$pages=  get_static_pages();
		$now=now();
		$custom_page_options="";
		foreach ($pages as $key => $value) 
			$custom_page_options.= "<option  style='cursor: pointer;' value='$key'>$value</option>";
		
		echo <<<MENU
<ul class='unstyled inline'>
<li><h3 ></i><a href="add_news" class='well'> <i class='icon-list-alt'> </i>Add Course News</a></h3></li>
<li><h3> <a href="add_content" class='well'><i class='icon-globe'></i> Add Static content</a></h3></li>
<li><h3> <a href="index.php?page=local_uploader&type=lectures" class='well'>
	<i class='icon-calendar'></i> Add Lecture notes</a></h3></li>
<li><h3> <a href="index.php?page=local_uploader&type=homework" class='well'>
	<i class='icon-keyboard'></i> Add Exercises</a></h3></li>
<li><h3> <a href="index.php?page=local_uploader&type=exams" class='well'>
	<i class='icon-pencil'></i> Add Exams Material</a></h3></li>
<li><h3> <a href="index.php?page=local_uploader&type=exercise" class='well'>
	<i class='icon-pencil'></i> Add Exercise For Submission</a></h3></li>
</ul>

<form action="$server_root/common/static_editor.php" method="get" class='well'
	style='display:inline-block;'>
<h3><i class='icon-edit'></i>Edit static content for $course</h3>
	<select name="title" type="text" style="cursor: pointer;"required>
		<option  style="cursor: pointer;" value="main">Course Main Page</option>
		<option  style="cursor: pointer;" value="info">Course Info Page</option>
		<option  style="cursor: pointer;" value="homework">Course Homework</option>
		<option  style="cursor: pointer;" value="exams">Course Exam Material</option>
		<option  style="cursor: pointer;" value="links">Course Links</option>
		<option  style="cursor: pointer;" value="grades">Grades Page</option>
		$custom_page_options
	</select>
	<input name="context" type="text" value="$course" style="display:none;">
	<input name="upload" type="text" value="yes" style="display:none;">
	<button type="submit" id="submit" class='btn btn-large btn-info'> 
		<i class='icon-edit'></i> Edit </button>
</form>
<form action="index.php" class='well'>
			Download Submitted Exercise, No:
			<input type="hidden" name="page" value="download_exercises"/>
			<input type="number" name="exerciseNo" required>
			<button type="submit" class="btn">
				<i class='icon-download-alt'></i>Download</button>
</form>
<form action="change_year" method="POST" enctype="multipart/form-data" class='well' style='display:inline-block;'>
		<h3><i class='icon-calendar-empty'></i>Change the Running Year</h3>
		<input type="text" name="year" value="$now" />
		<input type="submit" value="change" />
</form>
		
<form action="add_admin" method="POST" enctype="multipart/form-data" style='display:inline-block;' class='well'>
	<h3><i class='icon-user-md'></i>Add Course Admin</h3>
	
	Name: <input type="text" name="admin_name" placeholder="username" /><br>
	Password: <input type="text" name="admin_pass" placeholder="password" /><br>
	<button type="submit" value="Add" class="btn btn-info">	<i class='icon-plus'></i>Add</button>
</form>
MENU;
		break;
}


$content = ob_get_clean();
include "$site_root/common/skeletons/common_frame.php";
END:
?>

