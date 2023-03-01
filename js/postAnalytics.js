const ANALYTICS_STORAGE = "analyticsSession";
// C-like structs
function PostStats() {
	this.timeViewed = 0;
	this.maxTimeViewed = 0;
	this.timesViewed = 0;
	this.liked = false;

	this.state = null;
}
// Runtime post state
function PostState() {
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

		this.#updatePostsDOM(posts);
		this.#updatePostStats(visiblePosts);
		this.#updateResponseText(visiblePosts, visiblePostsStrict, centerPost);
	}

	static storeStatistics()
	{
		// deep copy and discard runtime post state before storage
		const postsStats = structuredClone(this.#postsStats);
		for (const id in postsStats) {
			postsStats[id].state = null;
		}

		const string = JSON.stringify(postsStats);
		console.log(string);
		sessionStorage.setItem(ANALYTICS_STORAGE, string);
	}

	static loadStatistics()
	{
		const obj = this.getStatistics();
		if (!obj) {
			console.log("Analytics storge not found.");
			return;
		}

		this.#postsStats = obj;
		for (const id in this.#postsStats) {
			this.#postsStats[id].state = new PostState();
		}
	}

	static getStatistics()
	{
		const string = sessionStorage.getItem(ANALYTICS_STORAGE);
		if (!string) return null;

		return JSON.parse(string);
	}

	static deleteStatistics()
	{
		sessionStorage.removeItem(ANALYTICS_STORAGE);
	}

	static like(post)
	{
		const postStats = this.#postsStats[post.id];

		postStats.liked = postStats.liked? false : true;

		this.#updatePostLikeButton(post);
	}

	static #updatePostStats(visiblePosts)
	{
		for (const i in visiblePosts) {
			const post = visiblePosts[i];

			const postNotInPostStats = (typeof this.#postsStats[post.id] === "undefined");
			if (postNotInPostStats) {
				this.#postsStats[post.id] = new PostStats();
				this.#postsStats[post.id].state = new PostState();
			}

			const postStats = this.#postsStats[post.id];
			const postState = postStats.state;

			const postInViewAgain = (!postState.inView);
			if (postInViewAgain) {
				postState.lastTime = performance.now();
				postState.currentViewTime = 0;
				postState.canBeViewed = true;
			}

			const secondsPassed = (performance.now() - postState.lastTime)/1000;
			if (secondsPassed > 0) postState.lastTime = performance.now();

			postStats.timeViewed += secondsPassed;
			postState.currentViewTime += secondsPassed;

			const newMaximumViewTime = postStats.maxTimeViewed < postState.currentViewTime;
			if (newMaximumViewTime) postStats.maxTimeViewed = postState.currentViewTime;

			const postNotYetViewed = (postState.currentViewTime >= 2 && postState.canBeViewed);
			if (postNotYetViewed) {
				postStats.timesViewed++;
				postState.canBeViewed = false;
			}
		}

		for (const id in this.#postsStats) {
			const postStats = this.#postsStats[id];
			const postState = postStats.state;

			// below assumes that the post ID are stored in ascending order
			const topVisible = visiblePosts[0];
			const bottomVisible = visiblePosts[visiblePosts.length - 1];

			const postInView = (Number(topVisible.id) <= Number(id) && Number(id) <= Number(bottomVisible.id));

			postState.inView = postInView;
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
		const postState = postStats.state;

		const timesViewed = postStats.timesViewed;
		const timeViewed = round(postStats.timeViewed);
		const averageViewTime = round(timeViewed / timesViewed);
		const maxTimeViewed = round(postStats.maxTimeViewed);
		const currentViewTime = round(postState.currentViewTime);
		return "ID: " + id + 
			"\nUsername: " + username +
			"\nTimes Viewed: " + timesViewed +
			"\nTime Viewed: " + timeViewed +
			"\nAverage time: " + averageViewTime +
			"\nMax time: " + maxTimeViewed +
			"\nCurrent time: " + currentViewTime +
			"\n";
	}

	static #likeButtonClassAttribute = null;
	static #updatePostLikeButton(post)
	{
		const postStats = this.#postsStats[post.id];
		const likeButton = post.getElementsByClassName("likeButton")[0];

		if (!this.#likeButtonClassAttribute) 
			this.#likeButtonClassAttribute = likeButton.getAttribute("class");

		if (postStats.liked)
			likeButton.setAttribute("class", this.#likeButtonClassAttribute + " button-on");
		else
			likeButton.setAttribute("class", this.#likeButtonClassAttribute);
	}

	static #updatePostsDOM(posts)
	{
		for (let i = 0; i < posts.length; i++) {
			const post = posts[i];
			const postStats = this.#postsStats[post.id];
			if (!postStats) continue;

			this.#updatePostLikeButton(post);
		}
	}
}
