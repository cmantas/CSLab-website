<script>
	uploadedall="";
	uploaded="";
	function sendForm() {
		if(document.getElementById("filename").value==""){
			alert("you need to provide a screen name for the file you upload!");
			return;
		}
		var oData = new FormData(document.forms.namedItem("fileinfo"));
		oData.append("CustomField", "This is some extra data");

		var oReq = new XMLHttpRequest();
		var stasher=<?echo "'". $server_root ."/common/stash.php'";?>;
		oReq.open("POST", stasher, true);
		oReq.onload = function(oEvent) {
			if (oReq.readyState == 4 && oReq.status === 200) {
				onSuccess(oReq);
			} else {
				document.getElementById("output").innerHTML = "Error " + oReq.status + " occurred uploading your file.<br \/>";
			}
		};

		oReq.send(oData);

	}

	function onSuccess(resp) {
		var oOutput = document.getElementById("output");
		var oOK = document.getElementById("OK_label");
		var uploaded_prompt = document.getElementById("uploaded_files");
		var link; 
		try{
			link= resp.responseXML.documentElement.
			getElementsByTagName("link")[0].firstChild.nodeValue;
		}catch(err)	{
			document.getElementById("output").innerHTML=resp.responseText;
		}
		ok = resp.responseXML.documentElement.getElementsByTagName("OK")[0].firstChild.nodeValue;
		//oOK.innerHTML = ok;
		if (ok == "OK") {
			oOutput.innerHTML = "<div class='alert alert-success'> \n\
<button type='button' class='close' data-dismiss='alert'>&times;</button> file saved in: " + link+"</div>";
			var filename=document.getElementById("filename").value;
			uploaded=" <a href='" + link + "' ><i class='icon-file'></i>"+filename+"</a>";
			uploadedall+=uploaded;
			uploaded_prompt.innerHTML="<span class='label label-success'>\n\
			You Can Copy the link(s):\t</span> "+uploadedall;
			refresh();
		}
		else {
			document.getElementById("output").innerHTML += "file was not saved\n"
				+resp.toString();
		}
		



	}
</script>


<div class="upload hero-unit center"  >
	<h2 class="page-header">File Upload Widget <br><small>(use only from the same directory level as where the links will be displayed)</small></h2>
<form enctype="multipart/form-data" method="post" name="fileinfo" >
      <fieldset>
		<legend>
			displayed name (eg. "slides in pdf"):
		</legend>
		<input type="text" name="filename" id="filename" required><br>
		<input type="text" name="folderpath" class='hidden'
		       value='<?echo $folderpath;?>'><br>
		<legend>
			File to save:
		</legend>
		<i class="icon-file"></i><input type="file" name="file" required > 
		<label id="OK_label"></label>
		<div id="output"></div>
	</fieldset>

</form>
	<button onclick="javascript:sendForm();" class="btn btn-large btn-info"><i class="icon-upload-alt"></i>Upload File </button>

	<legend>Uploaded Files: </legend>
	
<pre id="uploaded_files" class="well well-small"></pre>

</div>


;



