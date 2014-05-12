<?PHP

include 'tools.php';
$title="static content editor";
ob_start();
	
$page_content="";
$content_context="";
$content_title="";
$page_title="";
$ro=false;
$folderpath="$site_root/files_store";

if(isset($_GET['context'])){
	$content_context=$_GET['context'];
	switch ($content_context){
		case "main":
			$folderpath="$site_root/files_store";
			break;
	}
}

if(isset($_GET['context'])&& isset($_GET['title'])){
	$content_title=$_GET['title'];
	$data=\load_static($content_context, $content_title);
	$page_content=$data["Content"];
	$page_title=$data["PageTitle"]!="Not Found"?$data["PageTitle"]:"";
	$ro=true;
}

if (isset($_POST['content'])) {
	$content_context=$_POST['context'];
	$page_content=$_POST['content'];
	$content_title=$_POST['title'];
	$page_title=$_POST['pagetitle'];
	
	\store_static($content_context, $content_title, $page_title, $page_content);
	echo "<script> alert('$content_title was added'); history.go(-2);</script>";
}



?>





<body>

	<h2 class="header"> Static Content Editor</h2>
<form method="post" action="<?echo "$server_root/common/static_editor.php"?>" 
     class="form">
	context: <input name="context" value="<?echo $content_context;?>" readonly>
    
    <legend> Page Alias (as used in URL)</legend> 
    <input name="title"value="<? echo $content_title;?>" <?echo $ro?"readonly":"";?>	required>
    <legend> Page Title</legend>
    <input name="pagetitle" <? echo $page_title!=""?"value='$page_title'":"";?> required >
    <legend>Use Editor</legend>
    <button type='button'onclick='tiny_init();'class='btn btn-large'>
	    <i class='icon-edit-sign'></i>WYSIWYG editor (use at own risk)</button>
    <legend> Content (generated as html)</legend>
    <textarea name="content" style="width:100%" rows="40">
	 <?echo $page_content ;
   	 if ($page_content=="")
		 echo "You can use here links copied from the file upload widget (if there is one below)"; 
	 ?>
    </textarea>
    
    	<button type="submit" id="submit" name="submit" class="btn-large btn-success"
	   style="width:30%"><i class='icon-save'></i>Save Content</button>
	
	<a href="<?echo "$server_root/common/skeletons/bootstrap.html"?>"class="btn-large  btn-info"
	      style='width:30%;'>Find pretty html examples here</a>
</form>


<?

if (isset ($_GET['upload']))
	include "$site_root/common/upload.php";

?>
<script type="text/javascript" src="<?echo "$server_root/common/js/tinymce/tinymce.min.js";?>"></script>
<script type="text/javascript">
function tiny_init(){
tinymce.init({
    selector: "textarea",
    theme: "modern",
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste moxiemanager"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ],
    cleanup : false ,
    verify_html : false,
    cleanup_on_startup: false,
    trim_span_elements: false
});
};
//tiny_init();
</script>
<?

$content=  ob_get_clean();
include 'skeletons/common_frame.php';
?>
<script src=<?echo "'$server_root/common/js/file_input.js'";?>></script> 
<script>
$(document).ready(function(){$('input[type=file]').bootstrapFileInput();});
</script>