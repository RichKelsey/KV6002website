// C-like struct
function PostStats() {
	this.secondsViewed = 0;
	this.timesViewed = 0;
	this.weightedScore = 0;
	this.inView = false;
	this.lastTime = 0;
}

class Analytics
{
	static #postsStats = {};

	static checkPosts()
	{
		const posts = document.getElementsByClassName("post");
		if (posts.length == 0) return;

		const visiblePosts       = getVisibleElements(posts, false);
		const visiblePostsStrict = getVisibleElements(posts, true);
		const centerPost         = getCentermostElement(posts);

		for (const i in visiblePosts) {
			const post = visiblePosts[i];

			const postNotInPostStats = (typeof this.#postsStats[post.id] === "undefined");
			if (postNotInPostStats) this.#postsStats[post.id] = new PostStats();

			const postStat = this.#postsStats[post.id];

			const postInViewAgain = (!postStat.inView);
			if (postInViewAgain) {
				postStat.timesViewed++;
				postStat.lastTime = performance.now();
			}
		}

		for (const id in this.#postsStats) {
			const postStat = this.#postsStats[id];

			// below assumes that the post ID are stored in ascending order
			const topVisible = visiblePosts[0];
			const bottomVisible = visiblePosts[visiblePosts.length - 1];
			const postInView = (Number(topVisible.id) <= Number(id) && Number(id) <= Number(bottomVisible.id));

			if (postStat.inView) {
				postStat.secondsViewed += (performance.now() - postStat.lastTime)/1000;
				postStat.lastTime = performance.now();
			}

			postStat.inView = postInView;
		}

		this.#updateResponseText(visiblePosts, visiblePostsStrict, centerPost);
	}

	static #updateResponseText(visiblePosts, visiblePostsStrict, centerPost)
	{
		const centerPostString = this.#getPostString(centerPost);

		var visiblePostsString = "";
		for (let i = 0; i < visiblePosts.length; i++) {
			visiblePostsString += this.#getPostString(visiblePosts[i]);
		}

		var visiblePostsStrictString = "";
		for (let i = 0; i < visiblePostsStrict.length; i++) {
			visiblePostsStrictString += this.#getPostString(visiblePostsStrict[i]);
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

	static #getPostString(post)
	{
		const id = post.id;
		const username = post.getElementsByClassName("username")[0].innerText;

		const postStats = this.#postsStats[post.id];
		const timesViewed = postStats.timesViewed;
		const secondsViewed = Math.floor(postStats.secondsViewed*10)/10;
		return "ID: " + id + "\nUsername: " + username + "\nTimesViewed: " + timesViewed + "\nSecondsViewed: " + secondsViewed + "\n";
	}
}
