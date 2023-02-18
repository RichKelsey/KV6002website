<!DOCTYPE html>
<html lang="en">
<head>
    <!-- temp css -->
    <link rel="stylesheet" href="../css/newsfeed.css"> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Feed</title>
</head>
<body>
    <!-- include the js script for displaying posts -->
    <script src="../js/queryDB.js"></script>
    <script src="../js/displayPosts.js"></script>
    <script>
        var query = "SELECT * FROM Post";
        queryDB(query).then((posts) => {
            displayPosts(posts);
        });
    </script>
    <div id="postLayout"></div>
</body>
</html>