function checkPosts()
{
	const posts = document.getElementsByClassName("post");
	if (posts.length == 0) return;

	const post = getCentermostElement(posts);
	const id = post.id;
	const username = post.getElementsByClassName("username")[0].innerText;

	const string = "Closest ID: " + id + "\n Username: " + username + "\n";

	document.getElementById("responseText").innerText = string;
}

function getElementCenterY(element)
{
	const bounds = element.getBoundingClientRect();
	return(bounds.top + bounds.bottom)/2;
}

// Binary searh to get nearest.
function getCentermostElement(elements)
{
	const windowCenterY = (window.screenTop + window.screenTop + window.innerHeight)/2;
	var closest = Math.abs(windowCenterY - getElementCenterY(elements[0]));
	var closestIndex = 0;

	var lower = 0;
	var upper = elements.length-1;
	var middle = 0;

	while (lower <= upper) {
		middle = Math.floor((lower + upper)/2);
		const midElement = elements[middle];
		dy = windowCenterY - getElementCenterY(midElement);

		if (dy > 0) {
			lower = middle + 1;
		}
		else {
			upper = middle - 1;
		}

		const distance = Math.abs(dy);
		if (distance < closest) {
			closest = distance;
			closestIndex = middle;
		}
	}

	return elements[closestIndex];
}
