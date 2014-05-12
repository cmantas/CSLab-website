<?php 
include '../common/tools.php';

ob_start();?>
	<script>
		function validateForm()
		{
			var x = document.forms["add_publication"]["titletext"].value;
			if (x == null || x == "")
			{
				alert("title must be filled out");
				return false;
			}
			x= document.forms["add_publication"]["authortext"].value;
			if (x == null || x == "")
			{
				alert("authors must be filled out");
				return false;
			}
			x= document.forms["add_publication"]["conventiontext"].value;
			if (x == null || x == "")
			{
				alert("the place it appeared in must be filled out");
				return false;
			}
			x= document.forms["add_publication"]["yeartext"].value;
			if (x == null || x == "")
			{
				alert("the year must be filled out");
				return false;
			}
		}

		function setFocus() {
				var input = document.getElementsByName("titletext")[0];
				input.focus();
			}
	</script>	

	

<?php

//if there is something submitted, execute the following code to save it, before displaying the form
if (isset($_POST['submit'])) {
	//keep the data from the form
	$title = $_POST['titletext'];
	$link = $_POST['authortext'];
	$convention = $_POST['conventiontext'];
	$year = $_POST['yeartext'];
	$filepath="";
	//if there is a file uploaded, store it
	if (file_exists($_FILES['uploadedfile']['tmp_name'])) {
		//keep the name of the file 
		$filename = addslashes($_FILES["uploadedfile"]["name"]);
		//where to store the file
		$file_store_dir = '../files_store/publications/';
		
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
	}

	$query = "INSERT INTO Publications(Title, Authors, Appeared, Year, Filepath) VALUES('$title', '$link', '$convention', $year, '$filepath')"; 
	mysqli_query($con,$query);

	
	if ($filepath!=="")	$filepath= "<br> file saved in: <a href='$filepath'> $filename</a>";
	echo <<<ALERT
	<div class="alert alert-block alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <h4>Publication submitted</h4>
  "you have submitted the following publication: 
		<br> $title <br> $link<br>$convention - $year</b> $filepath
<br>You can use the following form again to enter a new paper.
</div>
ALERT;
}
?>


	
<form enctype='multipart/form-data' method="post" action="<?php echo $_SERVER['PHP_SELF'];
	?>" name="add_publication" onsubmit="return validateForm();" class='form-horisontal' >
<label>title</label>	
<input name="titletext" type="text" size="30" >	
<label>author</label>	
<input name="authortext" type="text" size="30" >
<label>appeared in</label>
<input name="conventiontext" type="text" size="30" >
<label>year</label>
<input name="yeartext" type="text" size="30" onkeyup="showResult();">
<label>file (optional)</label>
<input type='file' name='uploadedfile'> <br> 
<input type="submit" id="submit" name="submit" value="Submit Form" class='btn btn-large btn-primary'><br>
</form>


<script> setFocus();</script>

<?php
$content=  ob_get_clean();
$title= "Add Publication";

include "$site_root/common/skeletons/common_frame.php";
?>