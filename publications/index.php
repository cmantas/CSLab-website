<?php $title = "Find Cslab Publications";
$title_icon='icon-globe';
include "../common/tools.php";	
?>

		<script>
			function showResult()
			{
				title_q = document.getElementById("titletext").value;
				author_q = document.getElementById("authortext").value;
				convention_q = document.getElementById("conventiontext").value;
				yearfrom_q = document.getElementById("yearfromtext").value;
				yearto_q = document.getElementById("yeartotext").value;


				if (title_q.length <= 2 && author_q.length < -2 && convention_q.length <= 2)
					return;

				if (window.XMLHttpRequest)
				{// code for IE7+, Firefox, Chrome, Opera, Safari
					xmlhttp = new XMLHttpRequest();
				}
				else
				{// code for IE6, IE5
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
				xmlhttp.onreadystatechange = function()
				{
					if (xmlhttp.readyState === 4 && xmlhttp.status === 200)
					{
						var livesearch=document.getElementById("livesearch");
						livesearch.innerHTML =xmlhttp.responseText;
						//livesearch.parentNode.removeChild(livesearch);
						livesearch.parentNode.appendChild(livesearch);
					
					}
				};
				xmlhttp.open("GET", "livesearch.php?title_q=" + title_q + "&author_q=" + author_q +
					"&convention_q=" + convention_q + "&yearfrom_q=" + yearfrom_q + "&yearto_q=" + yearto_q, true);
				xmlhttp.send();
			}

		</script>
	
<?

//start generating content in buffer
		ob_start();
                ?>

                <form >
                    <!-- Form Name -->
                    <legend>Search for Article</legend>
                    <div class="form-horizontal span6">
                        <div class='control-group'>
                            <label class="control-label">Title</label>
                            <div class="controls">
                                <input id="titletext" type="text" size="30" 
                                       placeholder="find in title" onkeyup="showResult();">	
                            </div>
                        </div>

                        <div class='control-group'>
                            <label class="control-label">Author</label>

                            <div class="controls">
                                <input id="authortext" type="text" size="30"	
                                       placeholder="find in authors" onkeyup="showResult();">
                            </div>

                        </div>

                        <div class='control-group'>
                            <label class="control-label">Appeared in</label>
                            <div class="controls">
                                <input id="conventiontext" type="text" size="30" 
                                       placeholder="convention"  onkeyup="showResult();">
                            </div>

                        </div>
                    
                    
                    
                    </div>
                    <div class="form-horizontal span5">

                        <div class='control-group'>
                            <label class="control-label">Year from</label>
                            <div class="controls">
                                <select id="yearfromtext"onChange="showResult();">

                                    <?php
                                    #display all years in dropdown and select the min
                                    $years = \get_all_years();
                                    foreach ($years as $y) {
                                        $selected = $y == min($years) ? "selected" : "";
                                        echo "<option value=\"$y\" $selected>$y</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                            
                        </div>
                        
                        
                        <div class='control-group'>
                        <label class="control-label">Year to</label>
                          <div class="controls">

                              <select id="yeartotext"onChange="showResult();">
                                  <?php
                                  #display all years in dropdown and select the max
                                  foreach ($years as $y) {
                                      $selected = $y == max($years) ? "selected" : "";
                                      echo "<option value=\"$y\" $selected>$y</option>";
                                  }
                                  ?>
                              </select>
                          </div>
                        
                                
                    </div>
                    </div>

		</form>
		<div id="livesearch"></div>

		<script> 
		showResult();
		</script>
<? 
//======================================= Show the page =====================================================
	//get the content to be displayed
	$content= ob_get_clean();
	//render the page	
	include "$site_root/common/skeletons/common_frame.php";

	