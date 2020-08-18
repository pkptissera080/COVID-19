<?php
include 'LocationModel.php';
?>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyA-AB-9XZd-iQby-bNLYPFyb0pR2Qw3orw">
</script>

<script>
    /**
     * Create new map
     */
    var infowindow;
    var map;
    var red_icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
    var purple_icon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
    var locations = <?php get_myall_locations() ?>;
    var myOptions = {
        zoom: 8,
        center: new google.maps.LatLng(7.752484, 80.729071),
        mapTypeId: 'roadmap'
    };
    map = new google.maps.Map(document.getElementById('map'), myOptions);

    /**
     * Global marker object that holds all markers.
     * @type {Object.<string, google.maps.LatLng>}
     */
    var markers = {};

    /**
     * Concatenates given lat and lng with an underscore and returns it.
     * This id will be used as a key of marker to cache the marker in markers object.
     * @param {!number} lat Latitude.
     * @param {!number} lng Longitude.
     * @return {string} Concatenated marker id.
     */
    var getMarkerUniqueId = function(lat, lng) {
        return lat + '_' + lng;
    };

    /**
     * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
     * This function can be useful for getting new coordinates quickly.
     * @param {!number} lat Latitude.
     * @param {!number} lng Longitude.
     * @return {google.maps.LatLng} An instance of google.maps.LatLng object
     */
    var getLatLng = function(lat, lng) {
        return new google.maps.LatLng(lat, lng);
    };

    /**
     * Binds click event to given map and invokes a callback that appends a new marker to clicked location.
     */
    var addMarker = google.maps.event.addListener(map, 'click', function(e) {
        var lat = e.latLng.lat(); // lat of clicked point
        var lng = e.latLng.lng(); // lng of clicked point
        var markerId = getMarkerUniqueId(lat, lng); // an that will be used to cache this marker in markers object.
        var marker = new google.maps.Marker({
            position: getLatLng(lat, lng),
            map: map,
            animation: google.maps.Animation.DROP,
            id: 'marker_' + markerId,
            html: "<div id='info_" + markerId + "''>\n" +
                "  <form method='post' enctype='multipart/form-data'>\n" +
                "  <table class=\"map1 w3-padding\">\n" +
                "    <tr>\n" +
                "      <td>\n" +
                "          Subject : \n" +
                "      </td>\n" +
                "      <td>\n" +
                "        <input type='text' class='w3-input' name='subject' placeholder='subject' required minlength='5'>\n" +
                "      </td>\n" +
                "    </tr>\n" +
                "    <tr>\n" +
                "      <td>\n" +
                "          PCR report : \n" +
                "      </td>\n" +
                "      <td>\n" +
                "          <input class='w3-input' type='file' name='pcrRptImg' required accept='.png, .jpg, .jpeg,'/>\n" +
                "      </td>\n" +
                "    </tr>\n" +
                "    <tr>\n" +
                "      <td>\n" +
                "          Description : \n" +
                "      </td>\n" +
                "      <td>\n" +
                "        <textarea  class='manual_description w3-input' name='Description' placeholder='Description'  required minlength='10'></textarea>\n" +
                "      </td>\n" +
                "    </tr>\n" +
                "    <tr>\n" +
                "    <td></td>\n" +
                "      <td><input type='submit'class='w3-button w3-green' name='uploadReport' value='Upload Report'/></td>\n" +
                "    </tr>\n" +
                "    <tr>\n" +
                "      <td><input type='text' name='lat' value='" + lat + "' style='display:none'></td>\n" +
                "      <td><input type='text' name='lng' value='" + lng + "' style='display:none'></td>\n" +
                "    </tr>\n" +
                "  </table>\n" +
                "  </form>\n" +
                "</div>"
        });
        markers[markerId] = marker; // cache marker in markers object
        bindMarkerEvents(marker); // bind right click event to marker
        bindMarkerinfo(marker); // bind infowindow with click event to marker
    });

    /**
     * Binds  click event to given marker and invokes a callback function that will remove the marker from map.
     * @param {!google.maps.Marker} marker A google.maps.Marker instance that the handler will binded.
     */
    var bindMarkerinfo = function(marker) {
        google.maps.event.addListener(marker, "click", function(point) {
            var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
            var marker = markers[markerId]; // find marker
            infowindow = new google.maps.InfoWindow();
            infowindow.setContent(marker.html);
            infowindow.open(map, marker);
            // removeMarker(marker, markerId); // remove it
        });
    };

    /**
     * Binds right click event to given marker and invokes a callback function that will remove the marker from map.
     * @param {!google.maps.Marker} marker A google.maps.Marker instance that the handler will binded.
     */
    var bindMarkerEvents = function(marker) {
        google.maps.event.addListener(marker, "rightclick", function(point) {
            var markerId = getMarkerUniqueId(point.latLng.lat(), point.latLng.lng()); // get marker id by using clicked point's coordinate
            var marker = markers[markerId]; // find marker
            removeMarker(marker, markerId); // remove it
        });
    };

    /**
     * Removes given marker from map.
     * @param {!google.maps.Marker} marker A google.maps.Marker instance that will be removed.
     * @param {!string} markerId Id of marker.
     */
    var removeMarker = function(marker, markerId) {
        marker.setMap(null); // set markers setMap to null to remove it from map
        delete markers[markerId]; // delete marker instance from markers object
    };


    /**
     * loop through (Mysql) dynamic locations to add markers to map.
     */
    var i;
    var confirmed = 0;
    
    for (i = 0; i < locations.length; i++) {
        var imgdir = "'../res/pcrimg/"+locations[i][5]+"'";
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map,
            icon: locations[i][6] === '1' ? red_icon : purple_icon,
            html: '<div>\n' +
                '<table class=\'map1 w3-table\'>\n' +
                '<tr>\n' +
                '<td><img class="w3-border" src="../res/pcrimg/' + locations[i][5] + '" style="height: 50px;width:50px;cursor: pointer;" alt="report" onclick="viewImg('+imgdir+')"></td>\n' +
                '<td><b>' + locations[i][3] + '</b><p>&#9679; ' + locations[i][4] + '</p></td></tr>\n' +
                '</table>\n' +
                '</div>'
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infowindow = new google.maps.InfoWindow();
                confirmed = locations[i][4] === '1' ? 'checked' : 0;
                $("#confirmed").prop(confirmed, locations[i][4]);
                $("#id").val(locations[i][0]);
                $("#description").val(locations[i][3]);
                $("#form").show();
                infowindow.setContent(marker.html);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    /**
     * SAVE save marker from map.
     * @param lat  A latitude of marker.
     * @param lng A longitude of marker.
     */
    function saveData(lat, lng) {
        var description = document.getElementById('manual_description').value;
        var url = '../controller/LocationModel.php?add_location&description=' + description + '&lat=' + lat + '&lng=' + lng;
        downloadUrl(url, function(data, responseCode) {
            if (responseCode === 200 && data.length > 1) {
                var markerId = getMarkerUniqueId(lat, lng); // get marker id by using clicked point's coordinate
                var manual_marker = markers[markerId]; // find marker
                manual_marker.setIcon(purple_icon);
                infowindow.close();
                infowindow.setContent("<div style=' color: purple; font-size: 25px;'> Waiting for admin confirm!!</div>");
                infowindow.open(map, manual_marker);

            } else {
                console.log(responseCode);
                console.log(data);
                infowindow.setContent("<div style='color: red; font-size: 25px;'>Inserting Errors</div>");
            }
        });
    }

    function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
            if (request.readyState == 4) {
                callback(request.responseText, request.status);
            }
        };

        request.open('GET', url, true);
        request.send(null);
    }
</script>