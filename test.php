<?php

include "common/tools.php";

$data = \load_static("test10", "main");
		$title = $data["PageTitle"];
		echo "titlos= $title <br>";
		$content = $data['Content'];
		echo "content:<br> $content";
		

?>
