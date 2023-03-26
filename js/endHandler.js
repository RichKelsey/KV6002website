function redirect(){
    Analytics.storeStatistics();
    Analytics.interfaceDB("upload");
    window.location.replace("endscreen.php");
}

function endButtonHandler() {
    console.log(Analytics.getStatistics());
    if(confirm("Are you sure you want to end? You will not be able to return here after leaving the page!")){
        console.log("Ending");
        redirect();
    }
}