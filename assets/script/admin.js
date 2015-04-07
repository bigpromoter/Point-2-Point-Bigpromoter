var index = 999;
var oldValue = new Array();

function showError(id, error) {
        var ajax = jQuery('#showAjax' + id);
        ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_info').addClass('p2p_bp_error');
        ajax.fadeIn();
        ajax.html(error);
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.delay(2000).fadeOut(1000, function () { jQuery(this).addClass('p2p_bp_hide').removeClass('p2p_bp_show'); });
}

function checkField(index, field, type, chars) {
    jQuery('.' + index).live('click', function () {
        var id = jQuery(this).attr('id');
        oldValue[index] = jQuery('#'+ id + '.' + index).val();
    });
    
    jQuery('.' + index).live('change', function () {
        var id = jQuery(this).attr('id');
        var changeValue = jQuery('#'+ id + '.' + index).val();
        var error = "";
        if (type == 'text') error += (strSize(changeValue, chars))?field + " " + p2p_script.p2p_bp_translate['must_have_more_than'] + " " + chars + " " + p2p_script.p2p_bp_translate['characters'] +"!<BR>":"";
        if (type == 'numeric') error += (isNaN((changeValue)) || isEmpty(changeValue))?field + " must be a number!<BR>":"";
        if (!isEmpty(error)) {
            showError(id, error);
            jQuery('#'+ id + '.' + index).val(oldValue[index] );
        }
    });
}

//Find Location on TextArea (Used in Admin/Calendar)
function getCursorPosition(id) {
    var el = jQuery(id).get(0);
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
				title: p2p_script.p2p_bp_translate['choose_image'],
				button: {
						text: p2p_script.p2p_bp_translate['choose_image']
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

/* ---------------
|
|  SETTINGS
|
--------------- */
function settings() {
    //Return API Status
    jQuery("#checkApi").click(function () {
        jQuery(this).fadeOut('fast', function () {
            jQuery("#apiStatus").fadeIn(1000);
        });
        checkApi("test", "#divBigpromoterTest", "");
    });

    //Return Google Calendar API Status
    jQuery("#calendarTest").click(function () {
        jQuery('#divCalendarTest').fadeIn(1000);
        p2p_bp_test_calendar("#divCalendarTest");
    });
    
    //Choose Tab
    jQuery('#p2p_bp_menu ul li').click(function () {
        var id = jQuery(this).attr('id');
        jQuery('.p2p_bp_tab').hide();
        jQuery('.p2p_bp_choose_tab').removeClass('p2p_bp_tab_active');
        jQuery('#p2p_bp_' + id).fadeIn(1000);
        jQuery('#p2p_bp_icon_' + id).fadeIn(1000);
        jQuery(this).addClass('p2p_bp_tab_active');
        jQuery('#p2p_bp_last_tab').val('p2p_bp_' + id);
    });
    
    //Custom Style Tab
    jQuery('#bp_color_active_switch_switch_disabled').click(function() {
        jQuery('#color_active').fadeIn(500);
    });
    jQuery('#bp_color_active_switch_switch_enabled').click(function() {
        jQuery('#color_active').fadeOut(500);
    });
    jQuery('#bp_label_active_switch_switch_disabled').click(function() {
        jQuery('#label_border_color').fadeIn(500);
    });
    jQuery('#bp_label_active_switch_switch_enabled').click(function() {
        jQuery('#label_border_color').fadeOut(500);
    });
    jQuery('#bp_input_active_switch_switch_disabled').click(function() {
        jQuery('#input_border_color').fadeIn(500);
    });
    jQuery('#bp_input_active_switch_switch_enabled').click(function() {
        jQuery('#input_border_color').fadeOut(500);
    });
    jQuery('#bp_button_active_switch_switch_disabled').click(function() {
        jQuery('#button_border_color').fadeIn(500);
    });
    jQuery('#bp_button_active_switch_switch_enabled').click(function() {
        jQuery('#button_border_color').fadeOut(500);
    });
    //End Custom Style
    
    //Calendar Tab
    jQuery('#bp_calendar_switch_switch_enabled').click(function() {
        jQuery('#calendar_active').fadeOut(500);
    });
    jQuery('#bp_calendar_switch_switch_disabled').click(function() {
        jQuery('#calendar_active').fadeIn(500);
    });

    //End Calendar
    
    //Payment Tab
    jQuery('#payment_no').click(function() {
        jQuery('#divBrainTree').css('display','none');
        jQuery('#divPayPal').css('display','none');
        jQuery('#divPaymentNo').css('display','block');
    });
    jQuery('#paypal').click(function() {
        jQuery('#divBrainTree').css('display','none');
        jQuery('#divPayPal').css('display','block');
        jQuery('#divPaymentNo').css('display','none');
    });
    jQuery('#braintree').click(function() {
        jQuery('#divBrainTree').css('display','block');
        jQuery('#divPayPal').css('display','none');
        jQuery('#divPaymentNo').css('display','none');
    });
    //End Payment
}

/* ---------------
|
|  END SETTINGS
|
--------------- */
    
/* ---------------
|
|  MANAGE SERVICES
|
--------------- */
function services() {
    //After Page Loaded
    jQuery('#p2p_bp_loading').fadeOut(1000, function () {
        jQuery('#p2p_bp_services').fadeIn(1000);
    });

    jQuery('.p2p_service_add').click(function () {
        var id = jQuery(this).attr('id');
        var newService = jQuery('#'+ id + '.service_name').val();
        
        var error = "";
        if (strSize(newService, 3)) error = error + p2p_script.p2p_bp_translate['service'] + " " + p2p_script.p2p_bp_translate['must_have_more_than'] +  " 3 "+ p2p_script.p2p_bp_translate['characters'] +"!<BR>";
        if (!isEmpty(error)) {
            showError(id, error);
        } else {
            var newServiceDiv = ["<div id='showAjax" + index + "' class='p2p_bp_alert left p2p_bp_hide w90p'></div>",
                              "<div id='table" + index + "' class='serviceTable'>",
                                "<div class='colActionS'>",
                                  "<div id='" + index + "'  class='p2p_service_delete p2p_bp_btn p2p_bp_btn_remove' ><i class='fa fa-times'></i></div>",
                                "</div>",
                                "<div class='colServiceS'>",
                                    "<input id='"+ index + "' type='text' name='p2p_bp_services[name][]' value='" + newService + "' class='w100p service_name' />",
                                "</div>",
                              "</div>"];
            jQuery('#newService').append(newServiceDiv.join(''));
            jQuery('#'+ id + '.service_name').val('')
            
            var div = jQuery('#table' + index );
            var color = div.css('background-color');
            
            div.css({"background-color":"rgb(191, 242, 182)", "transition":"background-color 1.5s ease"});
            setTimeout(function () {
                div.css("background-color", color);
            }, 2500);
            index--;
        }
    });
    
    jQuery('.p2p_service_delete').live ("click", function () {
        var id = jQuery(this).attr('id');
        var service = jQuery('#'+ id + '.service_name').val();
        
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeIn();
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_error').addClass('p2p_bp_info');
        var del = [p2p_script.p2p_bp_translate['confirm_deletion']," ",p2p_script.p2p_bp_translate['service']," (", service, "): ",
                    "<div id='" + id + "' class='p2p_service_delete_confirm p2p_bp_save p2p_bp_btn_remove' >",p2p_script.p2p_bp_translate['confirm'],"</div>",
                    "<div id='" + id + "' class='p2p_service_close p2p_bp_save' >",p2p_script.p2p_bp_translate['close'],"</div>"
        ];
        
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.html(del.join(''));
    });
    
    jQuery('.p2p_service_delete_confirm').live("click", function () {
        var id = jQuery(this).attr('id');
        
        showError(id, p2p_script.p2p_bp_translate['service']+ " " + p2p_script.p2p_bp_translate['deleted'] + "!");
        
        jQuery('#showAjax' + id).delay(2000).fadeOut(1000, function() { jQuery(this).remove(); });
        jQuery('#table' + id).fadeOut(2000, function() { jQuery(this).remove(); });
    });
    
    jQuery('.p2p_service_close').live("click", function () {
        var id = jQuery(this).attr('id');
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeOut(500, function () {
            jQuery(this).addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_info').removeClass('p2p_bp_error');
        });
    });
    
    jQuery('.p2p_bp_service').live('click', function () {
        var id = jQuery(this).attr('id');
        oldValue = jQuery('#'+ id + '.p2p_bp_service').val();
    });
    
    jQuery('.p2p_bp_service').live('change', function () {
        var id = jQuery(this).attr('id');
        var changeValue = jQuery('#'+ id + '.p2p_bp_service').val();
        var error = "";
        if (strSize(changeValue, 3)) error = error + p2p_script.p2p_bp_translate['service'] + " " + p2p_script.p2p_bp_translate['must_have_more_than'] + " 3 "+ p2p_script.p2p_bp_translate['characters'] + "!<BR>";
        if (!isEmpty(error)) {
            showError(id, error);
            jQuery('#'+ id + '.p2p_bp_service').val(oldValue);
        }
    });
    checkField('service_name', p2p_script.p2p_bp_translate['service'], 'text', 3);
}
/* ---------------
|
|  END MANAGE SERVICE
|
--------------- */

/* ---------------
|
|  MANAGE FLEET
|
--------------- */
function fleet() {
    //After Page Loaded
    jQuery('#p2p_bp_loading').fadeOut(1000, function () {
        jQuery('#p2p_bp_fleet').fadeIn(1000);
        checkApi("fleetInfo", "#fleetInfo", "");
    });
    
    //Manage upload
    jQuery('.fleet_upload_pic').live('click', function(e) {
        var id = jQuery(this).attr('id');
        uploadMedia (e, '#pic' + id,'#thumb' + id);
    });
    
    //Manage upload
    jQuery('#upload_picNew').click(function(e) {
        uploadMedia (e, '#picNew','#picNewThumb');
    });
    
    //Manage Add Car	
    jQuery(".p2p_fleet_add").click(function () {
        var id = jQuery(this).attr('id');
        var body = jQuery("#bodytypeNew").val();
        var less_than = jQuery("#less_thanNew").val();
        var more_than = jQuery("#more_thanNew").val();
        var min = jQuery("#minNew").val();
        var passenger = jQuery("#nPassNew").val();
        var luggage = jQuery("#nLuggNew").val();
        var color = jQuery("#colorNew").val();
        var pic = jQuery("#picNew").val();
        var error = "";
        if (strSize(body, 3)) error = error + p2p_script.p2p_bp_translate['body'] + " " + p2p_script.p2p_bp_translate['must_have_more_than'] + " 3 "+ p2p_script.p2p_bp_translate['characters'] + "!<BR>";
        if (isNaN((less_than)) || isEmpty(less_than)) error = error + p2p_script.p2p_bp_translate['less_than'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        if (isNaN((more_than)) || isEmpty(more_than)) error = error + p2p_script.p2p_bp_translate['more_than'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        if (isNaN((min)) || isEmpty(min)) error = error + p2p_script.p2p_bp_translate['minimum'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        if (isNaN((passenger)) || isEmpty(passenger)) error = error + p2p_script.p2p_bp_translate['passenger'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        if (isNaN((luggage)) || isEmpty(luggage)) error = error + p2p_script.p2p_bp_translate['luggage'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        if (isNaN((color)) || isEmpty(color)) error = error + p2p_script.p2p_bp_translate['color'] + " " + p2p_script.p2p_bp_translate['must_be_a_number'] + "!<BR>";
        
        if (!isEmpty(error)) {
            showError(id, error)
        } else {
            //Clean Form
            jQuery("#bodytypeNew").val('');
            jQuery("#less_thanNew").val('');
            jQuery("#more_thanNew").val('');
            jQuery("#minNew").val('');
            jQuery("#picNew").val('');
            jQuery("#picNewThumb").html('');
            jQuery("#nPassNew option[value='1']").prop('selected', true);
            jQuery("#nLuggNew option[value='1']").prop('selected', true);
            jQuery("#colorNew option[value='1']").prop('selected', true);
            
            //New Div
            var newFleetDiv = ['<div id="showAjax' + index + '" class="p2p_bp_alert left p2p_bp_hide w90p"></div>',
                    '<div id="table' + index + '" class="carTable left w100p">',
                    '    <div class="colAction">',
                    '        <div id="p2p_bp_car_delete_btn"><div id="' + index + '" class="p2p_fleet_delete p2p_bp_btn p2p_bp_btn_remove" ><i class="fa fa-remove"></i></div></div>',
                    '    </div>',
                    '    <div class="colBody">',
                    '        <input type="text" id="' + index + '" name="p2p_bp_fleet[body][]" value="' + body + '" class="w90p fleet_body" placeholder="',p2p_script.p2p_bp_translate['body'],'"/>',
                    '    </div>',
                    '    <div class="junNum">',
                    '        <div class="colLess">',
                    '            <span class="descLess">< <span id="span_distance">'+ p2p_script.p2p_bp['basic']['distance'] + ' ' + p2p_script.p2p_bp['basic']['select_distance'] + '</span><BR></span>',
                    '            <span class="currency w30p"> '+ p2p_script.p2p_bp['basic']['select_currency'] + '</span>',
                    '            <input type="text" name="p2p_bp_fleet[lessThan][]" id="' + index + '" value="' + less_than + '"  class="w70p fleet_lessThan"/>',
                    '        </div>',
                    '        <div class="colMore">',
                    '            <span class="descLess">< <span id="span_distance">'+ p2p_script.p2p_bp['basic']['distance'] + ' ' + p2p_script.p2p_bp['basic']['select_distance'] + '</span><BR></span>',
                    '            <span class="currency w30p">'+ p2p_script.p2p_bp['basic']['select_currency'] + '</span>',
                    '            <input type="text" name="p2p_bp_fleet[moreThan][]" id="' + index + '" value="' + more_than + '"  class="w70p fleet_moreThan"/>',
                    '        </div>',
                    '        <div class="colMin">',
                    '          <span class="descLess">',p2p_script.p2p_bp_translate['minimum'],'<BR></span>',
                    '          <span class="currency w30p">'+ p2p_script.p2p_bp['basic']['select_currency'] + '</span>',
                    '          <input type="text" name="p2p_bp_fleet[minimum][]" id="' + index + '" value="' + min + '" class="w70p fleet_minimum" placeholder="Min"/>',
                    '        </div>',
                    '    </div>',
                    '    <div class="junNum">',
                    '        <div class="colPassenger">',
                    '            <span class="descLess">',p2p_script.p2p_bp_translate['passenger'],'<BR></span>',
                    '<select id="'+index+'" name="p2p_bp_fleet[nPass][]">'];
            
                    for (n = 1; n <= p2p_script.p2p_bp_max_passenger; n++) {
                        var select = (passenger == n)?'selected':'';
                        newFleetDiv.push('<option value="'+n+'" '+select+'>'+n+'</option>');
                    }
            newFleetDiv.push('        </select>',
                             '        </div>',
                             '        <div class="colLuggage">',
                             '            <span class="descLess">',p2p_script.p2p_bp_translate['luggage'],'<BR></span>',
                             '<select id="'+index+'" name="p2p_bp_fleet[nLugg][]">');
                             
                    for (n = 1; n <= p2p_script.p2p_bp_max_luggage; n++) {
                        var select = (luggage == n)?'selected':'';
                        newFleetDiv.push('<option value="'+n+'" '+select+'>'+n+'</option>');
                    }
                             
                             
            newFleetDiv.push('        </select>',
                            '        </div>',
                            '        <div class="colColor">',
                            '            <span class="descLess">',p2p_script.p2p_bp_translate['color'],'<BR></span>',
                            '            <select id="'+index+'" name="p2p_bp_fleet[color][]" class="fleet_color">');
            
            select = (color == '1')?'selected':'';
            newFleetDiv.push('<option value="1" '+select+'>1</option>');
            select = (color == '2')?'selected':'';
            newFleetDiv.push('<option value="2" '+select+'>2</option>');
            select = (color == '3')?'selected':'';
            newFleetDiv.push('<option value="3" '+select+'>3</option>');
            select = (color == '4')?'selected':'';
            newFleetDiv.push('<option value="4" '+select+'>4</option>');
            select = (color == '5')?'selected':'';
            newFleetDiv.push('<option value="5" '+select+'>5</option>');
            select = (color == '6')?'selected':'';
            newFleetDiv.push('<option value="6" '+select+'>6</option>');
            select = (color == '7')?'selected':'';
            newFleetDiv.push('<option value="7" '+select+'>7</option>');
            select = (color == '8')?'selected':'';
            newFleetDiv.push('<option value="8" '+select+'>8</option>');
            select = (color == '9')?'selected':'';
            newFleetDiv.push('<option value="9" '+select+'>9</option>');
            select = (color == '10')?'selected':'';
            newFleetDiv.push('<option value="10" '+select+'>10</option>');
            select = (color == '11')?'selected':'';
            newFleetDiv.push('<option value="11" '+select+'>11</option>');

            newFleetDiv.push('        </select>',
                            '        </div>',
                            '    </div>',
                            '    <div class="colImage">',
                            '        <div id="thumb' + index + '" class="thumbCar"><img src="' + pic + '" class="thumbCar"/></div>',
                            '        <input id="pic' + index + '" type="hidden" class="w90p fleet_pic" name="p2p_bp_fleet[pic][]" value="' + pic + '" />',
                            '        <button id="' + index + '" name="upload_picNew" class="onlyMarginLeft15 p2p_bp_save fleet_upload_pic" type="button">', p2p_script.p2p_bp_translate['upload_image'] ,'</button>',
                            '    </div>',
                            '</div>');
            
            jQuery('#newCar').append(newFleetDiv.join(''));
            jQuery('#'+ id + '.p2p_fleet_add').val('')
            
            var div = jQuery('#table' + index );
            var color = div.css('background-color');
            
            div.css({"background-color":"rgb(191, 242, 182)", "transition":"background-color 1.5s ease"});
            setTimeout(function () {
                div.css("background-color", color);
            }, 2500);
            index--;
        }
    });
    
    jQuery('.p2p_fleet_delete').live ("click", function () {
        var id = jQuery(this).attr('id');
        var car = jQuery('#'+ id + '.fleet_body').val();
        
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeIn();
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_error').addClass('p2p_bp_info');
        var del = [p2p_script.p2p_bp_translate['confirm_deletion']," ",p2p_script.p2p_bp_translate['car']," (", car, "): ",
                    "<div id='" + id + "' class='p2p_fleet_delete_confirm p2p_bp_save p2p_bp_btn_remove' >"+p2p_script.p2p_bp_translate['confirm']+"</div>",
                    "<div id='" + id + "' class='p2p_fleet_close p2p_bp_save' >"+p2p_script.p2p_bp_translate['close']+"</div>"
        ];
        
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.html(del.join(''));
    });
    
    jQuery('.p2p_fleet_delete_confirm').live("click", function () {
        var id = jQuery(this).attr('id');
        
        showError(id, p2p_script.p2p_bp_translate['car'] + " " + p2p_script.p2p_bp_translate['deleted'] + "!");
        
        jQuery('#showAjax' + id).delay(2000).fadeOut(1000, function() { jQuery(this).remove(); });
        jQuery('#table' + id).fadeOut(2000, function() { jQuery(this).remove(); });
    });
    
    jQuery('.p2p_fleet_close').live("click", function () {
        var id = jQuery(this).attr('id');
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeOut(500, function () {
            jQuery(this).addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_info').removeClass('p2p_bp_error');
        });
    });
    
    checkField('fleet_body', p2p_script.p2p_bp_translate['body'], 'text', 3);
    checkField('fleet_lessThan', p2p_script.p2p_bp_translate['less_than'], 'numeric', false);
    checkField('fleet_moreThan', p2p_script.p2p_bp_translate['more_than'], 'numeric', false);
    checkField('fleet_minimum', p2p_script.p2p_bp_translate['minimum'], 'numeric', false);
}
/* ---------------
|
|  END MANAGE FLEET
|
--------------- */

/* ---------------
|
|  EXPORT/IMPORT
|
--------------- */
function exportImport() {
    //After Page Loaded
    jQuery('#p2p_bp_loading').fadeOut(1000, function () {
        jQuery('#p2p_bp_export_import').fadeIn(1000);
    });
    
    jQuery('[name="import_export"]').click(function () {
        var id = jQuery(this).attr('id');
        jQuery('.p2p_bp_exp_imp').css('display','none');
        jQuery('#' + id + "_div").css('display','block');
    });
    
    jQuery('#p2p_bp_export_create').click(function () {
        p2p_bp_create_export('#p2p_bp_export_code', jQuery('[name="p2p_bp_export_options"]:checked').val(), jQuery('[name="p2p_bp_export_fleet"]:checked').val(), jQuery('[name="p2p_bp_export_service"]:checked').val());
    });
    
    jQuery('#p2p_bp_import_create').click(function () {
        p2p_bp_create_import('#p2p_bp_import_result', jQuery('textarea#p2p_bp_import_textarea').val());
    });

}
/* ---------------
|
|  EXPORT/IMPORT
|
--------------- */

/* ---------------
|
|  RESERVATION
|
--------------- */
function reservation() {
    //After Page Loaded
    jQuery('#p2p_bp_loading').fadeOut(1000, function () {
        jQuery('#p2p_bp_reservation').fadeIn(1000);
    });
    
    jQuery('.p2p_reservation_delete').live ("click", function () {
        var id = jQuery(this).attr('id');
        
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeIn();
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_error').addClass('p2p_bp_info');
        var del = [p2p_script.p2p_bp_translate['confirm_deletion']," ",p2p_script.p2p_bp_translate['reservation']," (", id, "): ",
                    "<div id='" + id + "' class='p2p_reservation_delete_confirm p2p_bp_save p2p_bp_btn_remove' >"+p2p_script.p2p_bp_translate['confirm']+"</div>",
                    "<div id='" + id + "' class='p2p_reservation_close p2p_bp_save' >"+p2p_script.p2p_bp_translate['close']+"</div>"
        ];
        
        ajax.removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        ajax.html(del.join(''));
    });
    
    jQuery('.p2p_reservation_delete_confirm').live("click", function () {
        var id = jQuery(this).attr('id');
        
        p2p_bp_confirm_delete_reservation('#showAjax' + id, id);
    });
    
    jQuery('.p2p_reservation_close').live("click", function () {
        var id = jQuery(this).attr('id');
        var ajax = jQuery('#showAjax' + id);
        ajax.fadeOut(500, function () {
            jQuery(this).addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.addClass('p2p_bp_hide').removeClass('p2p_bp_show');
            ajax.removeClass('p2p_bp_success').removeClass('p2p_bp_info').removeClass('p2p_bp_error');
        });
    });
    
    jQuery('.p2p_reservation_seemore').click(function () {
        var id = jQuery(this).attr('id');
        jQuery('.p2p_reservation_btn_seemore' + id).removeClass('p2p_bp_show').addClass('p2p_bp_hide');
        jQuery('.p2p_reservation_btn_seemore_close' + id).removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        p2p_bp_get_reservation_info('#showSeeMore'+id, id);
        
    });
    
    jQuery('.p2p_reservation_seemore_close').click(function () {
        var id = jQuery(this).attr('id');
        jQuery('.p2p_reservation_btn_seemore_close' + id).removeClass('p2p_bp_show').addClass('p2p_bp_hide');
        jQuery('.p2p_reservation_btn_seemore' + id).removeClass('p2p_bp_hide').addClass('p2p_bp_show');
        jQuery('#showSeeMore'+id).fadeOut('fast');
    });
    
    jQuery('.p2p_bp_change_provided').live("click", function () {
        var id = jQuery(this).attr('id');
        p2p_bp_change_provided('.p2p_bp_ball'+id, id, 0);
    });
    
    jQuery('.p2p_bp_change_provided_r').live("click", function () {
        var id = jQuery(this).attr('id');
        p2p_bp_change_provided('.p2p_bp_ball_r'+id, id, 1);
    });
}
    
/* ---------------
|
|  END RESERVATION
|
--------------- */

/* ---------------
|
|  AJAX
|
--------------- */

function p2p_bp_test_calendar(div) {
    data = {
        action: 'p2p_bp_calendar',
        nonce: p2p_script.p2p_bp_nonce
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).html(response);
    });
    
    return false;
}

function p2p_bp_create_export(div, options, fleet, services) {
    data = {
        action: 'p2p_bp_export',
        nonce: p2p_script.p2p_bp_nonce,
        options: options,
        fleet: fleet,
        services: services
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).html(response);
    });
    
    return false;
}

function p2p_bp_create_import(div, code) {
    data = {
        action: 'p2p_bp_import',
        nonce: p2p_script.p2p_bp_nonce,
        code: code
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).html(response);
    });
    
    return false;
}

function p2p_bp_confirm_delete_reservation(div, id) {
    data = {
        action: 'p2p_bp_delete_reservation',
        nonce: p2p_script.p2p_bp_nonce,
        id: id
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).html(response);
    });
    
    return false;
}

function p2p_bp_get_reservation_info(div, id) {
    data = {
        action: 'p2p_bp_get_reservation',
        nonce: p2p_script.p2p_bp_nonce,
        id: id
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).fadeIn('fast');
        jQuery(div).html(response);
    });
    
    return false;
}

function p2p_bp_change_provided(div, id, round) {
    data = {
        action: 'p2p_bp_provided',
        nonce: p2p_script.p2p_bp_nonce,
        id: id,
        round: round
    };
    
    jQuery.post(ajaxurl, data, function (response) {
        jQuery(div).html(response);
    });
    
    return false;
}

/* ---------------
|
|  END AJAX
|
--------------- */

/* ---------------
|
|  CREATE SWITCH
|
--------------- */
jQuery(document).ready(function () {
    jQuery('#insert_gratuity').bp_switch({
        id: 'insert_gratuity',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#placeholder').bp_switch({
        id: 'placeholder',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#extra_enabled').bp_switch({
        id: 'extra_enabled',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['enabled'], text_off: p2p_script.p2p_bp_translate['disabled']
    });
    jQuery('#car_seat').bp_switch({
        id: 'car_seat',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['enabled'], text_off: p2p_script.p2p_bp_translate['disabled']
    });
    jQuery('#calendar_switch').bp_switch({
        id: 'calendar_switch',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#nopayment_creditcard').bp_switch({
        id: 'nopayment_creditcard',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#paypal_enviroment').bp_switch({
        id: 'paypal_enviroment',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_off',switch_class_off: 'text_green', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['sandbox'], text_off: p2p_script.p2p_bp_translate['live']
    });
    jQuery('#braintree_enviroment').bp_switch({
        id: 'braintree_enviroment',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_off',switch_class_off: 'text_green', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['sandbox'], text_off: p2p_script.p2p_bp_translate['production']
    });
    jQuery('#admin').bp_switch({
        id: 'admin',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#debug').bp_switch({
        id: 'debug',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#curl').bp_switch({
        id: 'curl',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#color_active_btn').bp_switch({
        id: 'color_active_switch',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#color_label').bp_switch({
        id: 'label_active_switch',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#color_input').bp_switch({
        id: 'input_active_switch',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#color_button').bp_switch({
        id: 'button_active_switch',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#delete_table').bp_switch({
        id: 'delete_table',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    jQuery('#delete_settings').bp_switch({
        id: 'delete_settings',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#jquery_ui_datepicker').bp_switch({
        id: 'jquery_ui_datepicker',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#jquery_ui_button').bp_switch({
        id: 'jquery_ui_button',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#font_awesome').bp_switch({
        id: 'font_awesome',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#jquery_ui').bp_switch({
        id: 'jquery_ui',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#wp_color_picker').bp_switch({
        id: 'wp_color_picker',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#load_jquery_google').bp_switch({
        id: 'load_jquery_google',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#services_others').bp_switch({
        id: 'services_others',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#p2p_bp_export_options').bp_switch({
        id: 'p2p_bp_export_options',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#p2p_bp_export_fleet').bp_switch({
        id: 'p2p_bp_export_fleet',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    });
    
    jQuery('#p2p_bp_export_service').bp_switch({
        id: 'p2p_bp_export_service',
        style: 'text',background_class: 'bg',switch_class: 'text',switch_class_on: 'text_green',switch_class_off: 'text_off', background_width: 100, background_height: 30,background_border: 'none', text_on: p2p_script.p2p_bp_translate['yes'], text_off: p2p_script.p2p_bp_translate['no']
    }); 
});
/* ---------------
|
|  END CREATE SWITCH
|
--------------- */
