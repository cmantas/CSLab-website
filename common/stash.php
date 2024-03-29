<?php
/*	Server Side Part of uploading a file
 * 
 *  	saves the file and creates an XML response with the outcome of saving
 * 	this process returns a relative link to the saved file. Thus, it can 
 * 	only work if called from the save dir level where the file will be stored
 *	otherwise the link will be brocken 
 *  
 */


if (!function_exists("stash_file")) {
function stash_file($file, $filename, $file_store_dir){
//keep the name of the file 
		//append a random string in case of filename collision
		if (file_exists($file_store_dir .'/'. $filename)) {
			$name_parts = explode(".", $filename);
			$filename = $name_parts[count($name_parts) - 2] . "_" . rand() .
				"." . $name_parts[count($name_parts) - 1];
		}
		$filepath=$file_store_dir.'/'.$filename;
		//create the target folder if does not exist and move the file there
		if (!file_exists($file_store_dir))
			mkdir($file_store_dir);
		if(\move_uploaded_file($file, $filepath))
			return $filepath;
		else return "";
}
}

function real_path_to_link($path){
	global $server_root;
	$fullpath_dirs = explode('/', realpath($path));
	$root_index=array_search("htdocs", $fullpath_dirs);
	$root_index=$root_index?$root_index:array_search("www", $fullpath_dirs);
	$diff_array=array_slice($fullpath_dirs, $root_index+1);
	return $server_root."/".implode("/",$diff_array);
}



header("Content-type: text/xml");


	//keep the data from the form
//if there is a file uploaded, store it
if (file_exists($_FILES['file']['tmp_name'])) {
	$folderpath=$_POST["folderpath"];
	$filepath = stash_file($_FILES['file']['tmp_name'], $_FILES['file']['name'], $folderpath);
	if ($filepath != "") {
		echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
		echo "<stash_response>";
		echo "\t<OK>OK</OK>\n";
		$link=  real_path_to_link($filepath);
		
		echo "<link>$link</link>";
	} else {
		echo "\t<OK>Failed<OK>\n";
	}
} else {
	echo "<OK>no file specified<OK>";
}

echo("</stash_response>");

?>