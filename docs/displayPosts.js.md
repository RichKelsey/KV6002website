# displayPosts.js

``displayPosts.js`` uses [fetchPosts.php][fetchPosts.php] to fetch all the posts from the database.

[fetchPosts.php]: fetchPosts.php.md

### Depends on:
- [fetchPosts.php][fetchPosts.php]

## Example output

![Example of a post in the news feed](https://i.imgur.com/qIyYfH0.png "Example of post")

Output from the script is pure HTML placed directly into the DOM, specifically 
one div per entry in the Post table, each containing all the info from said 
entry. Each field in a row of the table has it's own element generated 
dynamically in the DOM. For example, the Username is placed inside a \<h3>. 

## CSS Identifiers

- Individual Posts': 
    - \<Div> 
    - ID: Dynamically named using that post's PostID from the database
    - Class: "post"
- Username:
    - \<h3>
    - Class: "username"
- Text body
    - \<p>
    - Class: "postText"
- Button
    - \<button>
    - Class: "likeButton"
