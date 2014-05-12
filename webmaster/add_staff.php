
	<script>
		function validateForm()
			{
				var x = document.forms["add_staff"]["name"].value;
				if (x == null || x == "")
				{
					alert("name must be filled out");
					return false;
				}
				x = document.forms["add_staff"]["email"].value;
				if (x == null || x == "")
				{
					alert("email must be filled out");
					return false;
				}
				var atpos = x.indexOf("@");
				var dotpos = x.lastIndexOf(".");
				if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length)
				{
					alert(x+"is not a valid e-mail address");
					return false;
				}
				x = document.forms["add_staff"]["category"].value;
				if (x == null || x == "")
				{
					alert("person category (faculty/graduates etc) must be filled out");
					return false;
				}

			}

		function setFocus() {
				var input = document.getElementsByName("titletext")[0];
				input.focus();
			}
	</script>	

<?php
	include '../common/tools.php';
	$title= 'Add CSLab Staff';
ob_start();

//if there is something submitted, execute the following code to save it, before displaying the form
if (isset($_POST['submit'])) {
	$name = $_POST['name'];

	//in case of an amendment, delete the previous entry
	if(isset($_POST['amendment'])&& $_POST['amendment']=="true"){
		echo "this is an amendment <br>";
		$query="DELETE FROM Staff WHERE Name='$name'";
		mysqli_query($con,$query);
	}
	//keep the data from the form
	
	$email = $_POST['email'];
	$category = $_POST['category'];
	$rank = $_POST['rank'];
	$bio = $_POST['bio'];
	$webpage = $_POST['webpage'];
	$context=$_POST['context'];
	$telephone=$_POST['telephone'];
	
	//store the entry in the DB
	
	$query = "INSERT INTO Staff(Name, Webpage, Email, Telephone, Category, Rank, Bio, Context) 
		VALUES('$name', '$webpage', '$email', '$telephone', '$category', '$rank', '$bio', '$context')"; 
	
	mysqli_query($con,$query);

	echo "you have submitted the following persons: 
		<br> $name <br> $email<br>$category"; 

}

$amend=false;

if(isset($_GET['delete'])){
	$name=$_GET['delete'];
	$query="DELETE FROM Staff WHERE Name='$name'";
	mysqli_query($con,$query);	
	echo "<script language='javascript'> alert('deletion command for: $name'); history.back(); </script>";
}

if (isset($_GET['amend'])) {
	$amend=true;
	$name=$_GET['amend'];
	$query = "SELECT  Name, Webpage, Email, Telephone, Category, Rank, Bio, Context FROM Staff
		WHERE LOWER(Name) LIKE LOWER('$name')";
	$result=mysqli_query($con,$query);
	$row = mysqli_fetch_array($result);
	if ($row==null){
		echo "<script language='javascript'> alert('nobody by the name $name was found'); </script>";
		$amend=false;
		goto no_amendment;
	}
	
	$name = $row['Name'];
	$rank=$row['Rank']==''?'':"  (".$row['Rank'].")";
	$email=$row['Email'];
	$telephone=$row['Telephone'];
	$page=$row['Webpage'];
	$bio=$row['Bio'];
	$context=$row['Context'];
	$category=$row['Category'];

	echo "amending $name";
}
no_amendment:



	
function get_all_ranks($con){
	include '../common/DB_tools.php';
	$con = \connect_DB();
	$query="SELECT DISTINCT Rank FROM Staff";
	$result = mysqli_query($con,$query);
	$ranks=array();
	
	while($result!=null && $row = mysqli_fetch_array($result))
		array_push($ranks, $row['Rank']);
	return $ranks;
}

function get_all_categories($con){
	include '../common/DB_tools.php';
	$con = \connect_DB();
	$query="SELECT DISTINCT Category FROM Staff";
	$result = mysqli_query($con,$query);
	$categories=array();
	
	while($result!=null && $row = mysqli_fetch_array($result))
		array_push($categories, $row['Category']);
	return $categories;
}

?>

	
<form enctype='multipart/form-data' method="post" action="<?php echo $_SERVER['PHP_SELF'];
	?>" name="add_staff" onsubmit="return validateForm(); " >
    <label>Name:</label>
	<input name="name" type="text" size="50" <?php echo $amend?"value='$name'":'';?> >	
	<label>email:</label>	
	<input name="email" type="text" size="50" <?php echo $amend?"value='$email'":'';?> >
	<label>telephone:</label>
	<input name="telephone" type="text" size="15" <?php echo $amend?"value='$telephone'":'';?> >
	<label>title:</label>
	<select name="rank">
		<option selected> PhD</option>
		<option > MSc.</option>
		<option> Diploma</option>
		<option> Professor </option>
		<option> Professor Emeritus </option>
		<option> Associate Professor </option>
		<option></option>
	</select>
	<label>category:</label>
	<select name="category">
		<?php
		$categories = get_all_categories($con);
		foreach ($categories as $r){
			$selected=$amend && $category==$r?"selected":"";
			echo "<option value='$r' $selected>$r</option>";
		}
		?>
	</select>

	<label>personal page:</label>
	<input name="webpage" type="text" size="50" <?php echo $amend?"value='$page'":'';?> >
	<label>short bio:</label>
	<textarea name="bio"  ><?php echo $amend?$bio:'';?></textarea>
	<label>context:</label>
	<textarea name="context"  ><?php echo $amend?$context:'';?></textarea>
	<!-- hidden amendment  field-->
        <input name="amendment" style="display: none;" <?php echo $amend?"value='true'":'';?> > <br>
	<input type="submit" id="submit" name="submit" value="Submit" class="btn btn-large btn-primary"><br>
	
</form>
	<script> set_focus();</script>
	


	<?php 
	
	$content=  ob_get_clean();
	include "$site_root/common/skeletons/common_frame.php";

	
	?>