

<div class="span2 leftpanel hero-unit">
<ul class="nav nav-pills nav-stacked span12">
    <h2> <?echo $course;?></h2>
 		<li>
			<a href="<? echo "$server_root/courses/$course"; ?>">
				<i class="icon-home pull-left"></i>
				Home
			</a> 
		</li><li>
			<a href="<? echo "$server_root/courses/$course/info"; ?>">
				<i class="icon-info-sign pull-left"></i>
				Info
			</a> 
		</li><li> 
			<a href="<? echo "$server_root/courses/$course/lectures"; ?>">
				<i class="icon-calendar pull-left"></i>
				Lectures
			</a> 
		</li><li>
			<a href="<? echo "$server_root/courses/$course/homework"; ?>">
				<i class="icon-keyboard pull-left"></i>
				Exercises
			</a>
		</li><li>
			<a href="<? echo "$server_root/courses/$course/exams"; ?>">
				<i class="icon-pencil pull-left"></i>
				Exam Material
			</a>
		</li><li>
			<a href="<? echo "$server_root/courses/$course/grades"; ?>">
				<i class="icon-bar-chart pull-left"></i>
				Final Grades
				
			</a>
		</li>

		<?
//load static pages for this particular course
		$query = "SELECT Title, PageTitle FROM Static_Content WHERE Context='$course' AND Title NOT IN('main','info','homework', 'grades')";
		$result = mysqli_query($con, $query);
		$pages = array();
		while ($result != null && $row = mysqli_fetch_array($result)) {
			$page_title = $row['PageTitle'];
			$alias = $row['Title'];
			echo "<li><a href='$server_root/courses/$course/$alias'>
		<i class='icon-globe pull-left'></i>$page_title</a></li>";
		}
		?>
</ul>
</div>