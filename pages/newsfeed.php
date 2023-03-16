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
    <script src="../js/modalHandler.js"></script>
    <script>
        
        window.onload = function() {
            let tutorialButton = document.getElementById("tutorialButton");
            let quitButton = document.getElementById("quitButton");
            let tutorialBox = document.getElementById("tutorialBox");
            let closeTutorialButton = document.getElementById("closeTutorialButton");

            tutorialButton.addEventListener("click", function(){modalHandler(tutorialBox, closeTutorialButton)});

            quitButton.addEventListener("click", function(){endButtonHandler()});
        }

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
    <button id="quitButton">Quit</button>
</div>

<div id="postLayout">

</div>

<div id="tutorialBox">
    <div class="tutorialContent">
        <h1>Tutorial</h1>
        <p>Scroll the news feed to view others' posts.</p>
        <p>Click like 👍 to add a like to a post.</p>
        <p>Click comment 💬 to add a comment to a post. Pressing the Enter key will submit your comment.</p>
        <p>The experience will end when the timer runs out or when you press the quit button.</p>
        <p>After the experience you will be directed to a short survey.</p>
        <p>Press the close button to close this window.</p>
        <button id="closeTutorialButton">Close</button>
    </div>
</body>
</html>
