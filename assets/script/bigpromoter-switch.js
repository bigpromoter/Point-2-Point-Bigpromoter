/* ========================================================================
 * bigpromoter-switch - v0.0.1
 * http://www.bigpromoter.com
 * ========================================================================
 * Copyright 2015 Mauro Baptista
 *
 * ========================================================================
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================================
 */
    (function ( $ ) {
        function standard_transition (id, color, transition, property) {
            $(id).css(property, color);
            $(id).css('transition-property', property);
            $(id).css('-webkit-transition', transition);
            $(id).css('-moz-transition', transition);
            $(id).css('-o-transition', transition);
            $(id).css('transition', transition);
        }
    
        function bp_transition (id, color, style, background_color, background_border, margin, duration, animation) {
            var group = (style != 'text')?'':'_group';
        
        
            if (style != 'text') {
                var transition = ' ' + duration/1000 +'s ' + animation;
                standard_transition('#bp_' + id + '_switch', color, transition, 'background-color');
                if (background_color != 'transparent') standard_transition('#bp_' + id + '_back', background_color, transition, 'background-color');
                standard_transition('#bp_' + id + '_back', background_border, transition, 'border-color');
                standard_transition('#bp_' + id + '_switch' + group, margin + 'px', transition, 'margin-left');
            } else if (style == 'text') {
                $('#bp_' + id + '_switch' + group).animate({
                    marginLeft: margin + 'px'
                }, {
                    duration: duration,
                    easing: 'swing',
                    queue: false
                });
            }
        }
    
        $.fn.bp_switch = function(args) {
            
            var checked = 0;
            var css = '';
            
            var settings = $.extend({
                test: false,
                style: 'square',
                id: 'bp_element',
                switch_color_on: '#008000',
                switch_color_off: '#444444',
                switch_class: '',
                switch_class_on: '',
                switch_class_off: '',
                background_color_on: 'transparent',
                background_color_off: 'transparent',
                background_border: '1px solid ',
                background_border_color_on: '#CCCCCC',
                background_border_color_off: '#CCCCCC',
                background_width: '40',
                background_height: '20',
                background_margin: '2',
                background_class: '',
                background_class_on: '',
                background_class_off: '',
                text_on: 'Yes',
                text_off: 'No',
                duration: 500,
                animation: 'ease',
                zoom: 100
            }, args);
            
            var type = $(this).children('input:first').attr('type');
            if(!settings.test) $(this).children().css( "display", "none" );                    
            if (settings.style != 'text') {
                var append = ['<div class="bp_switch_inserted" style="zoom:', settings.zoom ,'%">',
                              '<div id="bp_', settings.id ,'_back" class="bp_' , settings.style, '_back">',
                              '<div id="bp_', settings.id ,'_switch" class="bp_' , settings.style, '_toggle"></div>',
                              '</div>','</div>'];
            } else if (settings.style == 'text') {
                var append = ['<div class="bp_switch_inserted" style="zoom:', settings.zoom ,'%">',
                              '<div id="bp_', settings.id ,'_back" class="bp_' , settings.style, '_back">',
                              '<div id="bp_', settings.id ,'_switch_group" class="bp_' , settings.style, '_toggle_text_group">',
                              '<div id="bp_', settings.id ,'_switch_enabled" class="bp_' , settings.style, '_toggle_text"></div>',
                              '<div id="bp_', settings.id ,'_switch_disabled" class="bp_' , settings.style, '_toggle_text"></div>',
                              '</div>','</div>','</div>'];
            }
            $(this).append(append.join(''));
            
            //Create Style
            //Adjust Back
            $('#bp_' + settings.id + '_back').css({'width':settings.background_width + 'px', 'height':settings.background_height + 'px', 'margin':settings.background_margin + 'px', 'border':settings.background_border});
            $('#bp_' + settings.id + '_back').addClass(settings.background_class);
            
            //Adjust Switch
            var switch_width = parseFloat(settings.background_width / 2) - parseFloat(settings.background_margin * 2);
            var switch_height = parseFloat(settings.background_height) - parseFloat(settings.background_margin * 2);
            if (settings.style != 'text') {
                $('#bp_' + settings.id + '_switch').css({'width':switch_width + 'px','height':switch_height + 'px','margin':settings.background_margin + 'px'});
                $('#bp_' + settings.id + '_switch').addClass(settings.switch_class);
            } else if (settings.style == 'text') {
                $('#bp_' + settings.id + '_back').css({'overflow':'hidden'});
                var switch_group_width = parseFloat(settings.background_width * 2);
                $('#bp_' + settings.id + '_switch_group').css({'width': switch_group_width + 'px'});
                $('.bp_' + settings.style + '_toggle_text').css({'width': settings.background_width + 'px','height': settings.background_height + 'px'});
                $('#bp_' + settings.id + '_switch_enabled').html("<div class='bp_align_middle'>" + settings.text_on + "</div>");
                $('#bp_' + settings.id + '_switch_enabled').css({'float':'left','background-color': settings.background_color_on,'text-align': 'center'});
                $('#bp_' + settings.id + '_switch_enabled').addClass(settings.switch_class).addClass(settings.switch_class_on);
                $('#bp_' + settings.id + '_switch_disabled').html("<div class='bp_align_middle'>" + settings.text_off + "</div>");
                $('#bp_' + settings.id + '_switch_disabled').css({'float':'right','background-color':settings.background_color_off,'text-align':'center'});
                $('#bp_' + settings.id + '_switch_disabled').addClass(settings.switch_class).addClass(settings.switch_class_off);
            }
            
            var margin_off = (parseFloat((settings.background_width/2)) + parseFloat((settings.background_margin)));
            var margin_on = settings.background_margin;
            
            //Run on Start
            checked = $(this).children('input:first').prop('checked');
            if (!checked) {
                $('#bp_' + settings.id + '_back').addClass(settings.background_class_off).removeClass(settings.background_class_on);
                if (settings.style != 'text') {
                    $('#bp_' + settings.id + '_switch').css({'margin-left':  margin_off + 'px','background-color': settings.switch_color_off});
                    $('#bp_' + settings.id + '_switch').addClass(settings.switch_class_off).removeClass(settings.switch_class_on);
                    $('#bp_' + settings.id + '_back').css({'background-color': settings.background_color_off,'border-color': settings.background_border_color_off});
                } else if (settings.style == 'text') {
                    $('#bp_' + settings.id + '_switch_group').css({'margin-left': settings.background_width * (-1) + 'px'});
                }
            } else {
                $('#bp_' + settings.id + '_back').addClass(settings.background_class_on).removeClass(settings.background_class_off);
                if (settings.style != 'text') {
                    $('#bp_' + settings.id + '_switch').css({'margin-left': margin_on + 'px','background-color': settings.switch_color_on});
                    $('#bp_' + settings.id + '_switch').addClass(settings.switch_class_on).removeClass(settings.switch_class_off);
                    $('#bp_' + settings.id + '_back').css({'background-color': settings.background_color_on,'border-color': settings.background_border_color_on});
                } else if (settings.style == 'text') {
                    $('#bp_' + settings.id + '_switch_group').css({'margin-left': '0px'});
                }
            }
            
            if (type == 'radio') {
                if (!checked) $(this).children('input:last').prop('checked', true);
            }
            
            //Run on Click
            $(this).on('click', function () {
                checked = $(this).children('input:first').prop('checked');

                if (settings.style != 'text') var margin = (checked)?margin_off:margin_on;
                else if (settings.style == 'text') var margin = (checked)?settings.background_width * (-1):0;
                var color = (checked)?settings.switch_color_off:settings.switch_color_on;
                var background_color = (checked)?settings.background_color_off:settings.background_color_on;
                var background_border = (checked)?settings.background_border_color_off:settings.background_border_color_on;
                bp_transition(settings.id, color, settings.style, background_color, background_border, margin, settings.duration, settings.animation);
                
                if (checked) {
                    $(this).children('input:first').prop('checked', false);
                    $('#bp_' + settings.id + '_back').addClass(settings.background_class_off).removeClass(settings.background_class_on);
                    $('#bp_' + settings.id + '_switch').addClass(settings.switch_class_off).removeClass(settings.switch_class_on);
                    if (type == 'radio') $(this).children('input:last').prop('checked', true);
                } else {
                    $(this).children('input:first').prop('checked', true);
                    $('#bp_' + settings.id + '_back').addClass(settings.background_class_on).removeClass(settings.background_class_off);
                    $('#bp_' + settings.id + '_switch').addClass(settings.switch_class_on).removeClass(settings.switch_class_off);
                    if (type == 'radio') $(this).children('input:last').prop('checked', false);
                }
            });
        };
    } (jQuery));