<!doctype html>
<html lang="en">
	<head>
		<!-- temp css -->
		<link rel="stylesheet" href="../css/admin.css"> 
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
		
			//connect to the database
			require_once("../php/db_connection.php");
			$db = db_connect();

			// Check if connection successful
			if (!$db) die("Can't connect to database");


			echo'
			<aside>
			<!-- Button reveals Create Post interface -->
			<div class="dropdown">
				<button class="dropbtn">Download Statistics</button>
				<div class="dropdown-content">';

				//display all available groups
				$query = "SELECT GroupID FROM `Group`";

				$json_result = db_query($query, $db);
				$json_decoded = json_decode($json_result, TRUE);

				foreach($json_decoded as $key => $value)
				{
					echo "<button onclick='DownloadStatistics($key)'> Group $key </button>";
				}

			echo'
				</div>
			</div>

				<button class="dropbtn" onclick="DownloadIndividual()">Download Individual Activity</button>
			</aside>';
			

		?>
		
		<script src="https://code.jquery.com/jquery-3.6.3.min.js" 
		integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" 
		crossorigin="anonymous"></script>
		<script src="../js/queryDB.js"></script>

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

			function DownloadStatistics(GroupID)
			{
				var totals_data = [];
				var postIDs = [];
				//requesting results of a query asynchronously

				//query for statistics
				var queryAll = "SELECT A.PostID, SUM(HasLiked) AS Likes, SUM(RetentionTime) AS Retention, COUNT(A.PostID) AS Views " +
							"FROM Analytics A " + 
							"JOIN Post B " + 
							"ON A.PostID = B.PostID " +
							"AND B.GroupID =" + GroupID +
							" GROUP BY PostID";
	
				Query(queryAll);
			}

			function DownloadIndividual()
			{
				//query for actions of individuals
				var queryIndividual = "SELECT A.ParticipantID, GroupID, Name, ProfilePic, Bio, PostID, HasLiked, RetentionTime, Comment " +
									"FROM Participant A " +
									"JOIN Analytics B " +
									"ON A.ParticipantID = B.ParticipantID";

				Query(queryIndividual);		
			}

			function Query(query)
			{
				queryDB(query).then((returnedJSON) => 
				{

					const csv = JSONtoCSV(returnedJSON);

					// Create a new Blob object from the CSV string
					const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });

					// Create a new anchor element with the download attribute
					const link = document.createElement('a');
					link.setAttribute('href', '#');
					link.setAttribute('download', 'data.csv');
					link.innerText = 'Download';

					// Append the anchor element to the DOM
					document.body.appendChild(link);

					if (navigator.msSaveBlob) 
					{ // IE 10+
						navigator.msSaveBlob(blob, 'data.csv');
					}
					else
					{
						// Create a new URL object for the Blob object
						const url = window.URL.createObjectURL(blob);

						// Set the href property of the anchor element to the URL of the Blob object
						link.href = url;

						// Trigger a click event on the anchor element to download the file
						link.click();

						// Remove the anchor element from the DOM
						document.body.removeChild(link);

						// Release the URL object to free up memory
						window.URL.revokeObjectURL(url);
					}
					
				});
			}


			// Function to convert JSON data to CSV string
			function JSONtoCSV(json) {
			const header = Object.keys(json[0]).join(',') + '\n';
			const rows = json.map(obj => Object.values(obj).join(',') + '\n');
			return header + rows.join('');
			}



		</script>
		
	</main>
	<aside>
	
		
	</aside>

	
	</div>
	
	</body>
</html>