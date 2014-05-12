<?php
include '../common/tools.php';

ob_start();?>

	<script>
		function setFocus() {
				var input = document.getElementsByName("category_title")[0];
				input.focus();
			}
	</script>	

	

<?php



if (isset($_POST['submit'])) {
        $title=$year=$course="";
	$title = urlencode($_POST['title']);
	$content=urlencode($_POST['content']);
		
	$sticky=$_POST['sticky'];
	
	if ($sticky=="false") {
                $courses = array();
                
                if (isset($_POST['coursetext'])){
                    $courses = $_POST['coursetext'];
                }
		sort($courses);
		$course = urlencode(implode(", ", $courses));
		$year = urlencode($_POST['year']);
	} 

	$filepath="";

//if there is a file uploaded, store it
	if (file_exists($_FILES['uploadedfile']['tmp_name'])) {
		//keep the name of the file 
		$filename = addslashes($_FILES["uploadedfile"]["name"]);
		//where to store the file
		$file_store_dir = "../diplom/files/$title/";
		
		//append a random string in case of filename collision
		if (file_exists($file_store_dir . $filename)) {
			$name_parts = explode(".", $filename);
			$filename = $name_parts[count($name_parts) - 2] . "_" . rand() .
				"." . $name_parts[count($name_parts) - 1];
		}
		$filepath=$file_store_dir.$filename;
		//create the target folder if does not exist and move the file there
		if (!file_exists($file_store_dir))
			mkdir($file_store_dir);
		\move_uploaded_file($_FILES["uploadedfile"]["tmp_name"], $filepath);
		chmod($filepath, 0777);
	}
	
	$query = "INSERT INTO Diplom(Title, Sticky, Course, Content, Year, Filepath)
		VALUES('$title', $sticky, '$course', '$content', '$year',  '$filepath')";

//	echo $query;
	\mysqli_query($con, $query) or die(mysql_error());
	echo <<<ALERT
	<div class="alert alert-block alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <h4>Diplom submitted</h4>
  you have submitted the following diplom: <br> $title
<br>You can use the following form again to enter a new entry.
</div>
ALERT;
}

if (isset($_POST['delete'])) {
	$id=$_POST['delete_id']	;
	$query = "DELETE From Diplom WHERE id='$id'";
	if(!\mysqli_query($con, $query) ){
		alert(mysqli_error($con), "error");
	}
	else	alert("Deleted Entry $id");
	
}


$courses=  all_courses();

?>
		<script>
		function be_sticky(){
                        document.getElementById("regular_only").style.display="none";
                        document.getElementById("abstract_label").innerHTML="Content of the Topic"

                       
		}

		function be_regular(){

                        document.getElementById("regular_only").style.display="block;"
			document.getElementById("abstract_label").style.display="Thesis Abstract";

		}
		</script>



	
	
<form method="post" enctype='multipart/form-data' action="<?php echo $_SERVER['PHP_SELF'];
	?>" name="add_diplom" onsubmit="return validateForm();" >
	<label>Type of entry: </label>
<input type="radio" name="sticky" value="false" onclick="be_regular();" checked>Regular Entry (thesis topic) 
<input type="radio" name="sticky" value="true" onclick="be_sticky();">Sticky (Informative entry)<br><br>

<label>Title</label>
<input name="title" required>

<div id="regular_only">
	<label>Relevant Courses</label>
	<select multiple="multiple" name="coursetext[]" id="course_select">;;
		<?php foreach ($courses as $c)
			echo "<option>$c</option>\n"; ?> 
	</select>
	<label > Academic Year (eg: "2020-2021") : </label>
	<input name="year" type="text" size="20" >	
	<label>file</label>
	<input type='file' name='uploadedfile' class="btn" >
</div>

<label id="abstract_label"> Thesis Abstract</label>
<textarea name="content"  > </textarea><br>

<input type="submit" id="submit" name="submit" value="Submit Form" class='btn btn-large btn-primary'>
</form>

<form method="post" enctype='multipart/form-data' action="<?php echo $_SERVER['PHP_SELF'];?>"
       name="delete_deplom" onsubmit="return validateForm();" >
	<h3>Delete Entry</h3>
	<input name="delete_id" required>
<input type="submit" name="delete" value="delete" class='btn btn-danger'>
</form>

		
<script> setFocus();</script>

<?
$content=  ob_get_clean();
$title= "Add Diploma Thesis";

include "$site_root/common/skeletons/common_frame.php";


function all_courses(){
	global $con;
	$query = "SELECT DISTINCT Title FROM Courses;";
	$result = mysqli_query($con, $query);
	$courses = array();
	while ($result != null && $row = mysqli_fetch_array($result)) {
		array_push($courses, $row['Title']);
	}
	return $courses;
}

END:
?>