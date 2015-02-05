//Upload Fleet pic from Wordpress Media
function uploadMedia (e, media, img) {
		var custom_uploader;
	
		e.preventDefault();
		
		//If the uploader object has already been created, reopen the dialog
		if (custom_uploader) {
				custom_uploader.open();
				return;
		}
		
		//Extend the wp.media object
		custom_uploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Image',
				button: {
						text: 'Choose Image'
				},
				multiple: false
		});
		
		//When a file is selected, grab the URL and set it as the text field's value
		custom_uploader.on('select', function() {
				attachment = custom_uploader.state().get('selection').first().toJSON();
				jQuery(media).val(attachment.url);
				jQuery(img).html('<img src="' + attachment.url + '" class="thumbCar" />');
		});
		
		//Open the uploader dialog
		custom_uploader.open();
}

//Generate Google Maps
function bp_generate_map(lat, lon, zoom) {
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

function loadingDiv(div) {
    jQuery(div).html('<div class="alert p2p_bp_alert-info w100p left"><strong><img src="/wp-content/plugins/p2p_bigpromoter/system/style/images/ajax-loader.gif" /> Loading...</strong></div>');
}

//FLEET
function ajaxManageFleet(id,page,div,check) {
        loadingDiv("#showAjax"+id);
		jQuery.ajax({
				type: "POST",
				url: page,
				data: {
					id: id,
					active: check,
					body: jQuery("#body"+id).val(),
					more_than: jQuery("#more_than"+id).val(),
					less_than: jQuery("#less_than"+id).val(),
					image: jQuery("#pic"+id).val(),
					passenger: jQuery("#nPass"+id).val(),
					luggage: jQuery("#nLugg"+id).val(),
					min: jQuery("#min"+id).val(),
					color: jQuery("#color"+id).val()
				}
		}).done(function(msg) {
			jQuery(div).html(msg);
		});
}

function ajaxAddFleet(page,div,check) {
        loadingDiv("#showAjaxAdd");
		jQuery.ajax({
			type: "POST",
			url: page,
			data: {
				active: check,
				body: jQuery("#bodytypeNew").val(),
				more_than: jQuery("#more_thanNew").val(),
				less_than: jQuery("#less_thanNew").val(),
				image: jQuery("#picNew").val(),
				passenger: jQuery("#nPassNew").val(),
				luggage: jQuery("#nLuggNew").val(),
				min: jQuery("#minNew").val(),
				color: jQuery("#colorNew").val()
			}
		}).done(function(msg) {
			jQuery(div).html(msg);
		});	
}
//END FLEET

//SERVICE
function ajaxManageService(id,page,div) {
        loadingDiv("#showAjax"+id);
		jQuery.ajax({
				type: "POST",
				url: page,
				data: {
					id: id,
					service: jQuery("#service"+id).val()
				}
		}).done(function( msg ) {
			jQuery(div).html(msg);
		});
}

function ajaxAddService(page,div) {
        loadingDiv("#showAjaxAdd");
		jQuery.ajax({
			type: "POST",
			url: page,
			data: {
				service: jQuery("#newService").val()
			}
		}).done(function( msg ) {
			jQuery(div).html(msg);
		});	
}
//END SERVICE


		
//AJAX to show Fleet and Prices
function openFleetPrice(IdStart, IdEnd, divMap, page, div) {
		showDirectionOnMap(IdStart, IdEnd, divMap);
		
		var start;
		var end;
		start = encodeURIComponent(document.getElementById(IdStart).value);
		end = encodeURIComponent(document.getElementById(IdEnd).value);
		
		jQuery.ajax({
			type: "POST",
			url: page + 'user/view/fleet_price.php',
			data: {
				add_start: start,
				add_end: end
			}
		}).done(function(msg) {
			jQuery(div).html(msg);
		});	
}

//Ajax to test Calendar Settings
function ajaxTestCalendar(page,div) {
		jQuery(div).css('display','block');
		jQuery.ajax({
			type: "POST",
			url: page
		}).done(function( msg ) {
			jQuery(div).html(msg);
		});	
}

//Find Location on TextArea (Used in Admin/Calendar)
(function ($, undefined) {
    $.fn.getCursorPosition = function () {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
});//(jQuery);