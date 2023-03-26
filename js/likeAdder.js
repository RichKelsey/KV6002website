function likeAdder(likeText, likeMax, increment){
    setTimeout(function(){
        currentLikes = likeText.innerHTML.split(":")[1];
        if(currentLikes < likeMax){
            likeText.innerHTML = "Like 👍: " + (parseInt(currentLikes) + 1);
            likeAdder(likeText, likeMax, increment);
        }
}, Math.round(Math.random() + increment * 1000) + 1000);
}