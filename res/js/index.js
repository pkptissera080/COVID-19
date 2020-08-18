// Accordion 
function myAccFunc() {
    var x = document.getElementById("demoAcc");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}

// Open and close sidebar
function w3_open() {
    document.getElementById("mySidebar").style.display = "block";
    document.getElementById("myOverlay").style.display = "block";
}

function w3_close() {
    document.getElementById("mySidebar").style.display = "none";
    document.getElementById("myOverlay").style.display = "none";
}


// Get the <span> element that closes the modal
var span = document.getElementById("myimgclose");
var myimgModal = document.getElementById("myimgModal");
var modalImg = document.getElementById("img01view");

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    myimgModal.style.display = "none";
}

function viewImg(xx) {
    myimgModal.style.display = "block";
    modalImg.src = xx;
}

// Get the modal
var myeditModal = document.getElementById("myeditModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");


// When the user clicks the button, open the modal 
function openEditor(id, lat, lng, subject, description, pcrRptImg) {
    myeditModal.style.display = "block";
    document.getElementById('vrptid').innerHTML = id;
    document.getElementById('drptid').value = id;
    document.getElementById('dsubject').value = subject;
    document.getElementById('ddescription').value = description;
    document.getElementById('dpcrrptimg').src = '../res/pcrimg/' + pcrRptImg;
    document.getElementById('dkeepimg').value = pcrRptImg;
    document.getElementById('dlat').value = lat;
    document.getElementById('dlng').value = lng;
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == myeditModal) {
        myeditModal.style.display = "none";
    }
}


function togglePcrDiv(state) {
    if (state == 'new') {
        document.getElementById('keepold').style.display = 'none';
        document.getElementById('picknew').style.display = '';
        document.getElementById('dimgstate').value = 1;
    } else if (state == 'keep') {
        document.getElementById('keepold').style.display = '';
        document.getElementById('picknew').style.display = 'none';
        document.getElementById('dimgstate').value = 0;
    } else {

    }
}

function enlargeimg() {
    var imgUlr = document.getElementById('dpcrrptimg').src;
    viewImg(imgUlr);
}

function enlargeimgdgmap() {
    var imgUlr = document.getElementById('imgdgmap').src;
    viewImg(imgUlr);
}

var val_con_count = document.getElementById("con_count").innerHTML;
var val_uncon_count = document.getElementById("uncon_count").innerHTML;
document.getElementById("all_count").innerHTML = parseInt(val_con_count) + parseInt(val_uncon_count);


function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(showPosition);
    } else {
        document.getElementById("vlat").value = "Geolocation is not supported by this browser.";
        document.getElementById("vlng").value = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    document.getElementById("vlat").value = position.coords.latitude;
    document.getElementById("vlng").value = position.coords.longitude;
}