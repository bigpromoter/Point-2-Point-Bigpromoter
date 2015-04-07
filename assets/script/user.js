function reservation (price) {
    jQuery('input[name="car_seat"]').change(function () {
        calc_price(price);
    });
                
    jQuery('input[name="r"]').click(function () {
        calc_price(price);
    });
    
    jQuery("#p_apt").change(function () {
        jQuery("#tx_r_d_apt").html(jQuery("#p_apt").val());
    });  

    jQuery("#d_apt").change(function () {
        jQuery("#tx_r_p_apt").html(jQuery("#d_apt").val());
    });
        
    jQuery("#p_date").datepicker({
        defaultDate: "+1d",
        minDate: 0
    });
    jQuery("#r_p_date").datepicker({
        defaultDate: "+1d",
        minDate: 0
    });
                    
    jQuery("#p_date").after('<span class="fa fa-caret-square-o-right adjust-date-picker pull-right" id="go_p_date"></span>');
    jQuery("#r_p_date").after('<span class="fa fa-caret-square-o-right adjust-date-picker pull-right" id="go_r_p_date"></span>');
    jQuery("#go_p_date").click(function () {
        jQuery("#p_date").focus();
    });
    jQuery("#go_r_p_date").click(function () {
        jQuery("#r_p_date").focus();
    });                        
    jQuery('input[name="r_car_seat"]').change(function () {
        calc_price(price);
    });
    jQuery('input[name="gratuity"]').click(function () {
        calc_price(price);
    });
    
    jQuery('#gratuityOther_input').change(function () {
        calc_price(price);
    });
}

function calc_price(currentPrice) {
    var round_trip = jQuery('input[name="r"]:checked', '#checkout').val();
    var value = jQuery('input[name="gratuity"]:checked', '#checkout').val();
    var price = currentPrice;
    
    var car_seat = parseFloat(jQuery('input[name="car_seat"]:checked').val());
    car_seat = isNaN(car_seat)?0:car_seat;
    var r_car_seat = parseFloat(jQuery('input[name="r_car_seat"]:checked').val());
    r_car_seat = isNaN(r_car_seat)?0:r_car_seat;
    
    if (round_trip == 0) {
        jQuery("#roundTrip").css("display", "none");
    } else {
        jQuery("#roundTrip").css("display", "block");
        price = (price * 2);
    }
    
    if (isNaN(value)) {
        var gratuity = parseFloat(jQuery('#gratuityOther_input').val());
        gratuity = (gratuity < 0)?0:gratuity;
        var price_gratuity = (isNaN(gratuity))?price + car_seat + r_car_seat:(Math.round((parseFloat(price) + parseFloat(gratuity)) * 100) / 100) + car_seat + r_car_seat;
    } else {
        var price_gratuity = (Math.round((parseFloat(price) * (1 + (parseInt(value) / 100))) * 100) / 100) + car_seat + r_car_seat;
    }
    
    jQuery("#span_g10").html(Math.round(price * 0.1 * 100)/100);
    jQuery("#span_g15").html(Math.round(price * 0.15 * 100)/100);
    jQuery("#span_g18").html(Math.round(price * 0.18 * 100)/100);
    jQuery("#span_g20").html(Math.round(price * 0.2 * 100)/100);
    
    jQuery('#final_price').html(p2p_script.p2p_bp['basic']['select_currency'] + (Math.round(price_gratuity * 100)/100));
}


/* ---------------
|
|  AJAX
|
--------------- */

//Get Fleet Price
function p2p_bp_get_fleet_price(IdStart, IdEnd, divMap, page, div) {
    showDirectionOnMap(IdStart, IdEnd, divMap);
    var start = encodeURIComponent(document.getElementById(IdStart).value);
    var end = encodeURIComponent(document.getElementById(IdEnd).value);
    
    page = p2p_script.p2p_bp_fleet_price;
    jQuery.ajax({
        type: "POST",
        url: page,
        data: {
            add_start: start,
            add_end: end,
            nonce: p2p_script.p2p_bp_nonce
        }
    }).done(function(response) {
        jQuery(div).html(response);
    });	
}


/* ---------------
|
|  END AJAX
|
--------------- */