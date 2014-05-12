<?php
include"../common/DB_tools.php";

?>


<form action="../common/static_editor.php" method="get">
	<select name="title" type="text" required>
		<option value="mainpage">CSLab Main Page</option>
		<option value="researchpage">Research Page</option>
	</select>
	<input name="context" type="text" value="main" style="display:none;">
	<input type="submit" id="submit" value="Choose this">
</form>