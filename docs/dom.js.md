# dom.js

``dom.js`` is a collection of utilities to manipulate or query the DOM.


## createElementOnce

```JavaScript
createElementOnce(str parentElement, str type, str id): element
```

A wrapper for the DOM ``createElement()`` function. It creates a new element
within ``parentElement`` if ``id`` doesn't already exist, otherwise it returns
a DOM element of ``id``.

### Parameters

**parentElement**

- A string containing the ID of the parentElement.

**type**

- A string for the HTML element type, e.g. ``h1`` or ``div``.

**id**

- A string for the ID of the HTML element to be found, or otherwise created.

### Return Values

A newly created, or existing HTML element is returned.

## elementCenterY

```JavaScript
elementCenterY(element element): //no return
```

Gets the center height of the HTML element.

### Parameters

**element**

- A HTML element to get the center height for.

## Return Values

No variables returned. Output is the post data displayed in the DOM.

### Return Values

A newly created, or existing HTML element is returned.

## elementTopY

```JavaScript
elementTopY(element element): //no return
```

Gets the top height of the HTML element.

### Parameters

**element**

- A HTML element to get the top most bound for.

## Return Values

No variables returned. Output is the post data displayed in the DOM.

## elementBottomY

```JavaScript
elementBottomY(element element): //no return
```

Gets the center height of the HTML element.

### Parameters

**element**

- A HTML element to get the bottom most bound for.

## Return Values

No variables returned. Output is the post data displayed in the DOM.

## getClosestElementIndex

```JavaScript
getClosestElementIndex(
	HTMLCollection elements,
	float target,
	function elementHeightFunction(element element): float
): //no return
```

Get the index of the closest HTML element from ``elements`` to the ``target``
height, based on an ``elementHeightFunction()``.

This is to be used in conjunction with the functions ``elementCenterY()``,
``elementTopY()`` or ``elementBottomY()`` as the ``elementHeightFunction()``
parameter, depending on the desired behaviour.

Using ``elementCenterY()`` would find the closest element based on the
elements' center height.

Using ``elementTopY()`` would find the closest element based on the
elements' top height.

Using ``elementBottomY()`` would find the closest element based on the
elements' bottom height.

### Parameters

**elements**

- An HTML collection of elements sorted by height.

**target**

- The target height value as a float to find the closet element to.

**function elementHeightFunction(element element): float**

- A function returning a height value of element.

## Return Values

This function returns the index of the closet element found in ``elements``.

## Example usage

The ``getCentermostElement()`` function is a concise example of how to use this
function.

```JavaScript
function getCentermostElement(elements)
{
	const windowCenterY = (window.screenTop + window.screenTop + window.innerHeight)/2;
	const index = getClosestElementIndex(elements, windowCenterY, elementCenterY);
	return elements[index];
}
```

The ``getVisibleElements()`` function also contains a few lines that use this
function to get the indices of the top and bottom most post.

```JavaScript
const screenTop = window.screenTop;
const screenBottom = screenTop + window.innerHeight;

const topIndex    = getClosestElementIndex(elements, screenTop, elementTopY);
const bottomIndex = getClosestElementIndex(elements, screenBottom, elementBottomY);
```

## getVisibleElements

```JavaScript
function getVisibleElements(HTMLCollection elements, bool strict = false): array
```

This function returns an array **(not ``HTMLCollection``)** of HTML elements in
``elements`` that are deemed visible on the screen, based on the height values
of the elements.

If the parameter ``strict`` is true, only elements that are fully in view
vertically, and not partially occluded are returned.

### Parameters

**elements**

- An HTML collection of elements sorted by height.

**strict**

- Boolean to determine where to use strict filtering of visible elements.
  Defaults to false.

### Return Values

This function returns an array of HTML elements.

## getCentermostElement

```JavaScript
function getCentermostElement(HTMLCollection elements): element
```

This get the element that is closest vertically to the center of the screen,
from ``elements``.

### Parameters

**elements**

- An HTML collection of elements sorted by height.

### Return Values

This returns an HTML element.
