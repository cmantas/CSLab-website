<?php 	
include "../common/tools.php";

$title="Computing Systems Laboratory Courses";


//load the available courses
$query = "SELECT Title, Alias FROM Courses WHERE LOWER(Level)='undergraduate'";
$result = mysqli_query($con, $query);
$pregrad = array();
while ($result != null && $row = mysqli_fetch_array($result)) {
	$pregrad [$row['Title']]=$row['Alias'];
}
$query = "SELECT Title, Alias FROM Courses WHERE LOWER(Level)='postgraduate'";
$result = mysqli_query($con, $query);
$postgrad = array();
while ($result != null && $row = mysqli_fetch_array($result)) {
	$postgrad[$row['Title']]=$row['Alias'];
}

ob_start();
               echo "<h2 >Undergraduate</h2> \n<ul class='blocklist unstyled'>";
			foreach ($pregrad as $t => $a) {
				echo "<li> <a href='$server_root/courses/$a'>
					<i class='icon-book'></i>$t</a></li>";
			}
		
		echo	"</ul>\n <h2>Postgraduate</h2>\n<ul class='blocklist unstyled'>";
			  foreach ($postgrad as $t => $a) {
				  echo "<li><a href='$server_root/courses/$a'>
					  <i class='icon-book'></i>$t</a></li>";
			  }
		echo "</ul>";
$content=  ob_get_clean();


END:
include "$site_root/common/skeletons/common_frame.php";


?>	