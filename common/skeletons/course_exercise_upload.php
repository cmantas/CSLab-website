<?php

$year=now();
$course =$_POST['course']; 
$exNo=$_POST['exerciseNo'];
$year=$_POST['year'];

$deadline=new DateTime(load_exercise_deadline());

$title = "Upload Exercise: $exNo";
$title_icon="icon-user";

$folderpath = "$site_root/courses/$course/files/homework_files/$year/exercise_$exNo";





if ( isset($_POST['submit'])){
	//read the form
	$form_error =read_form();
	//check if the deadline is missed
	if($deadline< new Datetime('NOW')){
		alert("<br>you have missed the deadline", "error");
		goto END;
	}
	//in case of error
	if ( empty($_FILES['file']) or $_FILES['file']['error']){
		$file_error=1;
		$form_error=1;
		handle_file_error($_FILES['file']['error']);
	} 
	//if there is no error try to save the file
	else {
		//set the name of the file to save
		$dashed_name=preg_replace("/[\s_]/", "-", $name);
		$fname= "$dashed_name"."_$am"."_".$_FILES['file']['name'];
		$file_error=0;
		if(!save_file($_FILES['file']['tmp_name'], $folderpath,$fname)){
			$form_error=1;
			$file_error=1;
		}
	}

	//finally print the submission message
	if ( !$form_error && !$file_error){
		$msg=<<<MSG
		Exercise succesfully submitted<br>
			<b>Name</b> name<br/>
			<b>StudentNo<b>$am<br/>
			<b>filename:</b>$fname<br/>
			<b>email:</b>$email<br/>
MSG;
		alert($msg,"success");
	}
	else{
		$msg=<<<MSG
		Exercise Submission Failure<br>
		$file_error_msg;	
MSG;
		alert($msg,"failure");

	}
	goto END;
}




		
//print the page
	?>
	<h2><span>Details</span></h2>
	<form action='<?echo $_SERVER['PHP_SELF'];?>?page=exercise_upload' method='post' enctype="multipart/form-data"> 
  	<fieldset>
	<dl class="dl-horizontal">
		<h3>Exercise</h3>
		<dt>Course:</dt>
		<dd><input type="text" name="course" 
			   value="<?echo $course;?>" readonly="readonly" /></dd>
		<dt>Exercise #:</dt>
		<dd><input type="text" name="exerciseNo" 
			   value="<?echo $exNo;?>" readonly="readonly" /></dd>
		<input type="hidden" name="year" 
			   value="<?echo $year;?>" /></dd>
		<dt>Deadline:</dt>
		<dd><?echo $deadline->format('d/m/y');?></dd>
		<h3>Student</h3>
  		<dt>Full Name:</dt>
		<dd><input type="text" name="name" placeholder="John Hacker" required></dd>
		<dt>Student #:</dt>
		<dd><input type="text" name="studentNo" placeholder="123456"required></dd>
		<dt>Email:</dt>
		<dd><input type="text" name="email" placeholder="user@ece.ntua.gr" required></dd>
		<dt>File:<dt>
		<dd><i class="icon-file"></i><input type="file" name="file" required ></dd>
	</dl>

		<button type="submit" name="submit" class="btn btn-info btn-large">
			<i class="icon-upload-alt"></i>Submit Exercise</button>
	</fieldset>
	</form>


<?

END:

?>
<script src=<?echo "'$server_root/common/js/file_input.js'";?>></script> 
<script>
$(document).ready(function(){$('input[type=file]').bootstrapFileInput();});
</script>

<?



//saves a file in a specified folder
function save_file($file, $file_store_dir, $filename){
	global $file_error_msg;
		//append a random string in case of filename collision
		if (file_exists($file_store_dir .'/'. $filename)) {
			$name_parts = explode(".", $filename);
			$filename = $name_parts[count($name_parts) - 2] . "_" . rand() .
				"." . $name_parts[count($name_parts) - 1];
		}

		$filepath=$file_store_dir.'/'.$filename;
		//create the target folder if does not exist and move the file there
		if (!file_exists($file_store_dir))
			mkdir($file_store_dir,0777,true);
		if(\move_uploaded_file($file, $filepath)){
			return TRUE;
		}
		else{
			return FALSE; 
			$file_error_msg = "Failed to save the file";
		}
}


//reads the post form

function read_form(){

	global $name, $email,$am;

	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
	$am = filter_input(INPUT_POST, 'studentNo', FILTER_SANITIZE_STRING);
	$form_error= 0;
	if ( empty($firstname) ){
		$firstname_error =1;
		$form_eror = 1;
	} else {
		$firstname = strip_tags($firstname);
		}
	if ( empty($lastname) ){
		$lastname_error =1;
		$form_error = 1;
	} else { 
		$lastname = strip_tags($lastname);
		}
	if ( empty($email) ) { 
		$email_error =1;
		$form_error = 1;
	} else {
		$email = strip_tags($email);
		}
	if ( empty($am) ) { 
		$am_error =1;
		$form_error = 1;
	} else {
		$am = strip_tags($am);
		}
}


function handle_file_error($error){
	global $file_error_msg;
	switch($error) {
			case 1:
			case 2:
			$file_error_msg = "file too big";
				break;

			case 3:
				$file_error_msg = "file transfer unsuccessfull";
				break;
			case 4:
				$file_error_msg = "no file defined";
				break;
			case 5:
			case 6:
			case 7:
			case 8:
				$file_error_msg = "Failed to save the file";
				break;
		}
}

function load_exercise_deadline(){
	global $con, $course, $year, $exNo;
	$query = "SELECT Date from CourseMaterial WHERE Course='$course' 
		AND Year='$year' AND Type='homework' AND Number='$exNo'";
	$result = \mysqli_query($con, $query);
	while ($result != null && $row = mysqli_fetch_array($result)) {
		return $row['Date'];
	}
}