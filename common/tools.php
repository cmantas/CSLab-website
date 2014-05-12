<?php
error_reporting(E_ALL);

$host = "localhost";
//$user = "chris";
//$password = "V2Nsn2TV9B5SRzVq";
$user="cslab_new";
$password = "pwjW7QPsqQs6QYUA";
//$DB_name = "test1";
$DB_name="cslab_new";
//connencts to the datavase using the credentials above

$news_limit=15;


if (!function_exists("connect_DB")) {
	function connect_DB() {
		global $host, $user, $password, $DB_name;
		$con = mysqli_connect($host, $user, $password) or die('could not connect to database' . mysql_error());
		mysqli_select_db($con, $DB_name) or die('database not selected' . mysql_error());

		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		return $con;
	}

}

if (!function_exists("now")) {
function now(){
	global $course, $con;
	$query = "SELECT Year from Courses WHERE Alias='$course' ";
	$result = \mysqli_query($con, $query);
	if ($result != null && $row = mysqli_fetch_array($result)) {
		return $row['Year'];	
	}
}
}



if (!function_exists("get_all_years")) {
	function get_all_years($con = null) {
		if ($con == null)
			$con = connect_DB();

		$query = "SELECT DISTINCT Year FROM Publications ORDER by Year DESC
	";

		$result = mysqli_query($con, $query);
		$years = array();

		while ($result != null && $row = mysqli_fetch_array($result)) {
			array_push($years, $row['Year']);
		}

		return $years;
	}

}



//finds the site root
if (!function_exists("site_root_abs")) {
function site_root_abs() {
	$fullpath_dirs = explode('/', getcwd());
	$home_index = array_search("cslab", $fullpath_dirs);
	$dirs = array_slice($fullpath_dirs, 0, $home_index+1);
	return implode('/',$dirs);
}
}

//finds the site root
if (!function_exists("server_root")) {
function server_root() {
	$proto="http";
	if(isset($_SERVER['HTTPS'])&&$_SERVER["HTTPS"] == "on")
       		 $proto = "https";

	$fullpath_dirs = explode('/', getcwd());
	$home_index = array_search("cslab", $fullpath_dirs);
	$root_index=array_search("htdocs", $fullpath_dirs);
	$root_index=$root_index?$root_index:array_search("www", $fullpath_dirs);
	$diff_array=array_slice($fullpath_dirs, $root_index+1, $home_index-$root_index);
	$diff=implode("/",$diff_array);
	return	"$proto://".$_SERVER["SERVER_NAME"]."/$diff"; 
}
}



//prints an alert dialogue type must be one of: warning, error, info success
if (!function_exists("alert")) {
function alert( $message, $type="warning") {
	$up_type=  strtoupper($type);
	echo <<<ERR
<div class="alert alert-$type">
  <button type="button" class="close" data-dismiss="alert">&times;</button>
  <strong>$up_type!</strong> $message 
</div>
ERR;
}
}

function zip_and_download($folderpath, $file) {
        echo $folderpath;
        echo "<br>";
	$ret=  \exec("zip -r $file $folderpath");
        echo $ret;
	header('Content-type: application/zip');
	header("Content-Disposition: attachment; filename=\"$file\"");
	readfile($file);
}

//
if (!function_exists("find_my_course")) {
function find_my_course() {
	$temp =\dirname($_SERVER['PHP_SELF']);
        $pos= stripos($temp, "courses");
        $temp=substr($temp, $pos+8);
        $pos= stripos($temp, "/");
        if ($pos==0) return $temp;
        $temp=substr($temp, 0 ,$pos);
        return $temp;
}
}


$con=\connect_DB();

if(!isset($site_root))
	$site_root=  site_root_abs();

if(!isset($server_root))
	$server_root=  server_root ();

date_default_timezone_set('Europe/Athens');

include "$site_root/common/page_contents.php";



	


?>
