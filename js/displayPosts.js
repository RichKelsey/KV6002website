function displayPosts(parentElement, posts, admin=false) {
  console.log(posts);

  posts.forEach(element => {
	  //displayPost(parentElement, element);
	  displayPostExperimental(parentElement, element, admin);
  });
}

function displaySelfPost(parentElement, post, likesReceived){

	post = post[0];

	var div = document.createElement("div");
	div.setAttribute("id", "selfPost");
	div.setAttribute("class", "post experimental");
	document.getElementById(parentElement).appendChild(div);
  
	// create the post header element
	var postHead = document.createElement("div");
	postHead.setAttribute("class", "postHead");
	div.appendChild(postHead);

	// add the profile picture
	var profilePic = document.createElement("img");
	profilePic.setAttribute("src", post.ProfilePic);
	profilePic.setAttribute("class", "profilePic");			
	postHead.appendChild(profilePic);

	// add the username
	var username = document.createElement("h3");
	username.setAttribute("class", "username");
	username.innerHTML = post.Name;
	postHead.appendChild(username);
			
	// create the post body element
	var postBody = document.createElement("div");
	postBody.setAttribute("class", "postBody");
	div.appendChild(postBody);

	// create the post text element
	var postText = document.createElement("p");
	postText.setAttribute("class", "postText");
	postText.innerHTML = post.Bio;
	postBody.appendChild(postText);

	// create the post footer element
	var postFooter = document.createElement("div");
	postFooter.setAttribute("class", "postFooter");
	div.appendChild(postFooter);

	var likeButton = document.createElement("button");
	likeButton.setAttribute("type", "button");
	likeButton.setAttribute("class", "likeButton");
	likeButton.innerHTML = "Like 👍: " + likesReceived;
	postFooter.appendChild(likeButton);

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
    likeButton.innerHTML = "Like 👍: " + post.LikeCount + " ";
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
			profilePic.setAttribute("class", "profilePic");			
			//add event listeners if admin
			if(admin)
			{
				//const container = document.querySelector('#avatarsEdit');
				const popup = document.getElementById("avatarsEdit");

				profilePic.addEventListener("click", function(event)
				{
					var y = event.target.offsetTop;
					
					// Set the position of the popup
					popup.style.top = y +"px";

					// Display the popup
					popup.style.display = "block";

					// Animate the popup
					popup.style.transform = "scale(0)";
					popup.style.opacity = "0";
					popup.style.transition = "transform 0.3s ease-out, opacity 0.3s ease-out";
					setTimeout(function() {
						popup.style.transform = "scale(1)";
						popup.style.opacity = "1";
					}, 400);

				});
			}
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
				likeButton.innerHTML = "Like 👍: " + post.LikeCount;
				postFooter.appendChild(likeButton);
			
				var commentButton = document.createElement("button");
				commentButton.setAttribute("type", "button");
				commentButton.setAttribute("class", "commentButton");
				commentButton.innerHTML = "Comment 💬";
				postFooter.appendChild(commentButton);
			}
			// add the submit button, if an admin
			else 
			{
				var label = document.createElement("p");
				label.innerHTML ="Like Count 👍: ";
				postFooter.appendChild(label);


				var likes = document.createElement("p");
				likes.setAttribute("class", "likes");
				likes.innerHTML = post.LikeCount;
				postFooter.appendChild(likes);

				likes.addEventListener("click", function() 
				{

					// create an input field
					var input = document.createElement("input");
					input.setAttribute("type", "number");
					input.setAttribute("min", "0");
					input.setAttribute("step", "1");
					input.setAttribute("class", "postText");
					input.value = likes.innerHTML;
					postFooter.replaceChild(input, likes);
			
					// add event listener to the input field to detect when it loses focus
					input.addEventListener("blur", function() 
					{
					// replace the input field with a new paragraph element containing the edited text
					likes.innerHTML = input.value;
					postFooter.replaceChild(likes, input);
					});
				});

				//submit button sends the function "EditPost" the whole post and the ID of the post
				var submitButton = document.createElement("button");
				submitButton.setAttribute("type", "button");
				submitButton.setAttribute("class", "commentButton");
				submitButton.setAttribute("onclick", "EditPost(document.getElementById(" + post.PostID + "), " + post.PostID + ")");
				submitButton.innerHTML = "Submit";
				postFooter.appendChild(submitButton);

				var deleteButton = document.createElement("button");
				deleteButton.setAttribute("type", "button");
				deleteButton.setAttribute("class", "commentButton");
				deleteButton.setAttribute("onclick", "DeletePost(" + post.PostID + ")");
				deleteButton.innerHTML = "Delete This Post";
				postFooter.appendChild(deleteButton);
			}
  }
