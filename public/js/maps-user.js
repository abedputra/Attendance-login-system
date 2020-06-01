var map;
var markers = {};

var initLat = -7.5520179;
var initLongt = 110.8181248;

function initialize() {

    var latParam = getParamUrl('lat');
    var longtParam = getParamUrl('longt');
    var type = getParamUrl('type');

    if (latParam !== undefined && longtParam !== undefined){
        initMap(latParam, longtParam);
        addMarker(latParam, longtParam, 'param', type);
    }else{
        initMap(initLat, initLongt);
    }

    // Add marker from database
    getStoreLoc();
}

function initMap(initLat, initLongt){
    var myLatlng = new google.maps.LatLng(initLat, initLongt);
    var mapOptions = {
        zoom: 16,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    //Getting map DOM element
    var mapElement = document.getElementById('map-canvas');

    map = new google.maps.Map(mapElement, mapOptions);
}

function getStoreLoc() {
    $.ajax({
        url: baseURL + "api/data/showAllDataLocation",
        type: "get",
        success: function (response) {
            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                    addMarker(response[i].lat, response[i].longt, 'get', '')
                }
            } else {
                checkLocation = "No Data";
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function addMarker(lat, lng, status, type){
    var markerId = getMarkerUniqueId(lat, lng);
    if(status != 'param'){
        var marker = new google.maps.Marker({
            position: getLatLng(lat, lng),
            map: map,
            id: markerId,
            icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });

        markers[markerId] = marker; // cache marker in markers object
    }else {
        if (type == 'in') {
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                id: markerId,
                icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            });
        } else {
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                id: markerId,
                icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            });
        }

        markers[markerId] = marker; // cache marker in markers object
    }
}

function getMarkerUniqueId(lat, lng) {
    return lat + '_' + lng;
}

function getLatLng(lat, lng) {
    return new google.maps.LatLng(lat, lng);
}

function getParamUrl(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return decodeURIComponent(sParameterName[1]);
        }
    }
}

google.maps.event.addDomListener(window, 'load', initialize);