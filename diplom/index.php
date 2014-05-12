<?php 	
$title="Diploma Thesis";
$title_icon='icon-pencil';
include "../common/tools.php";

$courses=0;
if(isset($_GET['courses'])){
	$courses=$_GET['courses'];
}

 
 //start generating content in buffer
 ob_start();

	//first we will print the sticky topics
	show_sticky();

	$all_courses=  all_courses();
?>

<form action="<?echo $_SERVER['PHP_SELF'];?>" method="get">
	<h2><span>Choose Relevant Courses:</span></h2>
<select name="courses[]" multiple="multiple">
	<?php foreach($all_courses as $c) echo "<option>$c</option>";?>
</select>
	<button type="submit" class="btn btn-primary">GO <i class="icon-arrow-right"></i></button>
	<button class="btn btn-primary">Show All <i class="icon-arrow-right"></i><a href=""></a></button>
	
</form>


<?php
//	now print the non-sticky topics	
	show_non_sticky($courses);

		$content=  ob_get_clean();

		include "$site_root/common/skeletons/common_frame.php";	

function all_courses() {
	global $con;
	$query = "SELECT DISTINCT Title FROM Courses;";
	$result = mysqli_query($con, $query);
	$courses = array();
	while ($result != null && $row = mysqli_fetch_array($result)) {
		array_push($courses, $row['Title']);
	}
	return $courses;
}


function print_sticky($title, $id, $content){
echo "<div onclick='show_hide(this);' style='cursor:pointer;'class='hero-unit center'> 
						<div name='id' style='display:none'>$id </div>
						<h3>  $title</h3> <i class='icon-chevron-down '></i>
				<div class='easter_egg center' style='display:none'>
					$content
				</div>
			      </div>";	
}

function show_sticky() {
	global $con;
	echo "<h2><span>General</span></h2>";
	$query = "SELECT * FROM Diplom WHERE Sticky=true";
	$result = mysqli_query($con, $query);
	$group_category = "";
	echo "<div>";
	while ($result != null && $row = mysqli_fetch_array($result)) {
		$sticky_title = urldecode($row['Title']);
		$content = urldecode($row["Content"]);
		$id =$row['id'];
		print_sticky($sticky_title, $id, $content);
	}
	echo"</div>";
}

function show_non_sticky($courses=NULL) {
	global $con;
	$condition="TRUE";
	if($courses){
		$condition="";
		$f=true;
		foreach($courses as $c){
			$c=  urlencode($c);
			$condition.=$f?"":" OR ";
			$f=false;
			$condition.="Course LIKE '%$c%'";
		}
	}
	
	$query = "SELECT * FROM Diplom WHERE Sticky=false AND ($condition)
		ORDER BY Year DESC, Course ";
	$group_year = "";
	$group_course = "";
	$result = mysqli_query($con, $query);
	while ($result != null && $row = mysqli_fetch_array($result)) {
		$year = urldecode($row['Year']);
		$course = urldecode($row['Course']);
		$link = urldecode($row["Link"]);
		$filepath = $row["Filepath"];
		$abstract =urldecode( $row["Content"]);
		$title=  urldecode($row["Title"]);
		$id =$row['id'];

		//in case of different year
		if ($year != $group_year) {
			$group_year = $year;
			echo $group_course != "" ? "</ul>" : "";
			$group_course = "";
			echo "<h2><span> $group_year <span></h2>";
		}

		if ($course != $group_course) {
			echo $group_course != "" ? "</ul>" : "";

			$group_course = $course;
			echo "<h3> $group_course </h3>";
			echo "<ul class='unstyled'>";
		}

		echo "<li><i class='icon-asterisk'></i>";

		$end_hidden = "";
		if ($abstract != "") {
			echo "<div onclick='show_hide(this);' style='cursor:pointer; display:inline;'> 
					<i class='icon-chevron-down'></i>";
			$end_hidden = "<div class='easter_egg well' style='display: none;'>
					$abstract \n</div>\n</div>\n";
		}

		echo "<div name='id' style='display:none'>$id </div> $title";
		if (($anchor = $filepath != "" ? $filepath : $link ) != "") {
			echo "<a href=\"$anchor\"><i class='icon-file'></i></a>";
		}
		echo $end_hidden;
		echo"</li>";
	}
	echo"</ul>";
}

		
		?>
