<?php
include "../common/tools.php";
ob_start();

if(isset($_GET["course_alias"])&&isset($_GET["now"])
	&&isset($_GET["name"]) && isset($_GET["level"])){
	

$course=$_GET["course_alias"];
$name=$_GET["name"];
$now=$_GET["now"];
$level=$_GET["level"];
$admin=$_GET['admin'];
$pass=$_GET['admin_pass'];
error_reporting(E_ERROR | E_PARSE);

$success=true;
$err_msg="";
//create course folder
if( !mkdir("$site_root/courses/$course")){
	$success=false;
	$err_msg.="*failed to create $course directory\n";
}


//create the course files (file are hard links)
$from_file="$site_root/common/skeletons/course_index.php";
$to_file="$site_root/courses/$course/index.php";
if (!copy($from_file,$to_file)) {
	$success=false;
    	$err_msg.= "*failed to copy course index file($from_file to $to_file)\n";
}

//create the course .htaccess
$from_file="$site_root/common/skeletons/course_.htaccess";
$to_file="$site_root/courses/$course/.htaccess";
if (!copy($from_file,$to_file)) {
    $success=false;
    $err_msg.= "*failed to create course .htaccess file ($from_file to $to_file)\n";
}

//create the .htaccess file for the course webmaster dir
$dirs = explode('/', getcwd());
unset($dirs[count($dirs)-1]);
$root_path= implode("/",$dirs);
$coursepath="$root_path/courses/$course";
$htaccess=<<<HTA
RewriteEngine on
RewriteRule ^([a-zA-Z0-9_-]+)$ index.php?page=$1
RewriteRule ^([a-zA-Z0-9_-]+)/$ index.php?page=$1 

#RewriteCond %{HTTPS} !=on
#RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
	
AuthName "$course Webmaster Page"
AuthType Basic
AuthBasicProvider file
AuthUserFile $coursepath/webmaster/.htpasswd
Require valid-user
HTA;
$webmaster_dir="$site_root/courses/$course/webmaster";
mkdir($webmaster_dir);
file_put_contents("$webmaster_dir/.htaccess", $htaccess);
//create the .htpasswd file in the webmaster dir
exec("htpasswd -bc $webmaster_dir/.htpasswd $admin $pass");

//link the webmaster page 
$from_file="$site_root/common/skeletons/course_webmaster.php";
$to_file="$webmaster_dir/index.php";
if (!link($from_file,$to_file)) {
	$success=false;
    	$err_msg.= "failed to link $from_file to $to_file\n";
}


$query="INSERT INTO Courses VALUES('$name', '$course', '$now', '$level')";

if(!mysqli_query($con, $query)){
	$success=false;
    	$err_msg.= "failed to Failed to add $course in the DataBase";
}

if($success){
	alert("$course created successfully","success");
}
else{
	alert($err_msg,"error");
}

}
?>

<form role="form" class="form-horizontal" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
        <label class="col-sm-2 control-label" >Course Alias</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" name="course_alias" required 
                   placeholder="alias as it appears in URL">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">Now Year</label>
        <div class="col-sm-3">
            <input type="text" name="now"  required class="form-control" placeholder="2020-2021">
        </div>
    </div>	

    <div class="form-group">
        <label class="col-sm-2 control-label">Course Name</label>
        <div class="col-sm-3">
        <input type="text" name="name" class="form-control" required placeholder="Full Name of the course">
        </div>
    </div>

    <div class="form-group">    
        <label class="col-sm-2 control-label">Level</label>
        <div class="col-sm-3">
        <select name="level" class="form-control" required>	
            <option default>Undergraduate</option>
            <option>Postgraduate</option>
        </select>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label">1st Admin</label>
        <div class="col-sm-3">
        <input class="form-control" type="text" name="admin" placeholder="username" required/>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">Password</label>
        <div class="col-sm-3">
        <input type="text" class="form-control" name="admin_pass" value="" placeholder="password" required/>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-2">
            <input type="submit" id="submit" class="form-control btn-primary btn-large" value="Create">
        </div>
    </div>

</form>



<?php

$title="Create Course Page";
$content="";
$content.=  ob_get_clean();

include "$site_root/common/skeletons/common_frame.php";
?>