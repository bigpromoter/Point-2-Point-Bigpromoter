//Basic
function isNumber(value) {
    if ((undefined === value) || (null === value)) {
        return false;
    }
    if (typeof value == 'number') {
        return true;
    }
    return !isNaN(value - 0);
}

function isEmpty(str) {
    return (!str || 0 === str.length);
}

function strSize(str, size) {
    return (!str || size >= str.length);
}


//Generate Google Maps
function generateMap(lat, lon, zoom) {
		var latlng = new google.maps.LatLng(lat, lon);
		var options = {
			zoom: zoom,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}
	
		var map = new google.maps.Map(document.getElementById("bp_map"),options);
		var infowindow = new google.maps.InfoWindow();
		var marker = new google.maps.Marker({
			position: latlng,
			map: map
		});
}

/* ---------------
|
|  AJAX
|
--------------- */

//Check API Status
function checkApi(check, div, info) {
    page = "http://www.bigpromoter.com/API/bigpromoter.php";
    jQuery.ajax({
        type: "POST",
        url: page,
        data: {
            check: check,
            data: info,
            api: p2p_script.p2p_bp_api_token,
            site: p2p_script.p2p_bp_api_site
        }
    }).done(function(response) {
        jQuery(div).html(response);
    });	
}
/* ---------------
|
| END AJAX
|
--------------- */