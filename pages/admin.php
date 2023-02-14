<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title> Administrator Dashboard </title>
		<!--<link href="custom.css" rel="stylesheet">-->
	</head>
	<!--<body BGCOLOR="#d6f5f5">-->

	<main >


		<?php 
			//include 'connection.php';
			//write entered values into variables to later display
			require_once "credentials.php";
			try 
			{
				$dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
				$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				
				//display Interface for creating a post
				//iframe is to not redirect the user from the dashboard when they submit the form
				echo '
				
				<iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>


				<button onclick="PostInterface()">New Post</button>
				
				<div id="CreatePost" style="display:none">
					<form action="ProcessUpload.php" method="post" enctype="multipart/form-data" target="dummyframe">
						<br>
						<label for="Name">Username:</label>
						<input type="text" id="Name" name="Name">
		
						<label for="Group">Group ID:</label>
						<input type="number" id="Group" name="Group"><br><br>
		
						<label for="file" style="cursor: pointer;">Upload Image (optional)</label>
						<input type="file"  accept="image/*" name="Image" id="Image"  onchange="loadFile(event)"><br>
						<img id="output" width="200" /><br><br>
		
						<label for="Post">Post:</label>
						<input type="text" id="Text" name="Text"><br><br>
						<input type="submit" value="Submit" id="Submit" onclick="setTimeout(DisplayOutput,500)">
					</form>
				</div>
				<div id="OutputFromForm">
				<p>xd</p>
				</div>';
		

			} catch (PDOException $e) 
			{
				die("Error!: " . $e->getMessage() . "<br/>");
			}
			

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
				$("#OutputFromForm").load("ProcessingOutput.txt");
			}



		</script>
		
	</main>
	<aside>
	
		
	</aside>
	</div>
	
	</body>
</html>