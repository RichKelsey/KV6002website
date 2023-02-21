function createElementOnce(parentElement, type, id)
{
	const existingElement = document.getElementById(id);
	if (existingElement) return existingElement;

	const parent = document.getElementById(parentElement);

	const element = document.createElement(type);
	element.setAttribute("id", id);

	if (parent) parent.appendChild(element);
	else console.log("No parent element to append " + type + " of ID: " + id);

	return element;
}

function elementCenterY(element)
{
	const bounds = element.getBoundingClientRect();
	return(bounds.top + bounds.bottom)/2;
}

function elementTopY(element)
{
	const bounds = element.getBoundingClientRect();
	return bounds.top;
}

function elementBottomY(element)
{
	const bounds = element.getBoundingClientRect();
	return bounds.bottom;
}

// Binary search
function getClosestElementIndex(elements, target, elementHeightFunction)
{
	var closest = Math.abs(target - elementHeightFunction(elements[0]));
	var closestIndex = 0;

	var lower = 0;
	var upper = elements.length-1;
	var middle = 0;

	while (lower <= upper) {
		middle = Math.floor((lower + upper)/2);
		const midElement = elements[middle];
		dy = target - elementHeightFunction(midElement);

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
	return closestIndex;
}

function getVisibleElements(elements, strict = false)
{
	const screenTop = window.screenTop;
	const screenBottom = screenTop + window.innerHeight;

	const topIndex    = getClosestElementIndex(elements, screenTop, elementTopY);
	const bottomIndex = getClosestElementIndex(elements, screenBottom, elementBottomY);

	const visibleElements = [];
	for (let i = topIndex; i <= bottomIndex; i++) {

		const obscured = (
			elementTopY(elements[i])    < screenTop ||
			elementBottomY(elements[i]) > screenBottom
		);
		if (obscured && strict) continue;

		visibleElements.push(elements[i]);
	}
	return visibleElements;
}

function getCentermostElement(elements)
{
	const windowCenterY = (window.screenTop + window.screenTop + window.innerHeight)/2;
	const index = getClosestElementIndex(elements, windowCenterY, elementCenterY);
	return elements[index];
}
