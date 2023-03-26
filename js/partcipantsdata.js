function getProfileData() {
    // create a new XMLHttpRequest object
    var xhr = new XMLHttpRequest();
  
    // set the URL of the PHP script to retrieve profile data
    var url = "userprofile.php";
  
    // set the request method to POST
    xhr.open("POST", url, true);
  
    // set the Content-Type header to indicate we are sending form data
    xhr.setRequestHeader("ParticipantID", "GroupID", "application/x-www-form-urlencoded");
  
    // define a callback function to handle the server's response
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // response received, parse the JSON data
        var data = JSON.parse(this.responseText);
  
        // your code here to display the user's profile data
        var profileDiv = document.getElementById("userprofile");
        profileDiv.innerHTML = "ParicipantID: " + data.ParticipantID + "<br>" +
                            "username: " + data.Name + "<br>" +
                            "GroupID: " + data.GroupID + "<br>" +
                            "ProfilePicture: " + data.ProfilePic + "<br>" +
                            "bio: " + data.Bio;
      }
    };
  
  }