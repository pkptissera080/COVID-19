<?php
include 'PublicLocationModel.php';
?>


<div id="map"></div>

<!------ Include the above in your HEAD tag ---------->
<script>
    var map;
    var marker;
    var infowindow;
    var red_icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
    var purple_icon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
    var locations = <?php get_allConfirmed_locations() ?>;

    function initMap() {
        var Srilanka = {
            lat: 7.752484,
            lng: 80.729071
        };
        infowindow = new google.maps.InfoWindow();
        map = new google.maps.Map(document.getElementById('map'), {
            center: Srilanka,
            zoom: 8
        });


        var i;
        var confirmed = 0;
        for (i = 0; i < locations.length; i++) {

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                map: map,
                icon: locations[i][6] === '1' ? red_icon : purple_icon,
                html: document.getElementById('form')
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    $("#imgdgmap").attr('src','res/pcrimg/'+locations[i][5]);
                    $("#mvsubject").html(locations[i][3]);
                    $("#mvdescription").html(locations[i][4]);
                    $("#form").show();
                    infowindow.setContent(marker.html);
                    infowindow.open(map, marker);
                }
            })(marker, i));
        }
    }

    function saveData() {
        var confirmed = document.getElementById('confirmed').checked ? 1 : 0;
        var id = document.getElementById('id').value;
        var url = 'locations_model.php?confirm_location&id=' + id + '&confirmed=' + confirmed;
        downloadUrl(url, function(data, responseCode) {
            if (responseCode === 200 && data.length > 1) {
                infowindow.close();
                window.location.reload(true);
            } else {
                infowindow.setContent("<div style='color: purple; font-size: 25px;'>Inserting Errors</div>");
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

<div style="display: none" id="form">
    <table class="map1">
        <tr>
            <td rowspan="2"><img src="res/img/pcrrptdraft.png" style="width: 100px;height:100px;cursor: pointer;" alt="" onclick="enlargeimgdgmap()" id="imgdgmap"></td>
            <td>
                <h6>&#9929; <label id="mvsubject"></label></h6>
            </td>
        </tr>
        <tr>
            <td>
                <div style="height:70px;width:200px;overflow: auto;" id="mvdescription"></div>
            </td>
        </tr>
    </table>
</div>
<script async defer src="https://maps.googleapis.com/maps/api/js?language=en&key=AIzaSyB13PGkg5jSJNI6XUmA7weQlwG8tNH6P4c&callback=initMap">
</script>