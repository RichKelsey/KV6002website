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
    <script src="../js/likeAdder.js"></script>
    <script>
        
        window.onload = function() {
            let tutorialButton = document.getElementById("tutorialButton");
            let quitButton = document.getElementById("quitButton");
            let tutorialBox = document.getElementById("tutorialBox");
            let closeTutorialButton = document.getElementById("closeTutorialButton");

            tutorialButton.addEventListener("click", function(){modalHandler(tutorialBox, closeTutorialButton)});

            quitButton.addEventListener("click", function(){endButtonHandler()});

            Analytics.loadStatistics();
            setInterval(function() {Analytics.storeStatistics()}, 200);
        }

        let likesAllowance, likesReceived, timerDuration, group = null;

        let likeAdderInstance = false;

        if(sessionStorage.getItem("participantID") === null){
            window.location.href = "signin.php";
        }

        participantID = (sessionStorage.getItem("participantID") !== null)? parseInt(sessionStorage.getItem("participantID")) : null;
        var query = "SELECT * FROM `Participant` WHERE `ParticipantID` = " + participantID;
        queryDB(query).then((post) => {
            displaySelfPost("postLayout", post, 0);
        });



        setInterval(function() {Analytics.update()}, 200);

        let checkforgroup = setInterval(function() {
            if(!Analytics.isReady()){
                console.log("not ready")
            }
            else{
                console.log("ready")
                group = Analytics.getGroup();
                timerDuration = parseInt(group.TimeAllowance);
                likesAllowance = group.LikesAllowance;
                likesReceived = group.LikesReceived;
                console.log("likesAllowance: " + likesAllowance);
                console.log("likesReceived: " + likesReceived);
                console.log("timerDuration: " + timerDuration);
                console.log("group: " + group.GroupID);

                var query = "SELECT * FROM Post WHERE GroupID = " + group.GroupID;
                queryDB(query).then((posts) => {
                    displayPosts("postLayout", posts);
                });
                clearInterval(checkforgroup);
            }
        }, 200);

        let updateLikeAllowance = setInterval(function(){
            if(!Analytics.isReady()){
                console.log("not ready")
            }
            else{
                group = Analytics.getGroup();
                likesAllowance = group.LikesAllowance;
                document.getElementById("likesCounter").innerHTML = likesAllowance;
            }
        }, 200);

        timerInterval = setInterval(function(){
            console.log("checking for timer");
            if(timerDuration != null){
                let timerText = document.querySelector("#timer");
                timerHandler(timerDuration, timerText);
                clearInterval(timerInterval);
            }
        }, 200);

        likeAdderInterval = setInterval(function(){
            console.log("checking for likeAdder");
            if(likesAllowance != null && likesReceived != null && likeAdderInstance == false){
                likeAdderInstance = true;
                let selfPost = document.getElementById("selfPost");
                let likeButton = selfPost.querySelector(".likeButton");
                let increment = (timerDuration/4) / likesReceived;
                likeAdder(likeButton, likesReceived, increment);
                clearInterval(likeAdderInterval);
            }
        }, 200);

        function toggleNightmode(){
            let body = document.querySelector("body");
            let sidebar = document.querySelector("#sidebar");

            if(body.classList.contains("nightmode")){
                body.classList.remove("nightmode");
                sidebar.classList.remove("nightmode");
                sidebar.style.backgroundColor = "#f1f1f1";
            }
            else{
                body.classList.add("nightmode");
                sidebar.classList.add("nightmode");
                sidebar.style.backgroundColor = "#2c2c2c";
            }
        }


    </script>

<div id="sidebar">
    <h1>Newsfeed</h1>
    <p id="timerLabel">Time remaining:</p>
    <p id="timer">No number given</p>
    <p id="likesLabel">Your remaining likes:</p>
    <p id="likesCounter">nan</p>
    <button id="tutorialButton">Tutorial</button>
    <button id="quitButton">Finish</button>

    <div id=switchDiv>
    <p>☀️</p>
    <label class="switch">
    <input type="checkbox" onchange="toggleNightmode()">
    <span class="slider round"></span>
    </label>
    <p>🌙</p>
    </div>
</label>

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
