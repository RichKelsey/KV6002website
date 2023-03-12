<!doctype html>
<html lang="en">
	<head>
		<!-- temp css -->
		<link rel="stylesheet" href="../css/admin.css"> 
		<link rel="stylesheet" href="../css/newsfeed.css"> 
		<meta charset="utf-8">
		<title> Administrator Dashboard </title>
	</head>

	
	<main >


		<!-- dummy iframe is to not redirect the user away from the dashboard when they submit the form -->
		<iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>

		<!-- Button reveals Create Post interface -->
		<button onclick="PostInterface()">New Post</button>
		<br><br>
		<div>
			<button class="dropbtn" onclick="UpdateURL()" style="width: 180px;">Update Survey URL</button><br>
			<input type="text" id="URL" name="URL" style="width: 170px;">
		</div>

		<?php 



			$directory="../img/avatars/";
			$files = array();

			//to select the first avatar by default
			$tmp = 0;

			//connect to the database
			require_once("../php/db_connection.php");
			$db = db_connect();

			// Check if connection successful
			if (!$db) die("Can't connect to database");


			echo'
			<!-- Post creation form -->

			<div class="CreatePost" id="CreatePost" style="display:none">
				<form action="../php/ProcessUpload.php" method="post" enctype="multipart/form-data" target="dummyframe" onsubmit="return validateForm()">';
					
				echo"<br><label for='avatar[]'>Select an avatar:<br><br>";
				echo"<div class='avatars'>";
				//display all available avatars
				foreach (scandir($directory) as $file) 
				{
					if ($file !== '.' && $file !== '..') 
					{
						$files[] = $file;
						
						echo"<div class='avatarcontainer'>";
						
						echo"<label for='avatar'> <img src='../img/avatars/$file'/> </label>";
						
						//to select the first avatar by default
						if($tmp == 0)
						{
							echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='selectOnlyOne(this)' checked >";
							echo"</div>";
						}
						else
						{
							echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='selectOnlyOne(this)'>";
							echo"</div>";
						}
						
						$tmp +=1;
						
						
					}
				}
				echo"</label><br><br>";
				echo"</div>";


				echo'
					<br>
					<div class="fields">
						<label for="Name">Username:</label>
						<input type="text" id="Name" name="Name" required>

						<label for="Group">Group ID:</label>
						<select id="Group" name="Group" onchange="AddGroup()">';

						$query = "SELECT GroupID FROM `Group`";
	
						$json_result = db_query($query, $db);
						$json_decoded = json_decode($json_result, TRUE);
		
						foreach($json_decoded as $key => $value)
						{
							echo "<option value='$key'> Group $key </option>";
						}
						echo'
						<option value="http://localhost:8000/pages/AddGroup.html"> Create a new Group</option>
						</select>

						<br><br>
						<label for="LikeCount">Number of Likes</label>
						<input type="number" min="0" step="1" inputmode="numeric" id="LikeCount" name="LikeCount" style="width: 60px;"required>
						<br><br><br>

						<label for="file" style="cursor: pointer;">Upload Image (optional)</label>
						<input class="imageUpload" type="file"  accept="image/*" name="Image" id="Image"  onchange="loadFile(event)"><br>
						<img id="output" width="200" /><br><br>

						<label for="Post">Post:</label>
						<textarea class="text" type="text" id="Text" name="Text" required> </textarea><br><br>

						<input type="submit" value="Submit" id="Submit" onclick="setTimeout(DisplayOutput,500)">
					</div>
				</form>
			</div>
			<!-- This is where the output from uploading the image is displayed -->

			<div id="OutputFromForm">
				<p></p>
			</div>';
		


			//display download buttons
			echo'
			<aside>

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

				<div class="dropdown">
					<button class="dropbtn">Display Feeds</button>
					<div class="dropdown-content">';
	
					//display all available groups
					$query = "SELECT GroupID FROM `Group`";
	
					$json_result = db_query($query, $db);
					$json_decoded = json_decode($json_result, TRUE);
	
					foreach($json_decoded as $key => $value)
					{
						echo "<button onclick='DisplayFeed($key)'> Group $key </button>";
					}
	
				echo'
					</div>
				</div>

			</aside>';

			echo'
			<div class="DisplayStats" id="DisplayStats">
			

			</div>';




			//image selection for editing posts
			echo"<div class='avatarsEdit', id='avatarsEdit'>";
			//display all available avatars
			foreach (scandir($directory) as $file) 
			{
				if ($file !== '.' && $file !== '..') 
				{
					$files[] = $file;
					
					echo"<div class='avatarcontainer'>";
					
					echo"<label for='avatar'> <img src='../img/avatars/$file'/> </label>";
					
					//to select the first avatar by default
					if($tmp == 0)
					{
						echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='AvatarSelection(\"$file\")' checked >";
						echo"</div>";
					}
					else
					{
						echo"<input type='checkbox' id='avatar' name='avatar[]' value='{$file}' onclick='AvatarSelection(\"$file\")'>";
						echo"</div>";
					}
					
					$tmp +=1;
					
					
				}
			}
			echo"</div>";
		?>
		
		
		<script src="https://code.jquery.com/jquery-3.6.3.min.js" 
		integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" 
		crossorigin="anonymous"></script>
		<script src="../js/queryDB.js"></script>
    	<script src="../js/displayPosts.js"></script>

		<script>

			let isDisplayed = 0;
			DisplayStats();
			
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

			//display uploaded image
			var loadFile = function(event) 
			{
				var image = document.getElementById('output');
				image.src = window.URL.createObjectURL(event.target.files[0]);
			}

			//display success of errors of creating a post
			function DisplayOutput()
			{
				$("#OutputFromForm").load("../php/ProcessingOutput.txt");
			}

			function DisplayStats()
			{
				var div = document.getElementById('DisplayStats');

				var query = "SELECT GroupID, COUNT(DISTINCT(A.ParticipantID)) " +
							"FROM Analytics A " +
							"JOIN Participant B " +
							"ON A.ParticipantID = B.ParticipantID " +
							"GROUP BY GroupID";


				queryDB(query).then((returnedJSON) => 
				{
					var result ="<h3> The Survey Statistics: </h3> ";
					for(var key in returnedJSON)
					{
						result += "<h4>Group " + returnedJSON[key]['GroupID'] + ":</h4> <p>" + returnedJSON[key]['COUNT(DISTINCT(A.ParticipantID))'] + " Completions </p><br>";
					
					}
					div.innerHTML =result;
				});
			}

			//Allow only one checkbox to be selected
			function selectOnlyOne(checkbox) 
			{
				var checkboxes = document.getElementsByName('avatar[]');
				checkboxes.forEach((item) => 
				{
					if (item !== checkbox) item.checked = false;
				});
			}

			//Check if an avatar has been picked
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
				//requesting results of a query asynchronously

				//query for statistics
				var queryAll = "SELECT A.PostID, SUM(HasLiked) AS Likes, SUM(RetentionTime) AS Retention, COUNT(A.PostID) AS Views " +
							"FROM Analytics A " + 
							"JOIN Post B " + 
							"ON A.PostID = B.PostID " +
							"AND B.GroupID =" + GroupID +
							" GROUP BY PostID";
	
				QueryForDownload(queryAll);
			}

			function DownloadIndividual()
			{
				//query for actions of individuals
				var queryIndividual = "SELECT GroupID, A.ParticipantID, Name, PostID, ProfilePic, Bio, HasLiked, RetentionTime, MaxTimeViewed, TimesViewed, Comment " +
									"FROM Participant A " +
									"JOIN Analytics B " +
									"ON A.ParticipantID = B.ParticipantID";

				QueryForDownload(queryIndividual);		
			}

			function QueryForDownload(query)
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
			function JSONtoCSV(json) 
			{
				const header = Object.keys(json[0]).join(',') + '\n';
				const rows = json.map(obj => Object.values(obj).join(',') + '\n');
				return header + rows.join('');
			}

			// display feeds
			function DisplayFeed(GroupID)
			{
				var query = "SELECT * FROM Post WHERE GroupID =" + GroupID;

				//delete any previously selected feed to only display one selected feed at a time
				var postLayout = document.getElementById("postLayout");
				postLayout.innerHTML = "";

				queryDB(query).then((posts) => {
					displayPosts("postLayout", posts, admin=true);
				});
			}

			function AddGroup()
			{
				var selectElement = document.getElementById("Group");
				var selectedOption = selectElement.value;
				if(selectedOption == "http://localhost:8000/pages/AddGroup.html")
					{
						window.location.href = selectedOption;
					}

			}
			//avatar is a relative path to the new selected avatar
			var avatar;
			function AvatarSelection(avatarName)
			{
				//setting the relative path to the new avatar
				avatar = "../img/avatars/"+avatarName;

				//animate a popup selection of avatars
				var popup = document.getElementById("avatarsEdit");

				// Set the transition properties
				popup.style.transition = "transform 0.3s ease-out, opacity 0.3s ease-out";
				popup.style.transform = "scale(0.5)";
				popup.style.opacity = "0";
				
				// Wait for the transition to complete before hiding the popup
				setTimeout(function() {
					popup.style.display = "none";
				}, 300);


			}

			function EditPost(post, postID)
			{
				//getting the new values from the post
				var usernameElement = post.querySelector('.username');
  				var postTextElement = post.querySelector('.postText');
				var imgElement = post.querySelector('.img');
				var likeCountElement = post.querySelector('.likes');


				//retrieving only the text content of them. Excluding html
				usernameElement = usernameElement.textContent;
				postTextElement = postTextElement.textContent;
				likeCountElement = likeCountElement.textContent;

				//this is the case
				if(avatar !== null)
				{
					var query = "UPDATE Post " +
								"SET Username='" + usernameElement + "', " +
								"Text='" + postTextElement + "', " +
								"LikeCount='" + likeCountElement + "', " +
								"ProfilePic='" + avatar + "' " +
								"WHERE PostID=" + postID;

					
					queryDB(query).then((json) => 
					{
						window.alert("Post Updated. Refresh the page");
					});
				}
			}

			function DeletePost(postID)
			{
				var query = "DELETE FROM Post " +
							"WHERE PostID =" + postID;

				queryDB(query).then((json) => 
				{
					window.alert("Post Deleted");
				});
						
			}

			function UpdateURL()
			{
				var URL = document.getElementById("URL").value;
				var query = "UPDATE URL SET URL='" + URL + "'";
				queryDB(query).then((json) => 
				{
					window.alert("URL Updated with. " + URL);
				});
			}




		</script>
		
	</main>

    <div id="postLayout"></div>
	
		
	
	</body>
</html>