<? 
if(isset($_GET['fid'])) 
{ 
include "tools.php"; 
$con= \connect_DB();
// query the server for the picture 
$fid = $_GET['fid']; 
$query = "SELECT * FROM files WHERE fid = '$fid'"; 
$result  = mysqli_query($con, $query) or die(mysql_error()); 

$row=mysqli_fetch_array($result);
// define results into variables 
$name=$row["name"]; 
$size=$row["size"]; 
$type=$row["type"]; 
$page_content=$row["content"];
// give our file the proper headers...otherwise our page will be confused 
header("Content-Disposition: inline; filename=$name"); 
header("Content-length: $size"); 
header("Content-type: $type"); 
echo $page_content; 

mysqli_close($con); 
}else{ 
die("No file ID given..."); 
} 

?>