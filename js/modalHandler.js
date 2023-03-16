function modalHandler(modalDiv, modalCloseBtn) {
    modalDiv.style.display = "block";
    modalCloseBtn.onclick = function() {
        modalDiv.style.display = "none";
    }
    window.onclick = function(event) {
        if (event.target == modalDiv) {
            modalDiv.style.display = "none";
        }
    }
}