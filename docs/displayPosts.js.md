# displayPosts.js

``displayPosts.js`` functions take a parent HTML element ID and a JSON
formatted data of post properties. It turns the JSON into HTML of individual
posts, nesting them into the parent element.

## displayPosts()

```JavaScript
displayPosts(str parentElement, JSON posts): //no return
```
This function takes a JSON array of post data, and calls
[displayPost()](#displayPost) per post entry in the array.

### Parameters

**parentElement**

- The ID of the parent element to nest posts into.

**posts**

- A JSON encoded array of post data

### Return Values

No variables returned. Output is the post data displayed in the DOM.

## displayPost()

```JavaScript
displayPost(str parentElement, JSON post): //no return
```
This function displays and formats social media post data in the DOM.

### Parameters

**parentElement**

- The ID of the parent element to nest a post into.

**post**

- JSON encoded post data

### Return Values

No variables returned. Output is the post data displayed in the DOM.

### Example output

![Example of a post in the news feed](https://i.imgur.com/oygelOJ.png "Example of post")

Output from the script is pure HTML placed directly into the DOM, specifically 
one div per entry in the Post table, each containing all the info from said 
entry. Each field in a row of the table has it's own element generated 
dynamically in the DOM. For example, the Username is placed inside a \<h3>. 

## displayPostExperimental()

```JavaScript
displayPostExperimental(str parentElement, JSON post): //no return
```
This function displays and formats social media post data in the DOM with an
experimental structure.

Each post element is also attributed with the ``experimental`` class.

This should be a drop-in replacement for [displayPost()](#displayPost).

### Parameters

**parentElement**

- The ID of the parent element to nest a post into.

**post**

- JSON encoded post data

### Return Values

No variables returned. Output is the post data displayed in the DOM.

## CSS Identifiers

- Individual Posts': 
    - \<Div> 
    - ID: Dynamically named using that post's PostID from the database
    - Class: "post"
- Profile Picture
    - \<img>
    - Class: "profilePic"
- Username:
    - \<h3>
    - Class: "username"
- Text body
    - \<p>
    - Class: "postText"
- Button
    - \<button>
    - Class: "likeButton"
