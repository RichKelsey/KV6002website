function displayPosts(posts) {
  console.log(posts);

  posts.forEach(element => {
    //create post HTML elements
    //create div to hold all of individual post elements
    div = document.createElement("div");
    div.setAttribute("id", element.PostID);
    div.setAttribute("class", "post");
    document.getElementById("postLayout").appendChild(div);

    //create img to hold profile pic
    img = document.createElement("img");
    img.setAttribute("src", element.ProfilePic);
    img.setAttribute("class", "profilePic");
    document.getElementById(element.PostID).appendChild(img);

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
    likeButton.innerHTML = "Like 👍";
    document.getElementById(element.PostID).appendChild(likeButton);
  });

}