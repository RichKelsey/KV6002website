# displayPosts.js

``displayPosts.js`` uses [db_connection.php][db_connection.php] to fetch all the posts from the database. It does so by sending a POST request to the file. For more information on how the request is handled see 
[db_connection POST request][db_connection.php/POST].

[db_connection.php]: db_connection.php.md
[db_connection.php/POST]: db_connection.php.md#on-post-request

### Depends on:
- [db_connection.php][db_connection.php]

## Example output

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
