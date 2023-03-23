<?php
function generateUserID() {
  // generate a unique ID using the current timestamp and a random number
  $unique_id = time() . '-' . mt_rand(1000, 9999);

  // check if the ID already exists in the database
  // you can replace this with your own check against your database
  // or use a different method to ensure uniqueness
  while (idExistsInDatabase($unique_id)) {
    // if the ID already exists, generate a new one
    $unique_id = time() . '-' . mt_rand(1000, 9999);
  }

  // return the unique ID
  return $unique_id;
}
?>