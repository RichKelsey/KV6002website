const URL = "../php/fetchPosts.php"; //set file path to php file

fetch(URL) //initiate fetch request
.then(function (response) {
    return response.json(); //return response as json
})
.then(function(data) {
    data.forEach(element => {

        //create post HTML elements
        //create div to hold all of individual post elements
        div = document.createElement("div");
        div.setAttribute("id", element.PostID);
        div.setAttribute("class", "post");
        document.getElementById("postLayout").appendChild(div);

        //create h3 to hold username
        h3 = document.createElement("h3");
        h3.setAttribute("class", "username");
        h3.innerHTML = element.Username;
        document.getElementById(element.PostID).appendChild(h3);

        //create p to hold post text
        p = document.createElement("p");
        p.setAttribute("class", "postText");
        p.innerHTML = element.Text;
        document.getElementById(element.PostID).appendChild(p);

        //create button to act as like button
        likeButton = document.createElement("button");
        likeButton.setAttribute("type", "button");
        likeButton.setAttribute("class", "likeButton");
        likeButton.innerHTML = "Like";
        document.getElementById(element.PostID).appendChild(likeButton);

    });
})
.catch(function (err) {
    console.log("Something went wrong!", err);
});