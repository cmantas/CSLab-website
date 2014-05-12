<!--[if !IE]><!-->
	<style>
	
	/* 
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	
		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		tr { border: 1px solid #ccc; }
		
		td { 
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee; 
			position: relative;
			padding-left: 50% !important;
			min-height: 1.1em;
		}
		
		td:before { 
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%; 
			margin-right: 10px; 
			white-space: nowrap;
			border-right: 3px solid #8C0707;
		}
		
		/*
		Label the data
		*/
		td:nth-of-type(1):before { content: "Number:"; }
		td:nth-of-type(2):before { content: "Date:"; }
		td:nth-of-type(3):before { content: "Topic:"; }
		td:nth-of-type(4):before { content: "Files:"; }
		td:nth-of-type(5):before { content: "Reading:"; }
		td:nth-of-type(6):before { content: "Announcements:"; }
	}
	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body { 
			padding: 0; 
			margin: 0; 
			width: 320px; }
		}
	
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body { 
			width: 495px; 
		}
	}
	
	</style>
	<!--<![endif]-->

<?php 	// this file includes the functions for printing the sticky and the non-
 	// sticky lecture notes


if(isset($_GET['year'])){
	$title=" ($_GET[year])";
	$year=  urlencode ($_GET['year']);
}
else $year=now();

print_title();
print_sticky();
print_nonsticky_table();
print_year_selection();

//prints the regular course notes (the non-sticky topics)
function print_nonsticky_table() {
	global $course, $con, $year, $material_type ;
	//print the table tags and the header row
//	$announce=$material_type=='homework'?'Submission':'Announcements';
        $announce=$material_type=='lectures'?'Announcements':'Notes';
        
        $date = $material_type=='homework'?'Deadline':'Date';

	
	echo <<<TSTART
<table class='table table-striped table-bordered'>
<tr><thead> 
	<th>#</th><th>$date</th> <th>Topic</th> <th>Files</th> 
	<th>Suggested Reading</th> <th>$announce</th> </tr></thead>
TSTART;

	//construct the query for the speciffied course where date is not ZERO 
	// ZERO date indicates a sticky topic
	$query = "SELECT * from CourseMaterial WHERE Course='$course' AND Date<>'1970-01-01' AND Year='$year' AND Type='$material_type' ORDER BY Number;";
	$result = \mysqli_query($con, $query);
	while ($result != null && $row = mysqli_fetch_array($result)) {
		$date = new DateTime(urldecode($row["Date"]));
		$dt = "" . $date->format('D d M y');
		$topic=  urldecode($row["Topic"]);
		$filetext=  urldecode($row["FileText"]);
		$reading=  urldecode($row["Reading"]);
		$no=$row['Number'];
		$announcements=  urldecode($row["Announcements"]);
//		if($material_type=='homework' && $date>=new DateTime('NOW'))
//			$announcements=<<<SUB
//		<form action="index.php?page=exercise_upload" style="margin:0;" method='POST'>
//			<input type="hidden" name="course" value="$course" />
//			<input type="hidden" name="exerciseNo" value="$no" />
//			<input type="hidden" name="year" value="$year" />
//			<button type="submit" name='start_submit' class='btn btn-primary'>Submit</button>
//		</form>
//SUB;
		
		$desktop_reading=$reading==""?"class='desktop'":"";
		$desktop_announce=$announcements==""?"class='desktop'":"";
		
		echo <<<ROW
	<tr> 
		<td>$no</td><td>$dt</td> <td>$topic</td> <td>$filetext</td>
		<td $desktop_reading>$reading</td> <td $desktop_announce>$announcements</td>
	 </tr>
ROW;
	}
	echo "</table>";
}//print_nonsticky_table


function print_sticky($year=""){
	global $course, $con, $year, $material_type, $title;
	//construct the query for the speciffied course where date is ZERO 
	// ZERO date indicates a sticky topic
	$query = "SELECT * from CourseMaterial WHERE Course='$course' AND Date='1970-01-01' AND Year='$year' AND Type='$material_type'";
	$result = \mysqli_query($con, $query);
	while ($result != null && $row = mysqli_fetch_array($result)) {
		//only the announcements field is valid in a sticky topic
		$text=  urldecode($row["Announcements"]);
		$title=$row["Topic"];
		echo "<div class='hero-unit center'>";
		if ($title) echo "<h3>$title</h3>";
		echo $text;
		echo "</div>";
	}
}


	 


function print_year_selection() {
	global $course, $con, $material_type, $page;
	$query = "SELECT DISTINCT Year from CourseMaterial WHERE Course='$course' GROUP BY Year";
	$result = \mysqli_query($con, $query);
	$years = array();
	while ($result != null && $row = mysqli_fetch_array($result)) {
		array_push($years, $row['Year']);
	}
	$self = $_SERVER['PHP_SELF'];

	echo <<<SEL
	<br><br><br>
	<form action="$self" method="get">
        <div class="span8 hero-unit">
	<h2><span> <i class='icon-archive'></i> <span>Previous Years' $material_type Material </h2><br>
	<input name="page" hidden value="$page">
	Select Year: \t
	<select name="year">
		<optgroup>
SEL;
	foreach ($years as $y)
		echo "<option> $y</option>";
	echo <<<SEL
		 </optgroup>
	</select>
	<button type="submit" class="btn btn-primary">Go<i class='icon-arrow-right'></i></button>
	 
</form>
</div>
SEL;
}

function print_title(){
	global $title, $material_type, $title_icon;

	switch ($material_type){
		case "lectures":
			$title_icon='icon-calendar';
			$title="Lecture Material $title";
			break;
		case "homework":
			$title_icon='icon-keyboard';
			$title="Course Exercises Material $title";
			break;
		case "exams":
			$title_icon='icon-pencil';
			$title="Exams Material $title";
			break;
	}
	
}


	?>







