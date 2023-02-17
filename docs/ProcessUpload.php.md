# ProcessUpload.php:

``ProcessUpload.php`` processes new posts from [admin.php][admin.php] where they are created, and uploads them to the database.

### Depends on:
- [admin.php][admin.php]

## Handling the optional upload of images:

``ProcessUpload.php`` checks:

- Is the uploaded file is an image
- Has this image already been uploaded before (if it already exists)
- **Prohibits** the upload of images that **exceed 5MB**
- **Only allows** the upload of images that are ``.jpg`` ``.png`` ``.jpeg`` ``.gif``

## Uploading to the database

### If an image was uploaded:

- ``Username`` is stored in database column ``Username``
- ``Group ID`` is stored in database column ``GroupID``
- ``Post`` refers to the text content of a post. Stored in database column ``Text``
- ``Upload Image (optional)`` is stored in database column ``Image`` **as** a **path** to the uploaded image
- ``Select an avatar`` is stored in database column ``ProfilePic`` **as** a **path** to the selected avatar

### If an image was not uploaded:

- ``Username`` is stored in database column ``Username``
- ``Group ID`` is stored in database column ``GroupID``
- ``Post`` refers to the text content of a post. Stored in database column ``Text``
- **``Upload Image (optional)``** is stored in database column ``Image`` **as "NoImg"** 
- ``Select an avatar`` is stored in database column ``ProfilePic`` **as** a **path** to the selected avatar

