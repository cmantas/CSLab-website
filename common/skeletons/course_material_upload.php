<?php
//this file is only meant to be icluded from other files residing one level 
//below in the directory hierarchy (in a course folder withing the "courses"
//folder


//in case this is a submission, execute this code
if(isset($_POST["submit"])){
	$is_sticky=isset($_POST["sticky_check"])&& $_POST["sticky_check"]=="true"?
		true:false;

	$text=$is_sticky?$_POST["stickytext"]:$_POST["announcements"];
	$topic=$is_sticky?$_POST["stickytitle"]:$_POST["topic"];
	
	\store_material($_POST['course'], $_POST['type'], $_POST["lecNo"],  $_POST["date"], $topic,
		$_POST["filetext"], $_POST["reading"], $text);
}


?>

<script>

	
	function refresh(){
		document.getElementById("filetext").value+=uploaded;
		
	}

	function toggle_sticky(sticky){
		
		//this is done to cleanup the auto-generated elements from tinymce
		if(sticky==false){
			document.location.reload(true);
			return;
		}

		var sticky= document.getElementById("stickytext");
		var nonsticky= document.getElementById("nonsticky");
		sticky.style.display=sticky.style.display=="none"?"block":"none";	
		sticky= document.getElementById("stickytitle");
		sticky.style.display=sticky.style.display=="none"?"block":"none";
		nonsticky.style.display=sticky.style.display!="none"?"none":"block";
		init();
		set_date(0);
	}

	function set_date(date){
		var usedDate=date==0?new Date(0):new Date();
		document.getElementById('datefield').value = usedDate.toJSON().slice(0,10);
	}

		</script>

<!-- the following code is for the tinymce WYSIWYG editor -->
<script type="text/javascript" src="<?echo $server_root;?>/common/js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
init= function(){
	tinymce.init({
    selector: "textarea.edittable",
    theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste moxiemanager"
    ],
    toolbar1: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ],
});};

</script>

<body onload="set_date();">


<form enctype="multipart/form-data" method="post" name="lecture_entry" 
      action="index.php?page=local_uploader&type=<?echo $_GET['type'];?>" class="form">
	<fieldset>
	<?php 
	echo "<div style='display:none;'>";
	$this_course=$course?$course:"Course Name Here";
	$style=$course?"display:none":"display:block;";
	echo "</div>";
	echo "<input type='text' name='course' style='$style' value='$this_course'>";
?>

Type of material: <input type="text" name="type" value="<?echo $_GET['type'];?>" readonly>
<label class="checkbox">
<input type="checkbox"  onclick="toggle_sticky(this.checked);" 
       name="sticky_check" value="true"class="checkbox"">
	Sticky Topic 
</label>
	<p class="text-warning">Note: You can overwrite previous material by using the same #</p>
	<table id='nonsticky' style="display:block">
		<tr>
			<td>
				<label>#</label>	
				<input type="number" name="lecNo" id="LecNofield" min="0" max="100">
				
			</td>		<td>
				<label>Date</label>	
				<input type="date" name="date" id="datefield">
				
			</td>
			<td>
				<label>Topic</label>
				<textarea name="topic"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<label>Files</label>
				<textarea id="filetext" name="filetext" class="edittable"></textarea>
			</td>
			<td>
				<label>Suggested Reading</label>
				<textarea name="reading" class="edittable"></textarea>
			</td>
			<td>
				<label>Announcements</label>
				<textarea name="announcements" class="edittable"></textarea>
			</td>
					
			</td>
		</tr>
		
	</table>

	<input name='stickytitle' id='stickytitle'style='display:none;' value="Sticky post Title"> 

	<textarea id="stickytext" class='sticky edittable' name="stickytext" style="display:none; width:100%">
		write the content of your post here	</textarea>
	<input type="submit" id="submit" name="submit" value="Submit <?echo $_GET["type"];?>" class="btn btn-primary btn-large">

	</fieldset>	

</form>
	
	<? include "$site_root/common/upload.php";?>

</body>


<?

function current_year($course){
	$query = "SELECT Year From Courses WHERE Alias='$course'";
	global $con;
	$result = mysqli_query($con, $query);
	if ($result != null && $row = mysqli_fetch_array($result)) {
		return $row['Year'];
	}
	alert("could not retrieve the year of course $course","error");	
}

function store_material($course, $type, $lecNo, $date, $topic, $filetext, $reading, $announcements){
	global $con; 
	//escape special chars in case there is an link in the user text
	$filetext=urlencode($filetext);
	$announcements=urlencode($announcements);
	$reading=urlencode($reading);
	$topic= urlencode($topic);
	$year=now();

	$query ="DELETE FROM CourseMaterial WHERE Course='$course' AND Type='$type' AND Year='$year'
		AND Number='$lecNo';";
	mysqli_query($con, $query);
	
	$query = "INSERT INTO CourseMaterial(Course, Type, Number, Date, Topic, FileText,  Reading, Announcements, Year)
		VALUES('$course', '$type', '$lecNo','$date', '$topic', '$filetext', '$reading', '$announcements', '$year')";
	if (mysqli_query($con, $query))
		alert( "entry \"$topic \"added succesfully ", "success");	
		else{
			 alert("an error occurred with $topic: ".mysql_error(),"error");
		}
	
}

