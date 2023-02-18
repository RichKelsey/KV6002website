function displayPosts(parentElement, posts) {
  console.log(posts);

  posts.forEach(element => {
	  displayPost(parentElement, element);
	  //displayPostExperimental(parentElement, element);
  });
}

function displayPost(parentElement, post)
{
    //create post HTML elements
    //create div to hold all of individual post elements
    var div = document.createElement("div");
    div.setAttribute("id", post.PostID);
    div.setAttribute("class", "post");
    document.getElementById(parentElement).appendChild(div);

    //create img to hold profile pic
    var img = document.createElement("img");
    img.setAttribute("src", post.ProfilePic);
    img.setAttribute("class", "profilePic");
    div.appendChild(img);

    //create h3 to hold username
    var h3 = document.createElement("h3");
    h3.setAttribute("class", "username");
    h3.innerHTML = post.Username;
    div.appendChild(h3);

    //create p to hold post text
    var p = document.createElement("p");
    p.setAttribute("class", "postText");
    p.innerHTML = post.Text;
    div.appendChild(p);

    //create button to act as like button
    var likeButton = document.createElement("button");
    likeButton.setAttribute("type", "button");
    likeButton.setAttribute("class", "likeButton");
    likeButton.innerHTML = "Like 👍";
    div.appendChild(likeButton);
}

// alternate layout
function displayPostExperimental(parentElement, post)
{
	// indentation here also indicates the hierarchy of elements
	var div = document.createElement("div");
	div.setAttribute("id", post.PostID);
	div.setAttribute("class", "post experimental");
	document.getElementById(parentElement).appendChild(div);

		var postHead = document.createElement("div");
		postHead.setAttribute("class", "postHead");
		div.appendChild(postHead);

			var profilePic = document.createElement("img");
			profilePic.setAttribute("src", post.ProfilePic);
			postHead.appendChild(profilePic);

			var username = document.createElement("h3");
			username.setAttribute("class", "username");
			username.innerHTML = post.Username;
			postHead.appendChild(username);

		var postBody = document.createElement("div");
		postBody.setAttribute("class", "postBody");
		div.appendChild(postBody);

			if (post.Image != "NoImg") {
			var postImage = document.createElement("img");
			postImage.setAttribute("src", post.Image);
			postBody.appendChild(postImage);
			}

			var p = document.createElement("p");
			p.setAttribute("class", "postText");
			p.innerHTML = post.Text;
			postBody.appendChild(p);

			var likeButton = document.createElement("button");
			likeButton.setAttribute("type", "button");
			likeButton.setAttribute("class", "likeButton");
			likeButton.innerHTML = "Like 👍";
			postBody.appendChild(likeButton);

}
