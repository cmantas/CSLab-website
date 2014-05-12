<?php
include '../common/tools.php';
//get the q parameter from URL
$title_q=filter_var($_GET["title_q"], FILTER_SANITIZE_STRING);
$author_q=filter_var($_GET["author_q"], FILTER_SANITIZE_STRING);
$convention_q=filter_var($_GET["convention_q"], FILTER_SANITIZE_STRING);
$yearfrom_q=filter_var($_GET["yearfrom_q"], FILTER_SANITIZE_NUMBER_INT);
$yearto_q=filter_var($_GET["yearto_q"], FILTER_SANITIZE_NUMBER_INT);

#connect to the DB
$con=  \connect_DB();

$html_list="";

$query="SELECT * FROM Publications WHERE
	LOWER(Title) LIKE LOWER('%$title_q%')
	AND LOWER(Authors) LIKE LOWER('%$author_q%')
	AND LOWER(Appeared) LIKE LOWER('%$convention_q%')
	AND Year>=$yearfrom_q
	AND Year<=$yearto_q
	ORDER by Year DESC
	";

$result = mysqli_query($con,$query);
$year_prev='0';
$all_years=array();
while($result!=null && $row = mysqli_fetch_array($result))
  {
 $title=$row['Title'];
 $link=$row['Authors'];
 $appeared=$row['Appeared'];
 $year=$row['Year'];
 $file=$row['Filepath'];
 
 #if year changed add  an "a" item and new "ul"
 if ($year<>$year_prev){
	 $year_prev=$year;
	 array_push($all_years, $year);
	 $html_list.=$html_list===""?"":"</table>\n
		 <a href='#'class='btn btn-block'>
		 <i class='icon-chevron-up'></i>Back to Top<i class='icon-chevron-up'></i>
		 </a>";
	
	 $html_list.="<a id=\"$year\" name=\"$year\"></a><h2><span><i class='icon-calendar-empty'></i>
		 $year </span></h2>";
	 $html_list.="<table class='table table-striped'>";
 }
 
 #generate table item for the publication
 
  $html_list.="<tr><td class='publication blocklist'>";
  	if($file!="") $html_list.="<a href='$file'>\n";
	$html_list.="<div class=\"title\"> <b>$title </b></div>\n";
	$html_list.="<div class=\"authors\"> $link </div>\n";
	$html_list.="<div class=\"published\">$appeared</div>\n";
	if($file!="") $html_list.="</a>\n";
  	$html_list.="</td>\n";
	$citation=$title.", ".$link.", ".$appeared;
  	$html_list.="<td class='link'><button class='btn right' 
		onclick=\"window.prompt ('Copy to clipboard: Ctrl+C, Enter', '$citation');\" >
	<i class='icon-copy'></i>Copy Citation</button></td>\n";
	$html_list.="</tr>";
  }
  
  $html_list.= "</table>";
  
  
  #generate html links for each year
  $link_text="<h2 class='span12'><span>Pick A Year</span></h2>\n";
  $link_text.="<ul class=\"years\" >\n";
  $count=count($all_years);
  $i=0;
  foreach($all_years as $y){
	$i++;
	 $link_text.="<li>";
		$link_text.="<a href=\"#$y\">$y</a>" ; 
	$link_text.="</li>\n";
  }
  $link_text.="</ul>\n";
  
  #append the links to the top of the generated list of publications
  $html_list=$link_text.$html_list;
 
  #close the connection
  mysqli_close($con);
  echo $html_list;



?>