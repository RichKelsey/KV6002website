function redirect(){
    Analytics.storeStatistics();
    Analytics.interfaceDB("upload");
    window.location.replace("../endscreen.html");
}

function endButtonHandler() {
    let text;
    if(confirm("Are you sure you want to end? You will not be able to return here after leaving the page!")){
        redirect();
    }
}