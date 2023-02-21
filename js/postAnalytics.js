function checkPosts()
{
	const posts = document.getElementsByClassName("post");
	if (posts.length == 0) return;

	const visiblePosts       = getVisibleElements(posts);
	const visiblePostsStrict = getVisibleElements(posts, true);
	const centerPost         = getCentermostElement(posts);

	const centerPostString = getPostString(centerPost);

	var visiblePostsString = "";
	for (let i = 0; i < visiblePosts.length; i++) {
		visiblePostsString += getPostString(visiblePosts[i]);
	}

	var visiblePostsStrictString = "";
	for (let i = 0; i < visiblePostsStrict.length; i++) {
		visiblePostsStrictString += getPostString(visiblePostsStrict[i]);
	}

	// DOM manipulation
	const centerPostH3 = createElementOnce("responseText", "h3", "centerPostH3");
	centerPostH3.innerText = "Center post:";

	const centerPostDiv = createElementOnce("responseText", "div", "centerPost");
	centerPostDiv.innerText = centerPostString;

	const visiblePostH3 = createElementOnce("responseText", "h3", "visiblePostH3");
	visiblePostH3.innerText = "Visible posts:";

	const visiblePostDiv = createElementOnce("responseText", "div", "visiblePostDiv");
	visiblePostDiv.innerText = visiblePostsString;

	const visiblePostStrictH3 = createElementOnce("responseText", "h3", "visiblePostStrictH3");
	visiblePostStrictH3.innerText = "Visible posts strict:";

	const visiblePostStrictDiv = createElementOnce("responseText", "div", "visiblePostStrictDiv");
	visiblePostStrictDiv.innerText = visiblePostsStrictString;
}

function getPostString(post)
{
	const id = post.id;
	const username = post.getElementsByClassName("username")[0].innerText;
	return "ID: " + id + "\n Username: " + username + "\n";
}
