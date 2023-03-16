function timerHandler(duration, textElement) {
    let startDelta, minutes, seconds;
    let currentTime = Date.now();

    function updateTimer() {
        startDelta = duration - Math.floor((Date.now() - currentTime) / 1000);
        minutes = Math.floor(startDelta / 60);
        seconds = startDelta % 60;

        if (startDelta <= 0) {
            textElement.innerHTML = "00:00";
            return;
        }

        textElement.innerHTML = (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;
    }

    updateTimer();
    if(textElement.innerHTML == "00:00"){
        redirect();
    }
    setInterval(updateTimer, 1000);
}

