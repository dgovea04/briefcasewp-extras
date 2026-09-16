let bewaa_billing_map;
let bewaa_billing_autocomplete;
let bewaa_billing_address_1;
let bewaa_shipping_map;
let bewaa_shipping_autocomplete;
let bewaa_shipping_address_1;
let bewaa_pos;
let bewaa_shipping_marker;
let bewaa_billing_marker;
let bewaa_billing_infowindow;
let bewaa_shipping_infowindow;
let bewaa_current_place;
let bewaa_geocoder;
let bewaa_place;
let bewaa_formatted_address;
let bewaa_shipping_map_exist = false;
let bewaa_billing_map_exist = false;
let bewaa_map_zoom = parseInt(bewaa_autocomplete.bewaa_map_zoom);

(function($) {
    'use strict';

	function bewaa_initMap() {

        if (jQuery("#bewaa_shipping_map").length) {
            bewaa_shipping_map_exist = true;
        }

        if (jQuery("#bewaa_billing_map").length) {
            bewaa_billing_map_exist = true;
        }

        bewaa_console_log("bewaa_initMap");
        bewaa_geocoder = new google.maps.Geocoder();
        if (bewaa_shipping_map_exist) {
            bewaa_shipping_map = new google.maps.Map(document.getElementById("bewaa_shipping_map"), {
                center: { lat: parseFloat(bewaa_autocomplete.bewaa_center_map_latitude), lng: parseFloat(bewaa_autocomplete.bewaa_center_map_longitude) },
                zoom: bewaa_map_zoom,
            });
            bewaa_shipping_infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150, 50) });
        }

        if (bewaa_billing_map_exist) {
            bewaa_billing_map = new google.maps.Map(document.getElementById("bewaa_billing_map"), {
                center: { lat: parseFloat(bewaa_autocomplete.bewaa_center_map_latitude), lng: parseFloat(bewaa_autocomplete.bewaa_center_map_longitude) },
                zoom: bewaa_map_zoom,
            });
            bewaa_billing_infowindow = new google.maps.InfoWindow({ size: new google.maps.Size(150, 50) });
        }

        var set_marker_customer_location = false;

        if (bewaa_autocomplete.bewaa_customer_location == "1") {
            set_marker_customer_location = true;
            bewaa_geolocate();
        }


        if (set_marker_customer_location == false) {
            var marker_latlng = parseFloat(bewaa_autocomplete.bewaa_center_map_latitude) + "," + parseFloat(bewaa_autocomplete.bewaa_center_map_longitude);
            bewaa_geocodeLatLng(bewaa_geocoder, bewaa_billing_map, bewaa_billing_infowindow, marker_latlng, 'both', 'dragend', false);
        }
    }

    function bewaa_initAutocomplete() {
        bewaa_console_log("bewaa_initAutocomplete");
        if (bewaa_autocomplete.bewaa_map == "1") {
            bewaa_initMap();
        }

        //Billing
        if (jQuery("#billing_address_1").length && bewaa_autocomplete.bewaa_billing == "1") {
            bewaa_billing_address_1 = document.querySelector("#billing_address_1");

            bewaa_billing_autocomplete = new google.maps.places.Autocomplete(bewaa_billing_address_1, {

            });
            if (bewaa_autocomplete.bewaa_restrictions != "") {
                bewaa_billing_autocomplete.setComponentRestrictions({ country: bewaa_autocomplete.bewaa_restrictions });
            }
            bewaa_billing_address_1.focus();
            bewaa_billing_autocomplete.addListener("place_changed",
                function() {
                    bewaa_place = bewaa_billing_autocomplete.getPlace();
                    bewaa_console_log("place_changed");

                    bewaa_fillInAddress(bewaa_place, 'billing');
                    bewaa_pos = bewaa_place.geometry.location;
                    bewaa_formatted_address = bewaa_place.formatted_address;
                    if (bewaa_autocomplete.bewaa_map == "1") {
                        bewaa_show_marker_on_map(bewaa_pos, bewaa_place, bewaa_billing_map, 'billing', '');
                    }
                });
        }

        //Shipping
        if (jQuery("#shipping_address_1").length && bewaa_autocomplete.bewaa_shipping == "1") {
            bewaa_shipping_address_1 = document.querySelector("#shipping_address_1");
            bewaa_shipping_autocomplete = new google.maps.places.Autocomplete(bewaa_shipping_address_1, {

            });
            if (bewaa_autocomplete.bewaa_restrictions != "") {
                bewaa_shipping_autocomplete.setComponentRestrictions({ country: bewaa_autocomplete.bewaa_restrictions });
            }
            bewaa_shipping_address_1.focus();
            bewaa_shipping_autocomplete.addListener("place_changed",
                function() {
                    bewaa_place = bewaa_shipping_autocomplete.getPlace();
                    bewaa_console_log("place_changed");

                    bewaa_fillInAddress(bewaa_place, 'shipping');
                    bewaa_pos = bewaa_place.geometry.location;
                    bewaa_formatted_address = bewaa_place.formatted_address;
                    if (bewaa_autocomplete.bewaa_map == "1") {
                        bewaa_show_marker_on_map(bewaa_pos, bewaa_place, bewaa_shipping_map, 'shipping', '');
                    }
                });
        }

    }

    function bewaa_show_marker_on_map(pos, bewaa_place, map, address_type, event_type, show_infowindow = true) {
        bewaa_console_log('bewaa_show_marker_on_map ' + address_type + ' ' + event_type);

        bewaa_formatted_address = bewaa_place.formatted_address
        if (address_type == "both") {
            if (bewaa_billing_map_exist) {
                bewaa_show_marker_on_map(pos, bewaa_place, bewaa_billing_map, 'billing', event_type, show_infowindow);
            }
            if (bewaa_shipping_map_exist) {
                bewaa_show_marker_on_map(pos, bewaa_place, bewaa_shipping_map, 'shipping', event_type, show_infowindow)
            }
            return true;
        }

        var bewaa_draggable = false;
        /* <premium_only> */
        if (bewaa_autocomplete.bewaa_location_picker == "1") {
            bewaa_draggable = true;
        }
        /* </premium_only> */

        var bewaa_infowindow_content = bewaa_formatted_address;

        // Add choose address button.
        bewaa_current_place = "";
        if (event_type == "dragend") {
            bewaa_current_place = bewaa_place;
            if (bewaa_autocomplete.bewaa_location_picker_type != '2') {
                bewaa_infowindow_content = bewaa_infowindow_content + '<div class="bewaa_marker"><button class="bewaa_set_address_btn" type="button" onclick="bewaa_set_address_from_marker(\'' + address_type + '\');">' + bewaa_autocomplete.bewaa_select_address_text + '</button></div>';
            }
            if (bewaa_autocomplete.bewaa_location_picker_type != '1') {
                bewaa_infowindow_content = bewaa_infowindow_content + '<div class="bewaa_marker"><button class="bewaa_set_location_btn" type="button" onclick="bewaa_set_location_from_marker(\'' + address_type + '\');">' + bewaa_autocomplete.bewaa_select_location_text + '</button></div>';
            }

        }


        if (address_type == 'shipping' && bewaa_shipping_map_exist) {
            map.panTo(pos);
            bewaa_shipping_infowindow.setContent(bewaa_infowindow_content);

            if (!bewaa_shipping_marker) {
                bewaa_shipping_marker = new google.maps.Marker({
                    position: pos,
                    map,
                    draggable: bewaa_draggable
                });
                /* <premium_only> */
                bewaa_shipping_marker.addListener('dragstart', function(event) {
                    bewaa_shipping_infowindow.close();
                    jQuery(".bewaa_set_address_btn").replaceWith("");
                    jQuery(".bewaa_set_location_btn").replaceWith("");
                });
                bewaa_shipping_marker.addListener('dragend', function(event) {
                    bewaa_handleEvent(event, map, 'shipping');
                });
                /* </premium_only> */




            } else {
                bewaa_shipping_marker.setPosition(pos);

            }
            if (show_infowindow) {
                bewaa_shipping_infowindow.open(map, bewaa_shipping_marker);
                map.addListener("zoom_changed", () => {
                    bewaa_shipping_infowindow.close();
                });
            }

        }

        if (address_type == 'billing' && bewaa_billing_map_exist) {
            map.panTo(pos);
            bewaa_billing_infowindow.setContent(bewaa_infowindow_content);
            if (!bewaa_billing_marker) {
                bewaa_billing_marker = new google.maps.Marker({
                    position: pos,
                    map,
                    draggable: bewaa_draggable
                });
                /* <premium_only> */
                bewaa_billing_marker.addListener('dragstart', function(event) {
                    bewaa_billing_infowindow.close();
                    jQuery(".bewaa_set_address_btn").replaceWith("");
                    jQuery(".bewaa_set_location_btn").replaceWith("");
                });
                bewaa_billing_marker.addListener('dragend', function(event) {
                    bewaa_handleEvent(event, map, 'billing');
                });
                /* </premium_only> */



            } else {
                bewaa_billing_marker.setPosition(pos);

            }

            if (show_infowindow) {
                bewaa_billing_infowindow.open(map, bewaa_billing_marker);
                map.addListener("zoom_changed", () => {
                    bewaa_billing_infowindow.close();
                });
            }

        }


    }

    function bewaa_geocodeLatLng(bewaa_geocoder, map, infowindow, marker_latlng, address_type, event_type, show_content = true) {
        bewaa_console_log("bewaa_geocodeLatLng");
        const input = marker_latlng;
        const latlngStr = input.split(",", 2);
        const latlng = {
            lat: parseFloat(latlngStr[0]),
            lng: parseFloat(latlngStr[1]),
        };
        bewaa_geocoder.geocode({ location: latlng })
            .then((response) => {
                if (response.results[0]) {

                    if (event_type != "dragend") {
                        // Update address on autocomplete only.
                        bewaa_fillInAddress(response.results[0], address_type);
                    }

                    if (bewaa_autocomplete.bewaa_map == "1") {
                        // Show marker on map.
                        bewaa_show_marker_on_map(latlng, response.results[0], map, address_type, event_type, show_content);
                    }
                } else {
                    bewaa_console_log("No results found");
                }
            })
            .catch((e) => bewaa_console_log("Geocoder failed due to: " + e));
    }

    /* <premium_only> */
    function bewaa_handleEvent(event, map, address_type) {
        bewaa_console_log("bewaa_handleEvent");

        var marker_latlng = event.latLng.lat() + "," + event.latLng.lng();

        if (address_type == 'billing') {
            bewaa_geocodeLatLng(bewaa_geocoder, map, bewaa_billing_infowindow, marker_latlng, 'billing', 'dragend');
        }
        if (address_type == 'shipping') {
            bewaa_geocodeLatLng(bewaa_geocoder, map, bewaa_shipping_infowindow, marker_latlng, 'shipping', 'dragend');
        }

    }

    // geolocation
    function bewaa_geolocate() {
        bewaa_console_log('bewaa_geolocate');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(bewaa_geolocate_success, bewaa_geolocate_error);
        } else {
            //alert("Geolocation is not supported by this browser.");
        }
    }

    function bewaa_geolocate_success(pos) {
        bewaa_console_log('bewaa_geolocate_success');
        var lat = pos.coords.latitude;
        var lng = pos.coords.longitude;
        var marker_latlng = lat + "," + lng;
        var event_type = 'dragend';
        if (bewaa_autocomplete.bewaa_customer_location_auto_select == '1') {
            event_type = 'select';
        }
        bewaa_geocodeLatLng(bewaa_geocoder, bewaa_billing_map_exist, bewaa_billing_infowindow, marker_latlng, 'both', event_type);
    }

    function bewaa_geolocate_error(e) {
        bewaa_console_log('bewaa_geolocate_error');
        bewaa_console_log(e);
    }
    /* </premium_only> */

    jQuery(document).ready(function() {

        if (jQuery("#billing_address_1").length || jQuery("#shipping_address_1").length || jQuery("#bewaa_shipping_map").length) {
            google.maps.event.addDomListener(window, 'load', bewaa_initAutocomplete);
        }

    });
})(jQuery);


function bewaa_console_log(data) {
    console.log(data);
}

function bewaa_set_address_from_marker(address_type) {
    bewaa_console_log('bewaa_set_address_from_marker');
    bewaa_fillInAddress(bewaa_current_place, address_type);
    jQuery(".bewaa_set_address_btn").replaceWith("<svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"check\" class=\"svg-inline--fa fa-check fa-w-16\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path fill=\"currentColor\" d=\"M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z\"></path></svg>" + "<span class=\"bewaa_marker_text\">" + bewaa_autocomplete.bewaa_address_selected_text + "</span>");
    jQuery(".bewaa_set_location_btn").replaceWith("");
}

function bewaa_set_location_from_marker(address_type) {
    bewaa_console_log('bewaa_set_location_from_marker');
    bewaa_set_location(bewaa_current_place, address_type);
    jQuery(".bewaa_set_location_btn").replaceWith("<svg aria-hidden=\"true\" focusable=\"false\" data-prefix=\"fas\" data-icon=\"check\" class=\"svg-inline--fa fa-check fa-w-16\" role=\"img\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><path fill=\"currentColor\" d=\"M173.898 439.404l-166.4-166.4c-9.997-9.997-9.997-26.206 0-36.204l36.203-36.204c9.997-9.998 26.207-9.998 36.204 0L192 312.69 432.095 72.596c9.997-9.997 26.207-9.997 36.204 0l36.203 36.204c9.997 9.997 9.997 26.206 0 36.204l-294.4 294.401c-9.998 9.997-26.207 9.997-36.204-.001z\"></path></svg>" + "<span class=\"bewaa_marker_text\">" + bewaa_autocomplete.bewaa_location_selected_text + "</span>");
}


function bewaa_fillInAddress(bewaa_place, address_type) {
    bewaa_console_log("bewaa_fillInAddress " + address_type);


    if (address_type == "both") {
        bewaa_fillInAddress(bewaa_place, 'billing');
        bewaa_fillInAddress(bewaa_place, 'shipping');
        return true;
    }
    bewaa_console_log(bewaa_place);
    let bewaa_address1 = "";
    let bewaa_postcode = "";
    let bewaa_city = "";
    let bewaa_country = "";
    let bewaa_state = "";
    let bewaa_state_name = "";
    let bewaa_state_found = false;
    for (const component of bewaa_place.address_components) {
        const componentType = component.types[0];
        switch (componentType) {
            case "street_number":
                bewaa_address1 = `${component.long_name} ${bewaa_address1}`;
                break;

            case "route":
                bewaa_address1 += component.short_name;
                break;

            case "postal_code":
                bewaa_postcode = `${component.long_name}${bewaa_postcode}`;
                break;

            case "postal_code_suffix":
                bewaa_postcode = `${bewaa_postcode}-${component.long_name}`;
                break;


            case "political":
            case "administrative_area_level_3":
                if (bewaa_city == '') {
                    bewaa_city = component.long_name;
                }
                break;

            case "sublocality":
            case "sublocality_level_1":
            case "locality":
                bewaa_city = component.long_name;
                break;

            case "administrative_area_level_1":
                bewaa_state = component.short_name;
                bewaa_state_name = component.long_name;
                break;

            case "country":
            case 'administrative_area_level_2':
                bewaa_country = component.short_name;
                break;
        }
    }

    if (bewaa_address1 == '') {
        bewaa_address1 = bewaa_place.formatted_address;
    }

    if (address_type == 'billing') {
		checkout_input_labels();
        jQuery("#billing_address_1").val(bewaa_address1);
        jQuery("#billing_postcode").val(bewaa_postcode);
        jQuery("#billing_city").val(bewaa_city);


        if (jQuery("#billing_state_field").is(":visible")) {
            if (jQuery("select#billing_state").length) {

                bewaa_state_found = false;
                jQuery('#billing_state > option').each(function() {
                    if (jQuery(this).val() == bewaa_state && bewaa_state != "") {
                        jQuery("#billing_state").val(bewaa_state);
                        bewaa_state_found = true;
                    }
                });
                if (bewaa_state_found == false) {
                    jQuery('#billing_state > option').each(function() {
                        if (jQuery(this).text() == bewaa_state_name && bewaa_state_name != "") {
                            jQuery("#billing_state").val(jQuery(this).val());
                            bewaa_state_found = true;
                        }
                    });
                }

            } else {
                jQuery("#billing_state").val(bewaa_state);
            }

        } else {
            jQuery("#billing_state").val("");
        }

        jQuery("#billing_address_2").val("");
        jQuery("#billing_country").val(bewaa_country);
        bewaa_set_location(bewaa_place, address_type);

        jQuery("#billing_country").trigger("change");
    }

    if (address_type == 'shipping') {
        checkout_input_labels();
		jQuery("#shipping_address_1").val(bewaa_address1);
        jQuery("#shipping_postcode").val(bewaa_postcode);
        jQuery("#shipping_city").val(bewaa_city);

        if (jQuery("#shipping_state_field").is(":visible")) {
            if (jQuery("select#shipping_state").length) {

                bewaa_state_found = false;
                jQuery('#shipping_state > option').each(function() {
                    if (jQuery(this).val() == bewaa_state && bewaa_state != "") {
                        jQuery("#shipping_state").val(bewaa_state);
                        bewaa_state_found = true;
                    }
                });
                if (bewaa_state_found == false) {
                    jQuery('#shipping_state > option').each(function() {
                        if (jQuery(this).text() == bewaa_state_name && bewaa_state_name != "") {
                            jQuery("#shipping_state").val(jQuery(this).val());
                            bewaa_state_found = true;
                        }
                    });
                }

            } else {
                jQuery("#shipping_state").val(bewaa_state);
            }

        } else {
            jQuery("#shipping_state").val("");
        }

        jQuery("#shipping_country").val(bewaa_country);

        bewaa_set_location(bewaa_place, address_type);

        jQuery("#shipping_address_2").val("");
        jQuery("#shipping_country").trigger("change");
    }


}


function bewaa_set_location(bewaa_place, address_type) {
    bewaa_console_log("bewaa_set_location " + address_type);


    if (address_type == "both") {
        bewaa_set_location(bewaa_place, 'billing');
        bewaa_set_location(bewaa_place, 'shipping');
        return true;
    }
    bewaa_console_log(bewaa_place);
    bewaa_pos = bewaa_place.geometry.location;
    if (address_type == 'billing') {
        if (bewaa_autocomplete.bewaa_coordinates == '1') {
            jQuery("#bewaa_billing_lat").html(bewaa_pos.lat);
            jQuery("#bewaa_billing_lng").html(bewaa_pos.lng);
            jQuery("#bewaa_billing_lng_input").val(bewaa_pos.lng);
            jQuery("#bewaa_billing_lat_input").val(bewaa_pos.lat);
            jQuery(".woocommerce-billing-fields .bewaa_coordinates").show();
        }
    }

    if (address_type == 'shipping') {
        if (bewaa_autocomplete.bewaa_coordinates == '1') {
            jQuery("#bewaa_shipping_lat").html(bewaa_pos.lat);
            jQuery("#bewaa_shipping_lng").html(bewaa_pos.lng);
            jQuery("#bewaa_shipping_lng_input").val(bewaa_pos.lng);
            jQuery("#bewaa_shipping_lat_input").val(bewaa_pos.lat);
            jQuery(".woocommerce-shipping-fields .bewaa_coordinates").show();
        }
    }
}

function checkout_input_labels() {
						
	setTimeout(function(){
		$('.label-inside-yes input, .label-inside-yes select').each( function () {
					
			var $this = $(this);
				
			if ( this.value != '' ) $this.parents('div.form-row.label-inside-yes').addClass('is-active');
			if ( this.value != '' ) $this.parents('p.form-row.label-inside-yes').addClass('is-active');
		});
			
		$('input, select').each( function () {	
				
			var $this = $(this);
					
			if ( this.value != '' ) $this.parents('div.form-row').addClass('has-value');
			if ( this.value != '' ) $this.parents('p.form-row').addClass('has-value');
					
		});
						
	},200);
			
};
		