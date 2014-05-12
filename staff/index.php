
	<?php
	$title = "Staff";
	$title_icon="icon-user";
	include '../common/tools.php';



function print_modal($name, $content) {
	$id = str_replace(' ', '', $name);
	$modalLabel = $id . "ModalLabel";
	echo <<<MOD
<!-- Button to trigger modal -->
<a href="#$id" role="button" class="btn btn-info" data-toggle="modal"><i class="icon-info-sign"></i>Info</a>
 
<!-- Modal -->
<div id="$id" class="modal hide fade person_details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="$modalLabel">$name</h3>
  </div>
  <div class="modal-body">
		$content
      </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
MOD;
}
	
	
	function print_category($cat){
		global $con;
		$query = "SELECT * FROM Staff WHERE Category='$cat'";
		$result = mysqli_query($con, $query);
		echo "<table class='staff table-striped table-hover table-condensed'>";
		echo "<h2><span> $cat </span></h2>";
		while ($result != null && $row = mysqli_fetch_array($result)) {
			$name=$row['Name'];
			
			$rank=$row['Rank']==''?'':"  (".$row['Rank'].")";
			$email=$row['Email'];
			$telephone=$row['Telephone'];
			$page=$row['Webpage'];
			$bio=$row['Bio'];
			$context=$row['Context'];
			echo <<<END
			<tr class="person" >
				<td >$name  $rank
				</td>
END;
			
				$content=<<<END
			<div name="details" class="person_details" >
				email: $email 
				<br> telephone: $telephone
				<br> title:
				</div>
END;
				echo "<td >";
				print_modal($name, $content);
				echo "</td>\n<td class='link'>";
				if  ($page!="") 
					echo "<a href='$page' class='btn btn-primary' >
						<i class='icon-globe'></i>Page</a>"	;	
				echo "</td>\n<td class='link'>";
				if  ($email!="") 
					echo "<a href='mailto:$email' class='btn btn-success' >
						<i class='icon-envelope'></i>Mail</a>"	;
				echo	"</td";
			echo "</tr>";

			
		}
		echo "</table>";
	}//print category
	
	?>
		<?php
		//generate contents
		ob_start();
		print_category("Faculty");	
		print_category("Graduates");	
		print_category("Alumni");	
		print_category("Undergraduates");	
		$content= ob_get_clean();

		//print the page
		include "$site_root/common/skeletons/common_frame.php";
		?>
