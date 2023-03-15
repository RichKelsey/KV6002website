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
    <script src="../js/dom.js"></script>
    <script src="../js/postAnalytics.js"></script>
    <script src="../js/timer.js"></script>
    <script src="../js/endHandler.js"></script>
    <script>

        var query = "SELECT * FROM `Participant` WHERE `ParticipantID` = 1";
        queryDB(query).then((post) => {
            displaySelfPost("postLayout", post, 100);
        });

        var query = "SELECT * FROM Post";
        queryDB(query).then((posts) => {
            displayPosts("postLayout", posts);
        });
        setInterval(function() {Analytics.update()}, 200);
    </script>

<div id="sidebar">
    <h1>Newsfeed</h1>
    <p id="timerLabel">Time remaining:</p>
    <p id="timer"></p>
    <button id="tutorialButton">Tutorial</button>
    <button id="quitButton" onclick="endButtonHandler()">Quit</button>
</div>

<div id="postLayout">
</body>
</html>
