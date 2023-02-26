// C-like struct
function PostStats() {
	this.timeViewed = 0;
	this.maxTimeViewed = 0;
	this.timesViewed = 0;
	this.weightedScore = 0;

	this.currentViewTime = 0
	this.canBeViewed = false;
	this.inView = false;
	this.lastTime = 0;
}

class Analytics
{
	static #postsStats = {};

	static update()
	{
		const posts = document.getElementsByClassName("post");
		if (posts.length == 0) return;

		const visiblePosts       = getVisibleElements(posts, false);
		const visiblePostsStrict = getVisibleElements(posts, true);
		const centerPost         = getCentermostElement(posts);

		this.#updatePostStats(visiblePosts);
		this.#updateResponseText(visiblePosts, visiblePostsStrict, centerPost);
	}

	static #updatePostStats(visiblePosts)
	{
		for (const i in visiblePosts) {
			const post = visiblePosts[i];

			const postNotInPostStats = (typeof this.#postsStats[post.id] === "undefined");
			if (postNotInPostStats) this.#postsStats[post.id] = new PostStats();

			const postStats = this.#postsStats[post.id];

			const postInViewAgain = (!postStats.inView);
			if (postInViewAgain) {
				postStats.lastTime = performance.now();
				postStats.currentViewTime = 0;
				postStats.canBeViewed = true;
			}

			const secondsPassed = (performance.now() - postStats.lastTime)/1000;
			if (secondsPassed > 0) postStats.lastTime = performance.now();

			postStats.timeViewed += secondsPassed;
			postStats.currentViewTime += secondsPassed;

			if (postStats.maxTimeViewed < postStats.currentViewTime) postStats.maxTimeViewed = postStats.currentViewTime;

			if (postStats.currentViewTime >= 2 && postStats.canBeViewed) {
				postStats.timesViewed++;
				postStats.canBeViewed = false;
			}
		}

		for (const id in this.#postsStats) {
			const postStats = this.#postsStats[id];

			// below assumes that the post ID are stored in ascending order
			const topVisible = visiblePosts[0];
			const bottomVisible = visiblePosts[visiblePosts.length - 1];

			const postInView = (Number(topVisible.id) <= Number(id) && Number(id) <= Number(bottomVisible.id));

			postStats.inView = postInView;
		}
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

		const round = function(x) {
			return Math.floor(x * 10) / 10;
		}

		const postStats = this.#postsStats[post.id];
		const timesViewed = postStats.timesViewed;
		const timeViewed = round(postStats.timeViewed);
		const averageViewTime = round(timeViewed / timesViewed);
		const maxTimeViewed = round(postStats.maxTimeViewed);
		const currentViewTime = round(postStats.currentViewTime);
		return "ID: " + id + 
			"\nUsername: " + username +
			"\nTimes Viewed: " + timesViewed +
			"\nTime Viewed: " + timeViewed +
			"\nAverage time:" + averageViewTime +
			"\nMax time:" + maxTimeViewed +
			"\nCurrent time:" + currentViewTime +
			"\n";
	}
}
