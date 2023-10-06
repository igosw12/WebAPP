var modal = document.getElementById("myModal");
var modalButton = document.getElementById("modal-button");
var close = document.getElementsByClassName("close")[0];

modalButton.onclick = function() {
    modal.style.display = "block";
}

close.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

modalButton.onclick = function() {
    modal.style.display = "block";
    document.body.classList.add("modal-open");
}

close.onclick = function() {
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.body.classList.remove("modal-open");
    }
}