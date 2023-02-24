function displayPosts(parentElement, posts, admin=false) {
  console.log(posts);

  posts.forEach(element => {
	  //displayPost(parentElement, element);
	  displayPostExperimental(parentElement, element, admin);
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
function displayPostExperimental(parentElement, post, admin)
{
  	// indentation here also indicates the hierarchy of elements
	var div = document.createElement("div");
	div.setAttribute("id", post.PostID);
	div.setAttribute("class", "post experimental");
	document.getElementById(parentElement).appendChild(div);
  
		// create the post header element
		var postHead = document.createElement("div");
		postHead.setAttribute("class", "postHead");
		div.appendChild(postHead);
  
			// add the profile picture
			var profilePic = document.createElement("img");
			profilePic.setAttribute("src", post.ProfilePic);
			postHead.appendChild(profilePic);
  
			// add the username
			var username = document.createElement("h3");
			username.setAttribute("class", "username");
			username.innerHTML = post.Username;
			postHead.appendChild(username);

			// add event listener to the post text field
			username.addEventListener("click", function() 
			{
			if (admin) 
			{
				// create an input field
				var input = document.createElement("textarea");
				input.setAttribute("type", "text");
				input.setAttribute("class", "username");
				input.value = username.innerHTML;
				postHead.replaceChild(input, username);
		
				// add event listener to the input field to detect when it loses focus
				input.addEventListener("blur", function() 
				{
				// replace the input field with a new paragraph element containing the edited text
				username.innerHTML = input.value;
				postHead.replaceChild(username, input);
				});
			}
			});
			  
  
		// create the post body element
		var postBody = document.createElement("div");
		postBody.setAttribute("class", "postBody");
		div.appendChild(postBody);
  
			// add the post image, if available
			if (post.Image) {
			var postImage = document.createElement("img");
			postImage.setAttribute("src", post.Image);
			postBody.appendChild(postImage);
			}
	
			// create the post text element
			var postText = document.createElement("p");
			postText.setAttribute("class", "postText");
			postText.innerHTML = post.Text;
			postBody.appendChild(postText);
  
			// add event listener to the post text field
			postText.addEventListener("click", function() 
			{
			if (admin) 
			{
				// create an input field
				var input = document.createElement("textarea");
				input.setAttribute("type", "text");
				input.setAttribute("class", "postText");
				input.value = postText.innerHTML;
				postBody.replaceChild(input, postText);
		
				// add event listener to the input field to detect when it loses focus
				input.addEventListener("blur", function() 
				{
				// replace the input field with a new paragraph element containing the edited text
				postText.innerHTML = input.value;
				postBody.replaceChild(postText, input);
				});
			}
			});
  
		// create the post footer element
		var postFooter = document.createElement("div");
		postFooter.setAttribute("class", "postFooter");
		div.appendChild(postFooter);
  
			// add the like button and comment button, if not an admin
			if (!admin) 
			{
				var likeButton = document.createElement("button");
				likeButton.setAttribute("type", "button");
				likeButton.setAttribute("class", "likeButton");
				likeButton.innerHTML = "Like 👍";
				postFooter.appendChild(likeButton);
			
				var commentButton = document.createElement("button");
				commentButton.setAttribute("type", "button");
				commentButton.setAttribute("class", "commentButton");
				commentButton.setAttribute("onclick", "comment(document.getElementById(" + post.PostID + "))");
				commentButton.innerHTML = "Comment 💬";
				postFooter.appendChild(commentButton);
			}
			// add the submit button, if an admin
			else 
			{
				var submitButton = document.createElement("button");
				submitButton.setAttribute("type", "button");
				submitButton.setAttribute("class", "commentButton");
				submitButton.setAttribute("onclick", "EditPost(document.getElementById(" + post.PostID + "))");
				submitButton.innerHTML = "Submit";
				postFooter.appendChild(submitButton);
			}
  }




function comment(ID) {
	if (!(ID.children[2].children.length > 2)) {
		textArea = document.createElement("textarea");
		textArea.setAttribute("class", "commentBox");
		ID.children[2].appendChild(textArea);
		textArea.focus();
		textArea.addEventListener("keydown", function (e) {
			if (e.key === "Enter") {
				var comment = textArea.value;
				var postID = ID.children[0].innerHTML;
				var query = `INSERT INTO Analytics (PostID, ParticipantID, Comment) VALUES(1,1,'${comment}') ON DUPLICATE KEY UPDATE Comment = '${comment}';`;
				console.log(query);
				queryDB(query);
				ID.children[2].removeChild(textArea);
				var commentDiv = document.createElement("div");
				commentDiv.setAttribute("class", "comment");
				commentDiv.innerHTML = comment;
				ID.children[2].appendChild(commentDiv);
			}
		});
	} 
}
