const ANALYTICS_STORAGE = "analyticsSession";
// C-like structs
function PostStats() {
	this.retentionTime = 0;
	this.maxTimeViewed = 0;
	this.timesViewed = 0;
	this.hasLiked = false;
	this.comment = "";

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
	static #participantID = null;
	static #group = null;

	static #ready = false;

	static isReady()
	{
		return this.#ready;
	}

	static update()
	{
		this.#init();
		if (!this.#ready) return;

		const posts = this.#getPosts();
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

	static getGroup()
	{
		return structuredClone(this.#group);
	}

	static deleteStatistics()
	{
		sessionStorage.removeItem(ANALYTICS_STORAGE);
	}

	static like(post)
	{
		const postStats = this.#postsStats[post.id];

		postStats.hasLiked = postStats.hasLiked? false : true;

		if (!this.#updateLikesAllowance(post)) return;

		this.#updatePostLikeButton(post);
	}

	static comment(post, comment = null, isEdit = false)
	{
		const postStats = this.#postsStats[post.id];
		if (!comment) comment = postStats.comment? postStats.comment : "";

		const postFooter = post.getElementsByClassName("postFooter")[0];

		if (postFooter.children.length > 2)
			postFooter.removeChild(postFooter.children[2]);

		if (isEdit) {
			const textArea = document.createElement("textarea");
			textArea.setAttribute("class", "commentBox");
			textArea.value = comment;
			postFooter.appendChild(textArea);

			textArea.focus();

			textArea.addEventListener("keydown", function (e) {
				if (e.key === "Enter") {
					postFooter.removeChild(textArea);
					Analytics.comment(post, textArea.value);
				}
			});
			return;
		} 

		const commentDiv = document.createElement("div");
		commentDiv.setAttribute("class", "comment");
		commentDiv.innerHTML = comment;
		postStats.comment = comment;
		postFooter.appendChild(commentDiv);
	}

	static interfaceDB(action, data = null)
	{
		const URL = "../php/db_analytics.php";

		if (action == "upload") {
			data = {
				participantID: this.#participantID,
				postStats: this.getStatistics()
			};
		};

		var action = {
			"action": action,
			"data": data
		};

		return fetch(URL, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
			},
			body: JSON.stringify(action),
		})
			.then((response) => {
				return response.text();
			})
			.then((data) => {
				const parentElementID = "responseText";
				if (document.getElementById(parentElementID)) {
					const responseH3 = createElementOnce(parentElementID, "h3", "responseH3");
					responseH3.innerText = "Response:";

					const responseDiv = createElementOnce(parentElementID, "div", "responseDiv");
					responseDiv.innerHTML = data;
				}
				
				const object = JSON.parse(data);
				if (object) return object;
				return null;
			})
			.catch((error) => {
				console.log('Error when parsing response:', error);
			});
	}

	static #initialized = false;
	static #init()
	{
		if (this.#initialized) return false;
		this.#initialized = true;
		this.#participantID = (sessionStorage.getItem("participantID") !== null)? parseInt(sessionStorage.getItem("participantID")) : 1;

		console.log("Analytics init, ParticipantID: " + this.#participantID);
		this.interfaceDB('getParticipantGroup', {participantID : this.#participantID}).then((group) => {
			this.#group = group;
			console.log("Analytics Group: " + this.#group.GroupID);
			if (this.#postsStats) {
				for (const i in this.#postsStats) {
					this.#group.LikesAllowance -= this.#postsStats[i].hasLiked;
				}
			}
			this.#ready = true;
		});
	}

	static #postElements = null;
	static #getPosts()
	{
		if (!this.#postElements) this.#postElements = document.getElementsByClassName("post");
		const posts = [];
		for (let i = 0; i < this.#postElements.length; i++) {
			const postElement = this.#postElements[i];
			if (postElement.id == "selfPost") continue;
			posts.push(postElement);
		}
		return posts;
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

			postStats.retentionTime += secondsPassed;
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

	static #updateLikesAllowance(post) {
		// still allow update of post button if there is no group info
		if (!this.#group) return true;
		const postStats = this.#postsStats[post.id];

		if (postStats.hasLiked && !this.#group.LikesAllowance) {
			postStats.hasLiked = false;
			return false;
		}

		this.#group.LikesAllowance += postStats.hasLiked? -1 : 1;

		return true;
	}

	static #updateResponseText(visiblePosts, visiblePostsStrict, centerPost)
	{
		const centerPostString = this.#getPostString(centerPost);

		const parentElementID = "responseText";
		if (!document.getElementById(parentElementID)) return;

		var visiblePostsString = "";
		for (let i = 0; i < visiblePosts.length; i++) {
			visiblePostsString += this.#getPostString(visiblePosts[i]);
		}

		var visiblePostsStrictString = "";
		for (let i = 0; i < visiblePostsStrict.length; i++) {
			visiblePostsStrictString += this.#getPostString(visiblePostsStrict[i]);
		}

		// DOM manipulation
		const centerPostH3 = createElementOnce(parentElementID, "h3", "centerPostH3");
		centerPostH3.innerText = "Center post:";

		const centerPostDiv = createElementOnce(parentElementID, "div", "centerPost");
		centerPostDiv.innerText = centerPostString;

		const visiblePostH3 = createElementOnce(parentElementID, "h3", "visiblePostH3");
		visiblePostH3.innerText = "Visible posts:";

		const visiblePostDiv = createElementOnce(parentElementID, "div", "visiblePostDiv");
		visiblePostDiv.innerText = visiblePostsString;

		const visiblePostStrictH3 = createElementOnce(parentElementID, "h3", "visiblePostStrictH3");
		visiblePostStrictH3.innerText = "Visible posts strict:";

		const visiblePostStrictDiv = createElementOnce(parentElementID, "div", "visiblePostStrictDiv");
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
		const retentionTime = round(postStats.retentionTime);
		const averageViewTime = round(retentionTime / timesViewed);
		const maxTimeViewed = round(postStats.maxTimeViewed);
		const currentViewTime = round(postState.currentViewTime);
		return "ID: " + id + 
			"\nUsername: " + username +
			"\nTimes Viewed: " + timesViewed +
			"\nTime Viewed: " + retentionTime +
			"\nAverage time: " + averageViewTime +
			"\nMax time: " + maxTimeViewed +
			"\nCurrent time: " + currentViewTime +
			"\n";
	}

	static #likeButtonClassAttribute = null;
	static #likeButtonTextParts = null;
	static #updatePostLikeButton(post)
	{
		const postStats = this.#postsStats[post.id];
		const likeButton = post.getElementsByClassName("likeButton")[0];

		if (!likeButton.getAttribute("onclick"))
			likeButton.setAttribute("onclick", "Analytics.like(document.getElementById(" + post.id + "))");

		if (!this.#likeButtonClassAttribute) 
			this.#likeButtonClassAttribute = likeButton.getAttribute("class");

		if (!this.#likeButtonTextParts)
			this.#likeButtonTextParts = likeButton.innerText.split(":");

		const text  = this.#likeButtonTextParts[0];
		const likes = parseInt(this.#likeButtonTextParts[1]);
		if (postStats.hasLiked) {
			likeButton.setAttribute("class", this.#likeButtonClassAttribute + " button-on");
			likeButton.innerText = text + ": " + (likes+1);
		}
		else {
			likeButton.setAttribute("class", this.#likeButtonClassAttribute);
			likeButton.innerText = text + ": " + likes;
		}

	}

	static #updatePostCommentButton(post)
	{
		const postStats = this.#postsStats[post.id];
		const commentButton = post.getElementsByClassName("commentButton")[0];

		if (!commentButton.getAttribute("onclick")) {
			commentButton.setAttribute("onclick", "Analytics.comment(document.getElementById(" + post.id + "), null, true)");
			if (postStats.comment) this.comment(post, postStats.comment);
		}
	}

	static #updatePostsDOM(posts)
	{
		for (let i = 0; i < posts.length; i++) {
			const post = posts[i];
			const postStats = this.#postsStats[post.id];
			if (!postStats) continue;

			this.#updatePostLikeButton(post);
			this.#updatePostCommentButton(post);
		}
	}
}
