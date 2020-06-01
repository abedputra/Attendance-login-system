var map;
var markers = {};
var idsMarkerArr = [];
var intervalTime;

var initLat = -7.5520179;
var initLongt = 110.8181248;

var infowindow = new google.maps.InfoWindow();
var bounds = new google.maps.LatLngBounds();
var titleInfoWindow;

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

    google.maps.event.addListener(map, 'click', function(e) {
        var lat = e.latLng.lat(); // lat of clicked point
        var lng = e.latLng.lng(); // lng of clicked point
        addMarker(lat, lng, 'add', '');
    });

    // Add marker from database
    getStoreLoc();

    $('#search-location').click(function () {
        getWorkerLocation();
    });
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

function saveStoreLoc(lat, longt, title) {
    var data = {
        'lat' : lat,
        'longt' : longt,
        'title' : title,
    };

    $.ajax({
        url: baseURL + "api/data/storeLocation",
        data: data,
        type: "post"
    });
}

function deleteStoreLoc(id) {
    var data = {
        'id_marker' : id,
    };

    $.ajax({
        url: baseURL + "api/data/deleteLocationTable",
        data: data,
        type: "post"
    });
}

function getStoreLoc() {
    $.ajax({
        url: baseURL + "api/data/showAllDataLocation",
        type: "get",
        success: function (response) {
            if (response.length > 0) {
                for (var i = 0; i < response.length; i++) {
                    addMarker(response[i].lat, response[i].longt, '', 'get', response[i].title)
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

function getWorkerLocation() {

    Swal.fire({
        title: 'Searching...',
        text: "Please wait, we search your worker location...",
        icon: 'info',
        showCancelButton: false,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Cancel',
        backdrop:true,
        allowOutsideClick: false,
    }).then((result) => {
        if (result.value) {
            if(idsMarkerArr.length > 0){
                for (var i = 0; i < idsMarkerArr.length; i++) {
                    var markerId = idsMarkerArr[i];
                    var marker = markers[markerId]; // find marker
                    removeMarker(marker, markerId); // remove it
                }
            }
            stopIntervalTime();
        }
    });

    intervalTime = setInterval(function(){
        $.ajax({
            url: baseURL + "api/data/showAllDataWorkerLocation",
            type: "get",
            success: function (response) {

                if (response.length > 0) {
                    // Stop search the location
                    stopIntervalTime();
                    Swal.close();

                    var workerLat;
                    var workerLongt;

                    for (var i = 0; i < response.length; i++) {
                        workerLat = response[i].lat;
                        workerLongt = response[i].longt;
                        idsMarkerArr.push(response[i].lat + '_' + response[i].longt);
                        addMarker(response[i].lat, response[i].longt, 'param', 'worker', response[i].name);
                        bounds.extend(getLatLng(response[i].lat, response[i].longt));
                    }

                    map.fitBounds(bounds);
                    map.setCenter(bounds.getCenter());

                    if(map.getZoom() > 15){
                        map.setZoom(18);
                    }

                    // Move camera
                    // moveToLocation(workerLat,workerLongt);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    }, 1000);
}

function stopIntervalTime(){
    clearInterval(intervalTime);
}

function addMarker(lat, lng, status, type, nameWorker = '') {
    var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
    if (status != 'param') {
        var marker = new google.maps.Marker({
            position: getLatLng(lat, lng),
            map: map,
            id: markerId,
            icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
        });

        markers[markerId] = marker; // cache marker in markers object
        bindMarkerEvents(marker); // bind right click event to marker

        google.maps.event.addListener(marker, 'mouseover', function() {
            infowindow.setContent('<b>' + nameWorker + '</b>');
            infowindow.open(map, marker);
        });
    } else {
        if (type == 'in') {
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                id: markerId,
                icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
            });
        } else if(type == 'out') {
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                id: markerId,
                icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
            });
        }else{
            var marker = new google.maps.Marker({
                position: getLatLng(lat, lng),
                map: map,
                id: markerId,
                icon: 'https://maps.google.com/mapfiles/ms/icons/arrow.png',
            });

            google.maps.event.addListener(marker, 'mouseover', function() {
                infowindow.setContent('<b>' + nameWorker + '</b>');
                infowindow.open(map, marker);
            });
        }

        markers[markerId] = marker; // cache marker in markers object
    }

    if (status == 'add') {
        addInfoWindow(marker, lat, lng);
    }

    idsMarkerArr.push(marker.id);
}

function addInfoWindow(marker, lat, lng) {
    (async () => {

        const { value: text } = await Swal.fire({
            input: 'text',
            inputPlaceholder: 'Type your message here...',
            inputAttributes: {
                'aria-label': 'Type your message here'
            },
            showCancelButton: true
        });


        if (text) {
            titleInfoWindow = (text);

            google.maps.event.addListener(marker, 'mouseover', function() {
                infowindow.setContent('<b>' + titleInfoWindow + '</b>');
                infowindow.open(map, marker);
            });

            saveStoreLoc(lat, lng, titleInfoWindow);
        }

    })()
}

function getMarkerUniqueId(lat, lng) {
    return lat + '_' + lng;
}

function getLatLng(lat, lng) {
    return new google.maps.LatLng(lat, lng);
}

function bindMarkerEvents(marker) {
    google.maps.event.addListener(marker, "rightclick", function (point) {
        var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
        var marker = markers[markerId]; // find marker
        deleteStoreLoc(markerId);
        removeMarker(marker, markerId); // remove it
    });
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

function removeMarker(marker, markerId) {
    marker.setMap(null); // set markers setMap to null to remove it from map
    delete markers[markerId]; // delete marker instance from markers object
}

google.maps.event.addDomListener(window, 'load', initialize);