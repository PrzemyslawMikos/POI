// Ajax

function showHowManyPoints(action, element) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById(element).innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", action, true);
    xmlhttp.send();
}

// Google Maps

var lon;
var lat;
var address;
var markers = [];
var added = 0;

function setLocation(latitude, longitude, address){
    this.lat = latitude;
    this.lon = longitude;
    this.title = address
}

function initMap() {
    var myLatLng = {lat: this.lat, lng: this.lon};
    var mapDiv = document.getElementById('map');
    var map = new google.maps.Map(mapDiv, {
        center: myLatLng,
        zoom: 16
    });
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: this.address
    });
}