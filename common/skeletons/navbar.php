<?
//load the available courses
$query = "SELECT Title, Alias FROM Courses WHERE LOWER(Level)='undergraduate'";
$result = mysqli_query($con, $query);
$pregrad = array();
while ($result != null && $row = mysqli_fetch_array($result)) {
	$pregrad [$row['Title']] = $row['Alias'];
}
$query = "SELECT Title, Alias FROM Courses WHERE LOWER(Level)='postgraduate'";
$result = mysqli_query($con, $query);
$postgrad = array();
while ($result != null && $row = mysqli_fetch_array($result)) {
	$postgrad[$row['Title']] = $row['Alias'];
}

?>    
<div class="navbar">
	<div class="navbar-inner">
		<div class="fluid">
			<a href="<?php echo $server_root ?>" id="logo">
				<img src=<? echo "'$server_root/common/img/logo.png'" ?>> </a> 
			<button type="button" class="btn btn-navbar" data-toggle="collapse"
				data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

			<!-- navbar liks -->
			<div class="nav-collapse collapse pull-right">
				<ul class="nav">
					<li> <a href="<?php echo $server_root ?>">
							<i class="icon-home pull-left"></i> Home</a>
					</li>
					<!-- "Research" Dropdown -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-search pull-left"></i>Research <b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $server_root ?>/research/"> 
									<i class='icon-search'></i>Areas of Research</a> 
							</li>
							<li>
								<a href="<?php echo $server_root ?>/publications/">
									<i class='icon-globe'></i>Publications</a> 
							</li>
							<li>
								<a href="<?php echo $server_root ?>/projects/">
									<i class='icon-gears'></i>Projects </a> 
							</li>
							<li>
								<a href="<?php echo $server_root ?>/diplom/">
									<i class='icon-pencil'></i>Diploma Thesis</a>
							</li>
						</ul>
					</li>

					<!-- Courses Dropdown --> 
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-book pull-left"></i>Courses <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li class="nav-header">Undergraduate</li>
<?
foreach ($pregrad as $t => $a) {
	echo "<li><a href='$server_root/courses/$a'>
			<i class='icon-book'></i>$t</a></li>";
}
?>
							<li class="divider"></li>
							<li class="nav-header">Postgraduate</li>
<?
foreach ($postgrad as $t => $a) {
	echo "<li> <a href='$server_root/courses/$a'>
			  <i class='icon-book'></i>$t</a></li>";
}
?>
						</ul>
					</li>

					<!-- Quick Links Dropdown -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-globe pull-left"></i>Links <b class="caret"></b></a>
						<ul class="dropdown-menu" id="quicklinks">
                                                <?php include 'quick_links.html'; ?>
						</ul>
					</li>

					<!-- "About" Dropdown -->
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-info-sign pull-left"></i>Profile<b class="caret"></b>
						</a>
						<ul class="dropdown-menu">
							<li>
								<a href="<?php echo $server_root ?>/staff/">
									<i class='icon-user'></i>Staff </a>
							</li>
							<li>
								<a href="<?php echo $server_root ?>/contact/">
									<i class='icon-phone-sign'></i>Contact</a>
							</li>
						</ul>
					</li>



				</ul>
			</div>
		</div>
	</div>
</div>