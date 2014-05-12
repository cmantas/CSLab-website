<? 

ob_start();?>

<script>
		function validateForm()
		{
			var x = document.forms["add_news"]["titletext"].value;
			if (x == null || x == "")
			{
				alert("title must be filled out");
				return false;
			}
						
		}
		function setFocus() {
				var input = document.getElementsByName("titletext")[0];
				input.focus();
			}
	</script>	

	

<?php



if (isset($_POST['submit'])) {
	$context=$_POST['contexttext'];
	$title = $_POST['titletext'];
	$link = $_POST['linktext'];
	$expiry_len = $_POST['expiry'];
	$now_date = date("Y-m-d H:i:s", time());
	$expiry = \date("Y-m-d H:i:s", strtotime("$now_date + $expiry_len months"));
	$inserted_fid="NULL";
	$con = \connect_DB();
	

//if there is a file uploaded, store it
	if (file_exists($_FILES['uploadedfile']['tmp_name']) || 
		is_uploaded_file($_FILES['uploadedfile']['tmp_name'])) {

		//keep the name of the file 
		$filename = get_magic_quotes_gpc() ? $_FILES["uploadedfile"]["name"] :
			\addslashes($_FILES["uploadedfile"]["name"]);
		$tmp_name = $_FILES['uploadedfile']['tmp_name'];
		$type = $_FILES['uploadedfile']['type'];
		$size = $_FILES['uploadedfile']['size'];
		$handle = \fopen($tmp_name, "r");
		$file_contents = addslashes(fread($handle, filesize($tmp_name)));
		\fclose($handle);
		$add_file_query = "INSERT INTO files (name, size, type, content ) " .
			"VALUES ('$filename', '$size', '$type', '$file_contents')";
		\mysqli_query($con, $add_file_query) or die(mysql_error());
		$inserted_fid = mysqli_insert_id($con);
		echo "<br> interted file id: $inserted_fid";
	}
	
	$query = "INSERT INTO News(Context, Title, Link, Added_Datetime,  Expiry_Datetime, File_ID)
		VALUES('$context', '$title', '$link', NOW(), '$expiry', $inserted_fid)";

	//echo $query;
	\mysqli_query($con, $query) or die(mysql_error());
	echo <<<ALERT
	<div class="alert alert-block alert-success">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <h4>News submitted</h4>
  you have submitted the following news: <br> $title <br> $link
<br>You can use the following form again to enter a new entry.
</div>
ALERT;
}

?>

	
	
<form method="post" enctype='multipart/form-data' action="<?php echo $_SERVER['PHP_SELF']."?page=add_news";?>" 
      name="add_news" onsubmit="return validateForm();" class='form' >
Context: <input name="contexttext" type="text" size="20" readonly value="<?echo $context;?>">	
	<label>title</label>	
<input name="titletext" type="text" size="30" >	
<label>link </label>
<input name="linktext" type="text" size="30" placeholder='optional link' >
<label>expiry in months:</label>
<input name="expiry" type="number" min="1" max="48" step="1" value="16" required>
<label>File (optional)</label>
<input type='file' name='uploadedfile'><br> 
<input type="submit" id="submit" name="submit" value="Submit Form" class='btn btn-large btn-primary'><br>
</form>

<script> setFocus();</script>

<?

END:

$content=  ob_get_clean();
$title= "Add News";

include "$site_root/common/skeletons/common_frame.php";
?>