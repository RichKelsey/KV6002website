const URL = "../php/db_connection.php"; //set file path to php file

function queryDB(query) {

  var sql = {"query": query}; //data to be sent to db

  //sent post request to db connection file with query as data
  return fetch(URL, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(sql),
    })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      console.log('Success:', data);
      return data;
    })
    .catch((error) => {
    console.error('Error:', error);
    });


}