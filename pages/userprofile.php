* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  
  h1 {
    font-size: 90px;
    font-weight: 700px;
    background-image: linear-gradient(to left, #553c9a, #b393d3);
    color: transparent;
    background-clip: text;
    -webkit-background-clip: text;
  }
  
  button:hover{
    background-color: rgb(110, 101, 101);
  }
  
  .button-on {
    background-color: rgb(138, 159, 195);
  border-radius: 14px;
  }
  
  body {
    background-color: #c1c9d0;
    font-style: normal;
    transform-style: flat;
    text-align: center;
    text-size-adjust: 25px;
    font-family: "Oswald", Helvetica, Arial, sans-serif;
    font-weight: 300;
    text-transform:uppercase;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction:column;
    justify-content: center;
    align-items: center;
    font-variant-position: normal;
    
  }
  
  html, body, .main {
  height: 100%;
  }
  
  .avatars {
    display: flex;
    flex-wrap: wrap;
    width: 60px;
    border: 1px solid black;
  }
  
  
  #bottomContent {
  display: flex;
  justify-content: center;
  width: 100%;
  height: 100vh;
  background: #05011d;
  }
  
  #bio{
  width: 1000px;
  overflow: auto;
  resize: auto; 
  height: 100px;
  }
  
  .text{
    line-height: 2;
    overflow-wrap: break-word;
    letter-spacing: 0px;
    tab-size: 1;
    text-align: left;
    text-decoration: overline wavy;
    text-indent: 9px;
    white-space: pre-wrap;
  }
  
  #username{
    width: 50px;
    overflow: auto;
    resize: auto; 
    height: 45px
  }
  
  button{
    background-color: rgb(78, 78, 78);
    border: 1px solid rgb(148, 148, 148);
    border-radius: 7px;
    padding: 5px;
    transition: 0.3s;
    color: white;
    margin: 0px 1em 0px 0px;
    width: 100px;
    height: 40px;
  }
  
  button:hover{
    background-color: rgb(107, 107, 107);
  }
  
  .button-on {
    background-color: rgb(78, 107, 78);
  border-radius: 14px;
  }
