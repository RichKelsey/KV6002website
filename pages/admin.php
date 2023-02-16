<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Administrator Dashboard </title>
		<!--<link href="custom.css" rel="stylesheet">-->
	</head>
	<!--<body BGCOLOR="#d6f5f5">-->

	<main >

		<!-- dummy iframe is to not redirect the user away from the dashboard when they submit the form -->
		<iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>

		<!-- Button reveals Create Post interface -->
		<button onclick="PostInterface()">New Post</button>


		<?php 

			$directory="../img/avatars/";
			$files = array();

			//to select the first avatar by default
			$tmp = 0;


			echo'
			<!-- Post creation form -->

			<div id="CreatePost" style="display:none">
				<form action="../php/ProcessUpload.php" method="post" enctype="multipart/form-data" target="dummyframe" onsubmit="return validateForm()">';
					
				echo"<br><label for='avatar[]'>Select an avatar:<br><br>";

				//display all available avatars
				foreach (scandir($directory) as $file) 
				{
					if ($file !== '.' && $file !== '..') 
					{
						$files[] = $file;
						
						echo"<img src='../img/avatars/$file'" . "width='40' height='40' />";
						
						//to select the first avatar by default
						if($tmp == 0)
						{
							echo"<input type='checkbox' name='avatar[]' value='{$file}' onclick='selectOnlyOne(this)' checked >";
						}
						else
						{
							echo"<input type='checkbox' name='avatar[]' value='{$file}' onclick='selectOnlyOne(this)'>";
						}
						$tmp +=1;
						
					}
				}
				echo"</label><br><br>";
				echo'
					<br>
					<label for="Name">Username:</label>
					<input type="text" id="Name" name="Name" required>

					<label for="Group">Group ID:</label>
					<input type="number" id="Group" name="Group" required><br><br><br>

					<label for="file" style="cursor: pointer;">Upload Image (optional)</label>
					<input type="file"  accept="image/*" name="Image" id="Image"  onchange="loadFile(event)"><br>
					<img id="output" width="200" /><br><br>

					<label for="Post">Post:</label>
					<input type="text" id="Text" name="Text" required><br><br>

					<input type="submit" value="Submit" id="Submit" onclick="setTimeout(DisplayOutput,500)">
				</form>
			</div>

			<!-- This is where the output from uploading the image is displayed -->

			<div id="OutputFromForm">
				<p></p>
			</div>';
		


			

		?>
		
		<script src="https://code.jquery.com/jquery-3.6.3.min.js" 
		integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" 
		crossorigin="anonymous"></script>

		<script>

			let isDisplayed = 0;
			
			//toggle between displaying the interface and not when the button is clicked
			function PostInterface()
			{
				//get div
				var x = document.getElementById("CreatePost");


				if (isDisplayed === 0) {
					x.style.display = "block";
				} else {
					x.style.display = "none";
				}

				
				if (isDisplayed == 0)
				{
					isDisplayed = 1;
				}
				else
				{
					isDisplayed = 0;
				}
				
			}

			var loadFile = function(event) {
				var image = document.getElementById('output');
				image.src = URL.createObjectURL(event.target.files[0]);
			}


			function DisplayOutput()
			{
				$("#OutputFromForm").load("../php/ProcessingOutput.txt");
			}

			function selectOnlyOne(checkbox) 
			{
				var checkboxes = document.getElementsByName('avatar[]');
				checkboxes.forEach((item) => 
				{
					if (item !== checkbox) item.checked = false;
				});
			}

			function validateForm()
			{
				var checkboxes = document.getElementsByName("avatar[]");
				var checked = false;
				for (var i = 0; i < checkboxes.length; i++)
				{
					if (checkboxes[i].checked) 
					{
						checked = true;
						break;
					}
				}
				if (!checked)
				{
					alert("Please select an avatar.");
					return false;
				}
			}



		</script>
		
	</main>
	<aside>
	
		
	</aside>
	</div>
	
	</body>
</html>