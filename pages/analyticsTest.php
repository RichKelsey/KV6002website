<html>
<head>
	<meta charset="UTF-8">
	<title>Home</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="">

	<link rel="stylesheet" href="../css/newsfeed.css">
</head>

<body class="experimental">
	<script src="../js/queryDB.js"></script>
	<script src="../js/displayPosts.js"></script>
	<script src="../js/dom.js"></script>
	<script src="../js/postAnalytics.js"></script>
	<script>
		var query = "SELECT * FROM Post";
		queryDB(query).then((posts) => {
			displayPosts("posts", posts);
		});
		Analytics.loadStatistics();
		setInterval(function() {Analytics.update()}, 200);

		function goToTestUpload() {
			Analytics.storeStatistics();
			location.assign("analyticsTestupload.html");
		}

		function deleteLocal() {
			Analytics.deleteStatistics();
			location.reload();
		}

	</script>

	<div id="topContent">
		<h1>Analytics test page</h1>

		<button onclick="goToTestUpload()">Upload statistics</button>
		<button onclick="deleteLocal()">Delete statistics</button>
		<div id="responseText"></div>
	</div>

	<div id="bottomContent">
	<div id="posts" class="experimental"></div>
	</div>
</body>
</html>
