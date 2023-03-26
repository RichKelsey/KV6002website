[admin.php]: ../pages/admin.php
[credentials.php]: ../php/credentials.php
[displayPosts.js]: ../js/displayPosts.js
[queryDB.js]: ../js/queryDB.js
# [admin.php][admin.php]:
``admin.php`` Is the admin interface for creating new posts and viewing as well as downloading statistics.

### Depends on:
- [credentials.php][credentials.php] Authorization for connecting to the database
- [displayPosts.js][displayPosts.js] Displaying posts
- [queryDB.js][queryDB.js] Talking to the database

## Creating a new post

1. Pressing the ``New Post`` button reveals an interface for creating a new post.
2. Select an avatar. **First one is selected by default**
3. Create the username. This is the username participants will see when they see this post.
4. Select a group ID from the list of existing test groups.
    * **Optional** Create a new group
        1. Press ``Create a new Group``
        2. Set the ``LikeAllowance`` (how many likes can the user give out)
        3. Set the ``TimeAllowance`` (how much time can the user have to complete survey)
        4. Set the ``LikesReceived`` (how many likes the user's post will receive)
        5. Press ``Submit``
5. Set how many likes this post will have
6. **Optional** Upload an image the post will display
7. Write the text of the post
8. Press ``Submit``

## Update Survey URL

* Press the button ``Update Survey URL`` once you have pasted a link in the text window bellow.

## Survey Statistics:
* This window display how many completions of the survey have been done by each of the test groups individually.
* This window **does not** update in real-time and requires refreshing the page to see if more have been completed.

## Download Statistics:
* Hovering over this button reveals all the test groups.
* Pressing any group will download a ``.csv`` sheet of the statistics for that particular group
### The statistics consist of:
* ``PostID`` - the ID for which that row of statistics is for.
* ``Likes`` - how many likes that post has in total from this group.
* ``Retention`` - the total retention time of this group on the post.
* ``Views`` - how many people saw (liked or not) this post.

* The view count allows for calculation of an average retention time for a group on a post, as well as the average like count.

## Download Statistics:
* ``Download Statistics`` download all of the activity of every user in every group
### The statistics consist of:
* ``Group ID``
* ``Participant ID``
* ``Name`` of the participant
* ``Post ID`` with which that participant interacted
* ``ProfilePic`` that the user chose
* ``Bio`` of that user
* ``HasLiked`` boolean of whether the user liked that post or not
* ``RetentionTime`` total amount of that post was viewed by that user
* ``MaxTimeViewed`` longest time the post was viewed by that user in a single viewing
* ``TimesViewed`` how many times the post was scrolled onto by that user
* ``Comment`` the comment (if any) that user left on the post

## Display Feeds:
* Hovering over ``Display Feeds`` reveals all the test groups
* Pressing on one of the groups shows a newsfeed, which is very similar to what a user from that group would see in the survey
### Editing Existing Posts:
* Pressing on avatar of a post displays a window where a new avatar can be chosen. **This MUST be done if a post a being edited, even if the avatar is supposed to remain the same. In this case, select the same avatar.**
* Pressing on the ``username``, ``text of the post``, ``like count number`` allows for changing those values.
* After the post was edited **And an avatar was selected** pressing submit updates the post to the new values.
* Pressing ``Delete This Post`` will permanently delete that post.

## Send link to survey:
* The survey link will end with ``/pages/signin.php`` 
* Before sending to link, you need to modify the link ``/pages/signin.php?GroupID=x`` ``WHERE x is the group to which you want to assign participants who click the link``
* **The Group ID number 'x' must exist in the database. The group must be created to be assigned in a link.**
