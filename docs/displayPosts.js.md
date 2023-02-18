# displayPosts.js

``displayPosts.js`` takes a JSON arrray of post properties and turns it into HTML of individual posts.

## displayPosts()

```JavaScript
displayPosts(JSON posts): //no return
```
This function displays and formats social media post data in the DOM.

### Parameters

**posts**

- A JSON encoded array of post data

### Return Values

No variables returned. Output is the post data displayed in the DOM.

### Example output

![Example of a post in the news feed](https://i.imgur.com/oygelOJ.png "Example of post")

Output from the script is pure HTML placed directly into the DOM, specifically 
one div per entry in the Post table, each containing all the info from said 
entry. Each field in a row of the table has it's own element generated 
dynamically in the DOM. For example, the Username is placed inside a \<h3>. 

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
