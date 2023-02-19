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
	<script src="../js/postAnalytics.js"></script>
	<script>
        var query = "SELECT * FROM Post";
        queryDB(query).then((posts) => {
            displayPosts("posts", posts);
		    checkPosts();
        });
	</script>

	<div id="topContent">
		<h1>Analytics test page</h1>

		<div id="responseText"></div>
	</div>

	<div id="bottomContent">
	<div id="posts" class="experimental" onscroll="checkPosts()"></div>
	</div>
</body>
</html>
