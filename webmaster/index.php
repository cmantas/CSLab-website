<html>
<?php 	
$title = "Webmaster page";
$title_icon="icon-user-md";

include "../common/tools.php";
//start generating content
ob_start();

if (isset($_GET['page'])) {

	switch ($_GET['page']) {
		case "add_admin":
			if (empty($_POST))
				continue;
			$name = $_POST['admin_name'];
			$pass = $_POST['admin_pass'];
			$out = shell_exec("htpasswd -b .htpasswd $name $pass");
			if ($out != ""){
				alert($out);
			}	
			else{
				alert("admin added", "success");
				
			}
			break;

		case "stash":
			include "$site_root/common/stash.php";
			exit();
		case "add_news":
			$context="main";
			include "$site_root/common/add_news.php";
			exit();
	}
}


?>

	 <ul class="list-unstyled inline col-md-2">
		 <li>
			 <h3><a href=add_news?Context=main" class="well col-md-12"><i class="icon-file-text-alt"></i> Add News </a></h3> 
		 </li>
		 <li>
			 <h3><a href="add_publication.php"class="well col-md-12"><i class="icon-globe"></i> Add Publication </a></h3>  
		 </li>
		 <li>
			 <h3><a href="add_staff.php"class="well col-md-12"><i class="icon-user"></i> Add Staff </a> </h3>  
		 </li>
		  <li>
			 <h3><a href="add_diplom.php"class="well col-md-12"><i class="icon-pencil"></i> Add Diplom </a> </h3>  
		 </li>
                 <li>
			  <h3><a href="course_builder.php" class="well col-md-12"><i class="icon-book"></i> Create a new Course</a> </h3> 
		 </li>
                 
         </ul>
         <ul class="list-unstyled inline">
		 <li class="well">
                     <h3><i class="icon-edit"></i> amend existing staff.<br> Name:
			 <input id="ammended_name" type="text" size="50">
			 <button type="button" class="btn btn-large btn-info"
				 onclick="document.location.href = 
						 'add_staff.php?amend='+document.getElementById('ammended_name').value;">
				 amend
			 </button></h3>
		 </li>

		  <li class="well">
                      <h3><i class="icon-remove-sign"></i>Delete existing staff.<br> Name:
			 <input id="deleted_name" type="text" size="50">
			 <button type="button" class="btn btn-large btn-info"
				 onclick="document.location.href = 
						 'add_staff.php?delete='+document.getElementById('deleted_name').value;">
				 delete
			 </button></h3>
		 </li>

		 <li class="well">
			 <h3><i class="icon-edit"></i>Edit static content
                             <form action="../common/static_editor.php" method="get">
				 <select name="title" type="text" required>
					 <option value="mainpage">CSLab Main Page</option>
					 <option value="research">Research Page</option>
					 <option value="projects">Projects Page</option>
					 <option value="contact">Contact Page</option>
				 </select>
				 <input name="context" type="text" value="main" style="display:none;">
				 <input name="upload" type="text" value="yes" style="display:none;">
				 <button type="submit" id="submit" class="btn btn-large btn-info">
					 <i class="icon-edit"></i>Edit
				 </button> 
			 </form></h3>

		 </li>

		 <li class="well">
			 <h3>
				 <form action="add_admin" method="POST" enctype="multipart/form-data">
				 <h3><i class="icon-user-md"></i>Add CSLab Admin</h3>
                                 Name: <input type="text" name="admin_name" placeholder="username" /><br>
                                 Password: <input type="text" name="admin_pass" placeholder="password" /><br>
				 <input type="submit" name="submit" value="Add" class="btn btn-info"/>	
			 </form></h3>
		 </li>
	 </ul>


<?php
 $content=  ob_get_clean();
 include "$site_root/common/skeletons/common_frame.php";
 END:
 ?>