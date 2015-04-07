<?php include_once(P2P_DIR_INCLUDE.'user.php'); ?>
<section id="showMap" style="width:%width%; position: relative; top:0px; left: 0px; float: left;" >
    <script src="https://maps.googleapis.com/maps/api/js?key=%google_api%&sensor=false&libraries=places,geometry,drawing"></script>
    <script type="text/javascript">
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;

        
        function initialize() {
            directionsDisplay = new google.maps.DirectionsRenderer();
            var mapOptions = {
                zoom: %start_map_zoom%,
                center: new google.maps.LatLng(%start_map_lat%, %start_map_lon%)
            };
            map = new google.maps.Map(document.getElementById("bigpromoter_map"), mapOptions);
            directionsDisplay.setMap(map);
        }

        function showDirectionOnMap(idStart, idEnd, divMap) {
            var start = document.getElementById(idStart).value;
            var end = document.getElementById(idEnd).value;
            
            var request = {
                origin:start,
                destination:end,
                travelMode: google.maps.TravelMode.DRIVING
            };
            
            directionsService.route(request, function(result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    directionsDisplay.setDirections(result);
                }
            });        
        }        
        google.maps.event.addDomListener(window, 'load', initialize);

        var autocompleteStart, autocompleteEnd;
        
        var bounds = new google.maps.LatLngBounds(new google.maps.LatLng(%start_map_lat%, %start_map_lon%),new google.maps.LatLng(%start_map_lat%, %start_map_lon%));
        
        function geolocateStart() {
            autocompleteStart = new google.maps.places.Autocomplete(
                /** @type {HTMLInputElement} */(document.getElementById('p2p_bigpromoter_start')),{ types: ['geocode'] });
            autocompleteStart.setBounds(bounds);

        }
    
        function geolocateEnd() {
            autocompleteEnd = new google.maps.places.Autocomplete((document.getElementById('p2p_bigpromoter_end')),{ types: ['geocode'] });
            autocompleteEnd.setBounds(bounds);
        }
        
    </script>

    %customcolor%
    <div id="bigpromoter_map" name="bigpromoter_map" style="width:%width%; height:%height%;"></div>    
    <div style="width:%width%;" class="left p2p_bp_map">
        <div class="w100p left">
            <input class="w100p" type="text" name="p2p_bigpromoter_start" id="p2p_bigpromoter_start" placeholder="<?php _e('Pick-up Location', P2P_TRANSLATE); ?>" value="San Francisco, CA, United States" onFocus="geolocateStart();">
            <input class="w100p" type="text" name="p2p_bigpromoter_end" id="p2p_bigpromoter_end" placeholder="<?php _e('Drop-off Location', P2P_TRANSLATE); ?>" value="Oakland, CA, United States"  onFocus="geolocateEnd();">
        </div>
        <div class="right">
            <button type="submit" name="submit" id="submit" class="p2p_bp_btn_map">GET A QUOTE</button>
        </div>
        </div>    
        <div id="p2p_bp_show_price" name="p2p_bp_show_price" style="width:%width%;" class="left"></div>
    </div>
	<script  type="text/javascript">
		jQuery("#submit").click(function() {
            p2p_bp_get_fleet_price('p2p_bigpromoter_start', 'p2p_bigpromoter_end', 'bigpromoter_map', '%plugin_address%', '#p2p_bp_show_price');
        });
	</script>
</section>