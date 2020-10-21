<?php // echo $totalPages;                                                                                                                                                           ?>
<script src="https://api.tplmaps.com/js-api-v2/assets/tplmaps.js?api_key=$2a$10$rdvGTbEIDGCro8DHbHS2guFZWFaq8O9btc4JnkJfT9Y07AOklAOUe"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.5.1/dist/leaflet.css"
      integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
      crossorigin=""/>

<script src="https://unpkg.com/jquery@3.4.1/dist/jquery.min.js"
  integrity="sha384-vk5WoKIaW/vJyUAd9n/wmopsmNhiy+L2Z+SBxGYnUkunIxVxAv/UtMOhba/xskxh"
  crossorigin=""></script>

<script src="https://unpkg.com/leaflet@1.5.1/dist/leaflet.js"
  integrity="sha512-GffPMF3RvMeYyc1LWMHtK8EbPv0iNZ8/oTtHPx9/cc2ILxQ+u905qIwdpULaqDkyBKgOaB57QTMg7ztg8Jm2Og=="
  crossorigin=""></script>

<!-- <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.8.2.js"></script> -->
<script type="text/javascript" language="javascript" src="<?= base_url() ?>assets/js/modernizr.custom.js"></script>
<script src="<?= base_url() ?>assets/js/cbpTooltipMenu.min.js"></script>
<script>
    var menu = new cbpTooltipMenu(document.getElementById('cbp-tm-menu'));
</script>

<!-- <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script> -->
<!-- <script type="text/javascript" src="<?php echo base_url() . 'assets/js/markerclusterer.js'; ?>"></script> -->


<script type="text/javascript">
    var default_image = '<?php echo base_url() . "assets/images/map_pins/default_pin.png"; ?>';
    var map;
    var markers = [];
    var markerClusterer;
    var locations = [<?= $locations ?>];
    var urls = '<?php echo base_url() . "form/getMapPartial"; ?>';
    var urls_edit = '<?php echo base_url() . "form/edit_form_partial_map"; ?>';
    var punjab_districts = '<?php echo base_url() . "assets/kml/punjab_districts.kml"; ?>';
    var kml_path = '<?php echo base_url() . "assets/kml/"; ?>';
    var towns_boundaries;
    var uc_boundaries;


    //    alert(totalRecrods +' -'+totalPages);
    //    var lahore_towns = '<?php // echo base_url() . "assets/kml/lahore_towns.kml";                                                                                                                                                                                                          ?>';
    //    var lahore_uc = '<?php // echo base_url() . "assets/kml/lahore_uc.KML";                                                                                                                                                                                                          ?>';

    //    var lahore_towns = 'http://denguetrackingcedar.herokuapp.com/Lahore_Towns.KML';
    var ctaLayer;
    
    // var infowindow = new google.maps.InfoWindow({
    //     maxWidth: 500
    // });

    $(document).ready(function() {

        $('#overlay_loading').hide();
        
        // jQuery('#more_markers').val('Load More...' + totalPages);
        
        var zoom_level_db = <?php echo (isset($zoom_level) && $zoom_level!='')?$zoom_level:"7"; ?>;
        var latitude_db = <?php echo (isset($latitude) && $latitude!='')?$latitude:"31.58219141239757"; ?>;
        var longitude_db = <?php echo (isset($longitude) && $longitude!='')?$longitude:"73.7677001953125"; ?>;
        
        // var myLatlng = new google.maps.LatLng(latitude_db, longitude_db);
        
        // var options = {
        //     zoom: zoom_level_db,
        //     center: myLatlng,
        //     mapTypeControl: true,
        //     mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        //     navigationControl: true,
        //     navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
        //     mapTypeId: google.maps.MapTypeId.ROADMAP,
        //     disableDoubleClickZoom: false,
        // }
        // map = new google.maps.Map(document.getElementById("map"), options);
        // var infowindow = new google.maps.InfoWindow({
        //     maxWidth: 500
        // });

        // leaflet map 

        map = L.map('map').setView([latitude_db , longitude_db], zoom_level_db);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

//         for (i = 0; i < locations.length; i++) {
// //            alert(locations[i]);
//             companyMarker = new google.maps.Marker({
//                 position: new google.maps.LatLng(locations[i][0], locations[i][1]),
//                 map: map,
//                 title: locations[i][5],
//                 icon: locations[i][3],
//                 date: locations[i][6],
//                 zIndex: 3,
//                 draggable: false,
//             });

//             companyMarker.setValues({
//                 form_result_id: locations[i][4]
//             });

//             google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
//                 return function() {
//                     $.ajax({
//                         url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + locations[i][4] + '&form_id=' + locations[i][2],
//                         success: function(data) {
//                             infowindow.setContent(data);
//                             infowindow.open(map, companyMarker);
//                         }
//                     });
//                 }
//             })(companyMarker, i));
//             markers.push(companyMarker);

//             markers[i].setVisible(false);
//         }
        
        // leaflet markers
        for (i = 0; i < locations.length; i++) {
//            alert(locations[i]);
            var myIcon = L.icon({
                iconUrl: locations[i][3],
                iconSize: [29, 24],
                iconAnchor: [9, 21],
                popupAnchor: [0, -14],
                shadowSize : [0, 0]
            });
            
            var latlng = L.latLng(locations[i][0], locations[i][1]);
            var contentString = 'Sample info window content';
            L.marker(latlng , {icon: myIcon}).addTo(map)
                    .bindPopup(contentString).openPopup();

            // companyMarker = new google.maps.Marker({
            //     position: new google.maps.LatLng(locations[i][0], locations[i][1]),
            //     map: map,
            //     title: locations[i][5],
            //     icon: locations[i][3],
            //     date: locations[i][6],
            //     zIndex: 3,
            //     draggable: false,
            // });


            // companyMarker.setValues({
            //     form_result_id: locations[i][4]
            // });

            // google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
            //     return function() {
            //         $.ajax({
            //             url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + locations[i][4] + '&form_id=' + locations[i][2],
            //             success: function(data) {
            //                 infowindow.setContent(data);
            //                 infowindow.open(map, companyMarker);
            //             }
            //         });
            //     }
            // })(companyMarker, i));


            // markers.push(companyMarker);

            // markers[i].setVisible(false);
        }
        map.panTo(myLatlng);

        /**
         * handling town wise map setting static
         * will be dynamic later

         * @type String */
        var district_selected = '<?php echo $district_selected; ?>';
        var town_selected = '<?php echo (!empty($town_filter_selected)) ? $town_filter_selected : ""; ?>';
        if (town_selected != "" && town_selected == 'Aziz Bhati') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'DGBT') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Gulberg') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Iqbal') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Nishter') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Ravi') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Samanabad') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Wahga') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Shalimar') {
            map.setCenter(new google.maps.LatLng(locations[1][0], locations[1][1]));
            map.setZoom(12);
        } else {
            map.setCenter(new google.maps.LatLng(latitude_db, longitude_db));
            map.setZoom(zoom_level_db);
        }
        //        $town_filter_selected = (!empty($town_filter_selected)) ? $town_filter_selected : "";
        var view_type = '<?php echo $view_type; ?>';
        var map_type_filter = '<?php echo $map_type_filter; ?>';


        //        if (district_selected == "") {
        //            map.setCenter(new google.maps.LatLng(latitude_db, longitude_db));
        //            map.setZoom(zoom_level_db);
        //        } else {
        //            $('#disricts option[value="' + district_selected + '"]').prop('selected', true);
        //            var array = district_selected.split(',');
        //            map.setCenter(new google.maps.LatLng(array[0], array[1]));
        //            map.setZoom(10);
        //        }
        if (map_type_filter) {
            if (view_type == 'Pin') {
                $('#view_type option[value="' + view_type + '"]').prop('selected', true);
            } else {
                $('#view_type option[value="' + view_type + '"]').prop('selected', true);
                jQuery('#check_all').trigger('click');
                $('input.category').prop('checked', 'checked');
                jQuery('#counter_div').text(markers.length);
                clusterView(1);
            }
        }


//        google.maps.event.addListener(map, 'dblclick', function(event) {
//            placeMarker(event.latLng);
//        });
        ctaLayer = new google.maps.KmlLayer({
            url: punjab_districts,
            preserveViewport: true
        });
        ctaLayer.setMap(map);


        var boundaries = '<?php echo $boundaries; ?>';
        <?php if ($app_id == '3883' || $app_id == '3882') { ?>
	        $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
	        var kml_name = '2015_cumulative_Flood_Indus_16Jul-13Aug.kml';
	        var final_link = kml_path + kml_name;

	        towns_boundaries = new google.maps.KmlLayer({
	            url: final_link,
	            preserveViewport: true
	        });
	        towns_boundaries.setMap(map);
	   <?php } ?>
        <?php if ($app_id == '4631') { ?>
            $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
            var kml_name = 'NA122UCboundary.kml';
            var final_link = kml_path + kml_name;

            towns_boundaries = new google.maps.KmlLayer({
                url: final_link,
                preserveViewport: true
            });
            towns_boundaries.setMap(map);
       <?php } ?>
       <?php if ($app_id == '10665') { ?>
	        $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
	        var kml_name = 'Toba_Tek_Singh_Uc_Boundary.kml.kmz';
	        var final_link = kml_path + kml_name;

	        towns_boundaries = new google.maps.KmlLayer({
	            url: final_link,
	            preserveViewport: true
	        });
	        towns_boundaries.setMap(map);
	   <?php } ?>
        <?php if ($app_id == '9926') { ?>
	        $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
	        var kml_name = 'NA-120.kml';
	        var final_link = kml_path + kml_name;

	        towns_boundaries = new google.maps.KmlLayer({
	            url: final_link,
	            preserveViewport: true
	        });
	        towns_boundaries.setMap(map);

                var kml_name = 'UCsofNA-120boundarycolorgreytransparency60.kml';
	        var final_link = kml_path + kml_name;

	        towns_boundaries = new google.maps.KmlLayer({
	            url: final_link,
	            preserveViewport: true
	        });
	        towns_boundaries.setMap(map);
	   <?php } ?>
        var district = 'lahore';
        //        var district = $('#disricts :selected').text();
        if (boundaries == 'Towns') {
            $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
            var kml_name = district.toLowerCase() + '_' + boundaries.toLowerCase() + '.kml';
            var final_link = kml_path + kml_name;

            towns_boundaries = new google.maps.KmlLayer({
                url: final_link,
                preserveViewport: true
            });
            towns_boundaries.setMap(map);
            if (typeof uc_boundaries != 'undefined') {
                // variable is undefined
                uc_boundaries.setMap(null);

            }
            ctaLayer.setMap(null);

        }
        if (boundaries == 'UC') {
            $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
            var kml_name = district.toLowerCase() + '_' + boundaries.toLowerCase() + '.KML';
            var final_link = kml_path + kml_name;

            uc_boundaries = new google.maps.KmlLayer({
                url: final_link,
                preserveViewport: true
            });
            uc_boundaries.setMap(map);
            if (typeof towns_boundaries != 'undefined') {
                // variable is undefined
                towns_boundaries.setMap(null);

            }
            ctaLayer.setMap(null);

        }
        if (boundaries == 'Districts') {
            $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
            if (typeof towns_boundaries != 'undefined') {
                // variable is undefined
                towns_boundaries.setMap(null);
            }
            if (typeof uc_boundaries != 'undefined') {
                // variable is undefined
                uc_boundaries.setMap(null);

            }

            ctaLayer.setMap(map);

        }



    });
    //Clear all cluster with markers
    function clearOverlay() {

        var i = 0,
                l = markers.length;
        for (i; i < l; i++) {
            markers[i].setMap(null)
        }
        markers = [];
        // Clears all clusters and markers from the clusterer.
        markerClusterer.clearMarkers();
    }
    //in clear cluster but markers not null
    function clearClusterWithMarkers() {

        var i = 0,
                l = markers.length;
        for (i; i < l; i++) {
            markers[i].setMap(null)
        }
//        markers = [];
        // Clears all clusters and markers from the clusterer.
        markerClusterer.clearMarkers();
    }
    //in case of town and uc changes
    function clearOverlayCluster() {

        var i = 0,
                l = markers.length;
        for (i; i < l; i++) {
            markers[i].setMap(null)
        }
        markers = [];
        // Clears all clusters and markers from the clusterer.
//        markerClusterer.clearMarkers();
    }

    var placeMarkers = [];
    function placeMarker(location) {
        var image = default_image;
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: 'Newly Added Marker',
            icon: image,
        });
        placeMarkers.push(marker);
        var infowindow = new google.maps.InfoWindow({
            //content: 'Latitude: ' + location.lat() + '<br>Longitude: ' + location.lng()
            content: '<b>Do You Want to Add Marker at this Location</b></br><form accept-charset="utf-8" method="post" action="" id="add_info_form">\n\
            <input type="hidden" name="lat" value="' + location.lat() + '"style="float:right">\n\
            Latitute : <label for="lat">' + location.lat() + '</label><br>\n\
            Longitude : <label for="long">' + location.lng() + '</label>   \n\
            <input type="hidden" name="long" value="' + location.lng() + '"style="float:right">\n\
            <a href=' + urls + ' class="data_colorbox" title="New Data Form " name = "Pakistan">\n\
            <input type="button" name="add" value="Add Data" id="save_data" style="float:right"></a></form>'
        });
        infowindow.open(map, marker);
    }
    /*
     *
     * @returns {null}
     * clear temprary markers pushed by place marker method
     */

    function clearTempMarkers() {

        var i = 0,
                l = placeMarkers.length;
        for (i; i < l; i++) {
            placeMarkers[i].setMap(null)
        }
        placeMarkers = [];

    }

    /**
     * Clear overlays
     */
    function clearPins() {

        for (var i = 0; i < locations.length; i++) {
            markers[i].setVisible(false);
        }
        $('#overlay_loading').hide();

    }


    //    //Switch to cluster view and heat map view 0 for pin and 1 for heatmap
    function clusterView(viewType) {
        if (viewType == 0) {

            clearOverlay();
            $.each(locations, function(i) {
                companyMarker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                    map: map,
                    title: locations[i][5],
                    icon: locations[i][3],
                    zIndex: 3,
                    draggable: true,
                });
                markers.push(companyMarker);
                var contentString = '<div id="info_window"><div class="success"></div><div id="content">' + locations[i][2] + '\n\
            <a href=' + urls_edit + ' class="edit_color_box" title="Edit Form " name = "Pakistan" form_result_id=' + locations[i][4] + '>\n\
            </a>\n\
            </div><div id="edit_info" style="display:none">' + locations[i][4] + '</div></div>';
                google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, contentString) {
                    return function() {
                        infowindow.setContent(contentString);
                        infowindow.open(map, companyMarker);
                    }
                })(companyMarker, contentString));
            });
        } else {
            var mcOptions = {gridSize: 50, maxZoom: 13};
            markerClusterer = new MarkerClusterer(map, markers, mcOptions);
        }
        $('#overlay_loading').hide();

    }
    //ajax based loading load more
    var companyMarker, i;
    //    var markers = [];
    /**
     *
     * @type String
     * for pagination system
     */
    var totalPages = '<?php echo $totalPages - 1; ?>';
    var page = 2;
    var values = [];

    //add markers
    function addMarker() {

        $('input:checked.category').each(function() {
            values.push($(this).val());
        });
        //$('#overlay_loading').show();

        var selected_form_id = '<?php echo $form_id; ?>';

        var filter_date_to = jQuery('#datepicker').val();
        var filter_date_from = jQuery('#datepicker2').val();
        var town_filter_exist = $('#town_filter :selected').val();
        if (typeof town_filter_exist != 'undefined') {
            var town_filter = $('#town_filter :selected').val();
        }
        else {
            var town_filter = "";
        }
        var temp = jQuery('#more_markers').val().split("...")
        var page = temp[1];
        var datum = 'selected_form_id=' + selected_form_id + '&filter_date_to=' + filter_date_to + '&page=' + page + '& filter_date_from=' + filter_date_from + '& town_filter=' + town_filter;
        $.ajax({
            dataType: 'json',
            url: "<?= base_url() . 'form/moreMarker' ?>",
            data: datum,
            success: function(resp) {
                var counter = 0;
                var selected_view = $("#view_type option:selected").val();
                if (selected_view == 'Heat') {

                    $.each(resp, function(i) {

                        companyMarker = new google.maps.Marker({
                            position: new google.maps.LatLng(resp[i].long, resp[i].lat),
                            map: map,
                            title: resp[i].category_name,
                            icon: resp[i].icon_filename,
                            zIndex: 3,
                            draggable: false,
                        });
                        companyMarker.setValues({
                            form_result_id: resp[i].form_result_id
                        });

                        google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                            return function() {
                                $.ajax({
                                    url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + resp[i].form_result_id + '&form_id=' + resp[i].form_id,
                                    success: function(data) {
                                        infowindow.setContent(data);
                                        infowindow.open(map, companyMarker);
                                    }
                                });
                            }
                        })(companyMarker, i));
                        markers.push(companyMarker);
                        markerClusterer.addMarker(companyMarker, false);

                    });
                    if (!$('#check_all').is(':checked')) {
                        jQuery('#check_all').trigger('click')
                    }
                    $('#overlay_loading').hide();
                    jQuery('#counter_div').text(markers.length);
                } else {

                    $.each(resp, function(i) {

                        companyMarker = new google.maps.Marker({
                            position: new google.maps.LatLng(resp[i].long, resp[i].lat),
                            map: map,
                            title: resp[i].category_name,
                            icon: resp[i].icon_filename,
                            zIndex: 3,
                            draggable: false,
                        });
                        companyMarker.setValues({
                            form_result_id: resp[i].form_result_id
                        });

                        google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                            return function() {
                                $.ajax({
                                    url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + resp[i].form_result_id + '&form_id=' + resp[i].form_id,
                                    success: function(data) {
                                        infowindow.setContent(data);
                                        infowindow.open(map, companyMarker);
                                    }
                                });
                            }
                        })(companyMarker, i));
                        markers.push(companyMarker);
                        if ($.inArray(resp[i].category_name, values) != -1) {
                            companyMarker.setVisible(true);
                            counter = counter + 1;
                        } else {
                            companyMarker.setVisible(false);
                        }
                    });

                    var before_counter = jQuery('#counter_div').text();

                    jQuery('#counter_div').text(+before_counter + +counter);

                }
                totalPages--;
                page++;
                jQuery('#more_markers').val('Load More...' + totalPages);
                if (totalPages == 0) {
                    $('#more_markers').hide();
                }
                $('#overlay_loading').hide();
            }
        });
        //        var mcOptions = {gridSize: 50, maxZoom: 13};
        //        var markerCluster = new MarkerClusterer(map, markers, mcOptions);

    }

    var lhrKml;
    var lhrUc;
    function change_center() {

        var e = document.getElementById("disricts");
        var latLong = e.options[e.selectedIndex].value;
        var district = $('#disricts :selected').text();
        if (typeof uc_boundaries != 'undefined') {
            // variable is undefined
            uc_boundaries.setMap(null);

        }
        if (typeof towns_boundaries != 'undefined') {
            // variable is undefined
            towns_boundaries.setMap(null);

        }
        if (district == "Lahore") {
            //$('#boundaries :selected').text('Towns');
            //$('#boundaries :selected').val('Towns');
            //            $("#boundaries").val('Towns').trigger('change');
            //            $("#boundaries").val("Towns").trigger("change");
            //            $("#boundaries").change(function() {
            //                alert("Changed!");
            //            });
            //            lhrKml = new google.maps.KmlLayer({
            //                url: lahore_towns
            //            });
            //            lhrKml.setMap(map);
            //            lhrUc = new google.maps.KmlLayer({
            //                url: lahore_uc
            //            });
            //            lhrUc.setMap(map);
            //            ctaLayer.setMap(null);
        } else {
            ctaLayer.setMap(map);
            if (typeof lhrKml != 'undefined') {
                // variable is undefined
                lhrKml.setMap(null);
                lhrUc.setMap(null);
            }
        }
        if (latLong == "") {
            map.setCenter(new google.maps.LatLng(31.834632561515555, 71.6473388671875));
            map.setZoom(7);
        } else {
            var array = latLong.split(',');
            map.setCenter(new google.maps.LatLng(array[0], array[1]));
            map.setZoom(10);
        }
    }
    /**
     *
     * @param {type} markerCategory
     * @returns {undefined}
     * change  boundries based on object
     */

    function change_boundries(obj) {
        var district = 'lahore';
        //        var district = $('#disricts :selected').text().toLowerCase();
        var boundaries = $('#boundaries :selected').text().toLowerCase();
        if (boundaries == 'towns') {
            var kml_name = district + '_' + boundaries + '.kml';
            var final_link = kml_path + kml_name;

            towns_boundaries = new google.maps.KmlLayer({
                url: final_link,
                preserveViewport: true
            });
            towns_boundaries.setMap(map);
            if (typeof uc_boundaries != 'undefined') {
                // variable is undefined
                uc_boundaries.setMap(null);

            }
            ctaLayer.setMap(null);

        } else if (boundaries == 'uc') {
            var kml_name = district + '_' + boundaries + '.KML';
            var final_link = kml_path + kml_name;

            uc_boundaries = new google.maps.KmlLayer({
                url: final_link,
                preserveViewport: true
            });
            uc_boundaries.setMap(map);

            if (typeof towns_boundaries != 'undefined') {
                // variable is undefined
                towns_boundaries.setMap(null);

            }
            ctaLayer.setMap(null);

        } else {
            if (typeof towns_boundaries != 'undefined') {
                // variable is undefined
                towns_boundaries.setMap(null);
            }
            if (typeof uc_boundaries != 'undefined') {
                // variable is undefid
                uc_boundaries.setMap(null);

            }

            ctaLayer.setMap(map);
        }

    }


    function toggleMarkersHide(markerCategory) {

        if (markerCategory == 'all') {
            for (var i = 0; i < locations.length; i++) {
                markers[i].setVisible(true);
            }
        } else {
            for (var i = 0; i < locations.length; i++) {
                if (locations[i][5] == markerCategory) {

                    markers[i].setVisible(false);
                }
            }
        }
        $('#overlay_loading').hide();
    }

    function toggleMarkersShow(markerCategory) {
        var counter = 0;
        for (var i = 0; i < locations.length; i++) {

            if (locations[i][5] == markerCategory) {

                markers[i].setVisible(true);
                counter = counter + 1;
            }
            $('.overlay').toggle();
        }

        if (counter <= 1) {
            $('.overlay .message').text(counter + ' Record(s) ');
        } else {
            $('.overlay .message').text(counter + ' Record(s) ');
        }

        setTimeout(function() {

            $('.overlay').fadeOut();
        }, 2000);
        $('#overlay_loading').hide();
    }


    /*
     * Loading wait status for map
     */
    // loading_image();
    function loading_image() {
        $(function() {

            var docHeight = $(document).height();
            $("body").append('<div  id="overlay_loading" title="Please Wait while the map loads">\n\
            <img  alt=""  \n\
            src="<?php echo base_url() . 'assets/images/loading_map.gif'; ?>">\n\
            < /div>');

            $("#overlay_loading")
                    .height(docHeight)
                    .css({
                        'opacity': 0.16,
                        'position': 'absolute',
                        'top': 0,
                        'left': 0,
                        'background-color': 'black',
                        'width': '100%',
                        'z-index': 5000
                    });
        });
    }
    jQuery('#town_filter').live('change', function() {
        var boundaries = 'UC';
        $('#boundaries option[value="' + boundaries + '"]').prop('selected', true);
        var kml_name = 'lahore_' + boundaries.toLowerCase() + '.KML';
        var final_link = kml_path + kml_name;

        uc_boundaries = new google.maps.KmlLayer({
            url: final_link,
            preserveViewport: true
        });
        uc_boundaries.setMap(map);
        if (typeof towns_boundaries != 'undefined') {
            // variable is undefined
            towns_boundaries.setMap(null);

        }
        ctaLayer.setMap(null);

        var town_selected = jQuery('#town_filter :selected').text();
        if (town_selected != "" && town_selected == 'Aziz Bhati') {
            map.setCenter(new google.maps.LatLng(31.556713519304022, 74.42258834838867));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'DGBT') {
            map.setCenter(new google.maps.LatLng(31.627484485381746, 74.30482864379883));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Gulberg') {
            map.setCenter(new google.maps.LatLng(31.512234858378786, 74.33950424194336));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Iqbal') {
            map.setCenter(new google.maps.LatLng(31.475933947640083, 74.26088333129883));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Nishter') {
            map.setCenter(new google.maps.LatLng(31.420284260012796, 74.31375503540039));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Ravi') {
            map.setCenter(new google.maps.LatLng(31.626022819361406, 74.3034553527832));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Samanabad') {
            map.setCenter(new google.maps.LatLng(31.530965298007132, 74.28834915161133));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Wahga') {
            map.setCenter(new google.maps.LatLng(31.603802668907534, 74.43323135375977));
            map.setZoom(12);
        } else if (town_selected != "" && town_selected == 'Shalimar') {
            map.setCenter(new google.maps.LatLng(31.593860358147953, 74.37761306762695));
            map.setZoom(12);
        } else {
            map.setCenter(new google.maps.LatLng(latitude_db, longitude_db));
            map.setZoom(zoom_level_db);
        }
    });

</script>

<style>
    .filter-div-map{
        margin:6px 5px 0 0;
        float : left;
    }
</style>
<div id="form_descrip" style="display:none;">
    <?php
    foreach ($form_html as $form_preview) {
        echo $form_preview;
    }
    ?>
</div>
<div id="container">
    <div class="inner-wrap" style="z-index:999; position: relative;">

        <!--<input onclick="clearOverlay()" type=button value="Hide Markers">-->
        <!--<input id="change_cent" onclick="change_center();" type=button value="Change Center">-->

        <!--Filter Part on based of category-->
        <div class="success"></div>
        <?php
        $apps_name = preg_replace('/[^A-Za-z0-9]/', '-', $app_name);

        $slug = $apps_name . '-' . $app_id;
        ?>
        <div class="filter_class" style="height:auto; padding-bottom:8px;">



            <?php
            echo '<div style="width:143px;margin:5px 0 0 42px; position:fixed" title="' . $app_name . '">';
            echo '</div>';
            $this->load->helper(array('form'));

            echo form_open(base_url() . 'application-map/' . $slug, 'id=date_filter_form name=date_filter_form');

            if ($uc_filter == 1) {
                if ($town) {
                    ?>
                    <div class="filter-div-map">
                        &nbsp;Town :
                        <select  name="town_name" id="town" onchange="getTownBasedUc(this)"  style="width:81px;">
                            <option selected="selected" value="">Select Default</option>
                            <?php
                            foreach ($town as $town_name) {
                                ?>
                                <option value="<?php echo strip_tags($town_name); ?>"><?php echo $town_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                if ($uc) {
                    ?>
                    <div class="filter-div-map">
                        &nbsp;UC :
                        <select  name="uc_name" id="uc" onchange=""  style="width:81px;">
                            <option selected="selected" value="">Select Default</option>
                            <?php
                            foreach ($uc as $uc_name) {
                                ?>
                                <option value="<?php echo strip_tags($uc_name); ?>"><?php echo $uc_name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                }
                ?>
                <div id="boundaries" class="filter-div-map">
                    Boundaries :
                    <select name="boundaries" id='boundaries' onchange="change_boundries(this);" >

                        <option value="Districts">Districts</option>
                        <option value="Towns">Towns</option>
                        <option value="UC">UC</option>
                    </select>
                </div>
                <?php
            }
            if ($district_filter == 1) {
                ?>
                <div class="filter-div-map">
                    Districts :
                    <select  name="district_select" id="disricts" onchange="change_center(this);" >
                        <option selected="selected" value="">Select Default</option>
                        <option value="33.7489646284842,72.37518310546875">Attock</option>
                        <option value="29.996910,73.251167">Bahawalnagar</option>
                        <option value="29.264118038909746,71.71119689941406">Bahawalpur</option>
                        <option value="31.6230,71.0626">Bhakkar</option>
                        <option value="32.9196915914051,72.81600952148438">Chakwal</option>
                        <option value="31.7200,72.9800">Chiniot</option>
                        <option value="30.04717901252635,70.63385009765625">Dera Ghazi Khan</option>
                        <option value="31.4180,73.0790">Faisalabad</option>
                        <option value="32.17026536253259,74.18449401855469">Gujranwala</option>
                        <option value="32.5411696052858,74.0423583984375">Gujrat</option>
                        <option value="32.0700,73.6800">Hafizabad</option>
                        <option value="31.2630348995156,72.30514526367188">Jhang</option>
                        <option value="32.9333,73.7333">Jhelum</option>
                        <option value="31.1173615359634,74.46670532226562">Kasur</option>
                        <option value="30.3100,71.8200">Khanewal</option>
                        <option value="32.3121622743738,72.35870361328125">Khushab</option>
                        <option value="31.451920768643237,74.2976188659668">Lahore</option>
                        <option value="30.9602,70.9423">Layyah</option>
                        <option value="29.5400,71.6300">Lodhran</option>
                        <option value="32.5833,73.5000">Mandi Bahauddin</option>
                        <option value="32.5689498997008,71.5484619140625">Mianwali</option>
                        <option value="30.1978,71.4697">Multan</option>
                        <option value="30.0703,71.1933">Muzaffargarh</option>
                        <option value="31.4496,73.7065">Nankana Sahib</option>
                        <option value="32.1030074169987,74.87457275390625">Narowal</option>
                        <option value="30.8014,73.4483">Okara</option>
                        <option value="30.3000,73.2667">Pakpattan</option>
                        <option value="28.4200,70.3000">Rahim Yar Khan</option>
                        <option value="29.1046,70.3257">Rajanpur</option>
                        <option value="33.5926369799151,73.06045532226562">Rawalpindi</option>
                        <option value="30.5833,73.3333">Sahiwal</option>
                        <option value="32.1667,72.5000">Sargodha</option>
                        <option value="31.7200,73.9800">Sheikhupura</option>
                        <option value="32.5200,74.5500">Sialkot</option>
                        <option value="30.978080051588822,72.49259948730469">Toba Tek Singh</option>
                        <option value="29.9872038450137,72.3614501953125">Vehari</option>

                    </select>
                </div>
            <?php }

            if ($sent_by_filter == 1) {
            ?>
            <div class="filter-div-map">
                Sent By:
                <select  name="sent_by_list" style="max-width: 127px" id="sent_by_map" class="multiselect"  >
                                    <option selected="selected" value="">Sent By</option>
                    <?php
                    //                print_r($sent_by_list);die;
                    foreach ($sent_by_list as $sent_by) {
                        if (isset($sent_by['imei_no']) && $sent_by['imei_no'] != '') {
                            $select = '';
//                        if (strip_tags($sent_by['imei_no']) == $selected_sent_by) {
                            if(in_array($sent_by['imei_no'],$selected_sent_by)){
                                $select = 'selected';
                            }
                            ?>
                            <option <?php echo $select; ?> value="<?php echo strip_tags($sent_by['imei_no']); ?>"><?php echo $sent_by['name']; ?></option>
                        <?php
                        }
                    }
                    ?>
                </select>
                </div>
            <?php
            }

            ?>

            <?php
            echo '<div class="form_class" style="float: left">';
            if (count($form_lists) > 1) {
                echo 'Forms ';
                echo ': ' . form_dropdown('form_list[]', $form_lists, $form_list_selected, 'id="form_list" class="form_list"');
                echo '';
            } else {
                echo '<input type="hidden" value="' . $form_id . '" name="form_list[]">';
            }
            echo '</div>';

            //fOR multiple filters handling
            $selected_final = "";
            foreach ($app_filters_array as $key => $filters) {
                foreach ($selected_filters as $selected_key => $selected) {
                    if ($key == $selected_key) {
                        $selected_final = $selected;
                    }
                }


                echo '<div class="form_class" style="float: left">';

                echo '&nbsp;' . str_replace('_', ' ', $key) . ' : ';
                echo '<select id=' . $key . ' name =' . $key . '[] class="filter_list" multiple="multiple" rel = ' . $key . '>';
                foreach ($filters as $category => $value) {
                    $category = (strlen($value) > 23) ? substr($value, 0, 23) . ' ...' : $value;
                    if ($selected_final) {
                        if (in_array($value, $selected_final)) {
                            echo '<option value="' . $value . '" selected="selected" >' . $category . '</option>';
                        } else {
                            echo '<option value="' . $value . '" >' . $category . '</option>';
                        }
                    } else {
                        echo '<option value="' . $value . '" >' . $category . '</option>';
                    }
                }
//                echo ':' . form_dropdown($key . '[]', $filters, $selected_final, 'id="' . $key . '" class="filter_list" multiple="multiple" rel="' . $key . '"');
                echo '<select>';

                echo '</div>';
            }
            //multiple filters ends hree

            if ($map_type_filter) {
                ?>

                <div id="map_type" style="float:left;margin-left:5px">
                    Map Type :
                    <select name="view_type" id='view_type' >

                        <option value="Heat">Heat Map</option>
                        <option value="Pin">Pin Map</option>
                    </select>
                </div>
                <?php
            }
            ?>


            <div class="filter-div-map" style="margin-left:2px;">
                <input type="hidden" value="<?php echo $form_id; ?>" name="form_id">
                <input type="hidden" value="" name="changed_category" id="changed_category">
                <input type="hidden" value="0" name="all_visits_hidden">
                From :
                <input type="text" size="8" id="datepicker" value="<?php echo!empty($selected_date_to) ? $selected_date_to : ''; ?>" name="filter_date_to" onchange="check_date_validity()" ondblclick="clear_field(this)">
                To :
                <input type="text" size="8" id="datepicker2" value="<?php echo!empty($selected_date_from) ? $selected_date_from : ''; ?>" name="filter_date_from" onchange="check_date_validity()" ondblclick="clear_field(this)">
                <!--                Search :
                                <input size="20" type="text" id="search_text" value="<?php echo!empty($search_text) ? $search_text : ""; ?>" title="<?php // echo!empty($search_text) ? $search_text : "";                                                                                                ?>" name="search_text" placeholder='Type your search....' ondblclick="clear_field(this)">-->


                <?php

//                foreach($possible_filters_from_settings as $key=>$filters){
//                    ?>
<!---->
<!--                    <label>--><?php //echo ucwords(trim(preg_replace('/[^A-Za-z0-9\-]/', ' ', urldecode($key)))); ?><!--</label> :-->
<!--                    <select  name ="dynamic_filters[--><?php //echo $key; ?><!--][]" class="filter_list_mapview" multiple="multiple" >-->
<!--                        --><?php
//                        foreach($filters as $key1=>$val){
//                            $selected_filter=$dynamic_filters[$key];
//
//                            if(in_array($val,$selected_filter)){
//                                $selected="selected";
//                            }else{
//                                $selected='';
//                            }
//                            echo $val;
//                            if($key=="sent_by"){
//                                if(in_array($key1,$selected_filter)){
//                                    $selected="selected";
//                                }else{
//                                    $selected='';
//                                }
//                                ?>
<!--                                <option --><?php //echo $selected; ?><!-- value="--><?php //echo $key1; ?><!--">--><?php //echo $val; ?><!--</option>-->
<!--                            --><?php
//                            }else {
//                                ?>
<!--                                <option --><?php //echo $selected; ?><!-- value="--><?php //echo $val; ?><!--">--><?php //echo $val; ?><!--</option>-->
<!--                            --><?php
//                            }
//                            ?>
<!--                        --><?php
//                        }
//                        ?>
<!--                    </select>-->
<!---->
<!--                --><?php
//                }
                ?>


                <input type="submit" value="Filter" id="filter_submit">
                <input type="button" value="Add/Remove Filters" id="filter_reset" class="open_settings">
                <input type="hidden" value="t-4" id="open_settings" class="open_settings">

            </div>

            <?php echo form_close(); ?>
            <!--Filter Ends here-->
            <div  style="float:right; margin: 0 10px 0 0;">
                <span id="counter_div" >0
                    <?php // echo count($form);       ?>
                </span>
                Record(s)
            </div>
            <div  style="float:left; margin-left: 1px;">
                <?php if ($totalPages > 1) { ?>
                    <input type="button" id='more_markers' value='Load More...' onclick='addMarker()'>
                <?php } ?>
            </div>
            <div class="overlay" style="display:none">
                <div class="message">

                </div>
            </div>

        </div>

        <div id="map" style="height:583px;">
            <!--Herer is the map to ber-->
        </div>
        <a style="position: fixed; margin-top: 210px; top: 4px; right: 0px;" class="show_hide" href="javascript:void(0)">
            <img alt="" src="<?php echo base_url() . '/assets/images/show_categories.png'; ?>" height="368px" width="19px">
        </a>
        <div class="CrimeList">
            <div class="crimeListTexta" style="width:auto;position: absolute;top: -55px;">
                <form id='setfilter' method='POST' action='<?= base_url() ?>form/changefilter/<?php echo $form_id ?>' style="background-color: #DDDDDD;height: 14px;padding: 6px 0 9px;">
                    <input name="redirect_to" value="mapview" type="hidden" />
                    <font color="#0E76BD" style="font-size: 15px; font-weight: bold;">&nbsp;Select Category</font>
                    <select class="required customSelect" name="filter" id="filter" style="width:188px" onChange="$('#overlay_loading').show();
                            filter_update('<?php echo $app_id ?>', $(this).val())"/>
                    <?php echo $filter_options; ?>
                    </select>


                </form>
            </div>
            <div>
                <?php echo form_open("application-map/" . $form_id, array('id' => 'checkbox_filter_form', 'name' => 'cate_check_filter')); ?>
                <div style="float: left; width: 100%; background-color: #0E76BD; position: fixed; margin-bottom: 30px; margin-left: 7px;">
                    <p style="height: 30px;line-height: 25px;">
                        <input type="checkbox" style="margin-top: 8px;"  id="check_all">
                        <label>
                            <b style="font-size: 13px; color: white;">All Categories</b>
                        </label>
                    </p>
                </div>

                <div class="crimeListText">


                    <?php
                    $only_once_category = array();

                    $column_number = 0;
                    $searched_filter_attribute = array();
                    foreach ($filter_attribute as $filter_attribute_value) {
                        if (!in_array($filter_attribute_value, $searched_filter_attribute)) {
                            foreach ($form_for_filter as $form_item) {
                                if (isset($form_item[$filter_attribute_value])) {
                                    if (isset($form_item[$filter_attribute_value]) && !empty($form_item[$filter_attribute_value])) {
                                        $category = explode(" ", $form_item[$filter_attribute_value]);
                                        $category = strtolower($category[0]);

                                        if (!in_array($form_item[$filter_attribute_value], $only_once_category)) {
                                            $column_number++;
                                            $only_once_category[] = $form_item[$filter_attribute_value];


                                            if (!file_exists(FCPATH . "assets/images/map_pins/" . $form_item['pin'])) {

                                                $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                                            } else {
                                                $icon_filename_cat = base_url() . "assets/images/map_pins/" . $form_item['pin'];
                                            }
                                            ?>
                                            <div style="float: left; width: 100%; margin-top: 2px;">
                                                <p><?php
                                                    if (isset($selected_check_boxes)) {
                                                        if (in_array($form_item[$filter_attribute_value], $selected_check_boxes)) {
                                                            ?>
                                                            <input type="checkbox" id="filter_check" checked="checked" class="filter checkbox category public" name="check_box_filter[]" value="<?= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$filter_attribute_value]); ?>">
                                                        <?php } else { ?>
                                                            <input type="checkbox" id="filter_check"  class="filter checkbox category public" name="check_box_filter[]" value="<?= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$filter_attribute_value]); ?>">
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>
                                                        <input type="checkbox" id="filter_check"  class="filter checkbox category public" name="check_box_filter[]" value="<?= preg_replace('/[^a-zA-Z0-9_ \[\]\.\-]/s', '', $form_item[$filter_attribute_value]); ?>"><?php
                                                    }
                                                    $value_attribute = html_entity_decode($form_item[$filter_attribute_value]);
                                                    $value_attribute = str_replace('&', 'and', $value_attribute);
                                                    ?>

                                                    <label class="category" title="<?php echo $value_attribute; ?>"><?php
                                                        echo (strlen($value_attribute) > 18) ? substr($value_attribute, 0, 18) . ' ...' : $value_attribute;
                                                        ?></label>
                                                    <img width="12px" src="<?php echo $icon_filename_cat; ?>">
                                                </p>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            }
                            $searched_filter_attribute[] = $filter_attribute_value;
                        }
                    }
                    $icon_filename_cat = base_url() . "assets/images/map_pins/default_pin.png";
                    ?>


                </div>

                <?php echo form_hidden('form_id', $form_id); ?>

                <div style="padding: 2px 3px;" class="dropDownDiv">
                    <div style="position: fixed; margin: 211px 20px 0 0; top: 4px; right: 0px;" class="hide_category" >
                        <img alt="" src="<?php echo base_url() . '/assets/images/minimize_category.png'; ?>" height="28px" width="34px">
                    </div>
                    <div class="chosen-container chosen-container-multi" style="width: 207px;" title="">
                        <?php // echo form_button('Submit', 'Clear Map', 'id=submit_button');              ?>
                    </div>
                </div>
                <?php echo form_close(); ?>
                <a style="position: absolute; top: 4px;" class="float_left show_or_hide" href="#">
                    <img alt="" src="<?php base_url() . "assets/images/map_pins/default_pin.png"; ?>">
                </a>
            </div>
        </div>

    </div>
    <div style="position: absolute; bottom: 15px;margin-left:28%;z-index: 999;font-weight: bold;"><?php echo FOOTER_TEXT;?></div>
</div>



<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<script>     jQuery(function() {
                            jQuery("#datepicker").datepicker({dateFormat: 'yy-mm-dd'});
                            jQuery("#datepicker2").datepicker({dateFormat: 'yy-mm-dd'});
                        });
                        $('#overlay_loading').show();
                        $(window).load(function() {

                            $('#overlay_loading').hide();
                        });
</script>

<script type="text/javascript">

    function getTownBasedUc()
    {

        $("#uc > option").remove();
        var town_filter_exist = jQuery('#town :selected').val();
        if (typeof town_filter_exist != 'undefined') {
            var town_filter = jQuery('#town :selected').val();
        }
        else {
            var town_filter = "";
        }
        if (town_filter == "") {
            window.location = window.location;
        } else {

            var datum = 'town=' + town_filter;
            $.ajax({
                dataType: 'json',
                url: "<?= base_url() . 'form/town_wise_uc?&app_id=' . $app_id; ?>",
                data: datum,
                success: function(resp) {
                    jQuery('#uc').prepend("<option value='' selected='selected'>Default</option>");
                    $.each(resp, function(id, type)
                    {
                        var opt = $('<option />'); //
                        opt.val(id);
                        opt.text(type);
                        $('#uc').append(opt);
                    });

                }
            });
        }
    }
    jQuery(document).ready(function() {
        $('.CrimeList').show();

        jQuery('#form_list').change(function() {
            $('#more_markers').hide();
        })

        $('#view_type').change(function() {
            $('#overlay_loading').show();
            var selected_view = $("#view_type option:selected").val();

            if (selected_view == 'Heat') {
                $('.new').show();
                setTimeout(function() {
                    var mcOptions = {gridSize: 50, maxZoom: 13};
                    markerClusterer = new MarkerClusterer(map, markers, mcOptions);
                    $('#overlay_loading').hide();
                }, 1);


            } else {
                $('.new').hide();
                $('.show_hide').hide();
                setTimeout(function() {
                    clearOverlay();
                    $.each(locations, function(i) {
                        companyMarker = new google.maps.Marker({
                            position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                            map: map,
                            title: locations[i][5],
                            icon: locations[i][3],
                            zIndex: 3,
                            draggable: true,
                        });

                        google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                            return function() {
                                $.ajax({
                                    url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + locations[i][4] + '&form_id=' + locations[i][2],
                                    success: function(data) {
                                        infowindow.setContent(data);
                                        infowindow.open(map, companyMarker);
                                    }
                                });
                            }
                        })(companyMarker, i));
                        markers.push(companyMarker);
                    });
                    $('#overlay_loading').hide();

                }, 1);

            }

        });



        /**
         * FOR tOWNS aJAX cALL
         */

        $('#town').change(function() {
            clearOverlayCluster();
            $('#overlay_loading').show();

            var town_filter_exist = jQuery('#town :selected').val();
            if (typeof town_filter_exist != 'undefined') {
                var town_filter = jQuery('#town :selected').val();
            }
            else {
                var town_filter = "";
            }
            if (town_filter == "") {
                window.location = window.location;
            } else {

                var datum = 'town=' + town_filter;
                $.ajax({
                    dataType: 'json',
                    url: "<?= base_url() . 'form/town_wise_record?&app_id=' . $app_id; ?>",
                    data: datum,
                    success: function(resp) {

                        $.each(resp, function(i) {
                            companyMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(resp[i].long, resp[i].lat),
                                map: map,
                                title: resp[i].category_name,
                                icon: resp[i].icon_filename,
                                zIndex: 3,
                                draggable: false,
                            });
                            companyMarker.setValues({
                                form_result_id: resp[i].form_result_id
                            });

                            google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                                return function() {
                                    $.ajax({
                                        url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + resp[i].form_result_id + '&form_id=' + resp[i].form_id,
                                        success: function(data) {
                                            infowindow.setContent(data);
                                            infowindow.open(map, companyMarker);
                                        }
                                    });
                                }
                            })(companyMarker, i));
                            markers.push(companyMarker);

                        });
                        if (resp.length > 1) {
                            map.setCenter(new google.maps.LatLng(resp[0].long, resp[0].lat));
                            map.setZoom(13);
                        }
                        $('#boundaries option[value="Towns"]').prop('selected', true);
                        var kml_name = 'lahore_towns.kml';
                        var final_link = kml_path + kml_name;

                        town_boundaries = new google.maps.KmlLayer({
                            url: final_link,
                            preserveViewport: true
                        });
                        town_boundaries.setMap(map);

                        if (typeof uc_boundaries != 'undefined') {
                            // variable is undefined
                            uc_boundaries.setMap(null);

                        }
                        ctaLayer.setMap(null);


                        $('#more_markers').hide();
                        if (!$('#check_all').is(':checked')) {
                            jQuery('#check_all').trigger('click')
                        }
                        $('#overlay_loading').hide();
                        jQuery('#counter_div').text(markers.length);

                    }
                });
            }

        });

        //FOR UC AJAX CALL
        $('#uc').change(function() {
            clearOverlayCluster();
            $('#overlay_loading').show();

            var sent_by_filter_exist = jQuery('#sent_by_map :selected').val();
            if (typeof sent_by_filter_exist != 'undefined') {
                var sent_by_filter = jQuery('#sent_by_map :selected').val();
            }
            else {
                var sent_by_filter = "";
            }

            var uc_filter_exist = jQuery('#uc :selected').val();
            if (typeof uc_filter_exist != 'undefined') {
                var uc_filter = jQuery('#uc :selected').val();
            }
            else {
                var uc_filter = "";
            }
            if (uc_filter == "") {
                window.location = window.location;
            } else {

                var datum = 'uc=' + uc_filter+"&sent_by_filter="+sent_by_filter;
                $.ajax({
                    dataType: 'json',
                    url: "<?= base_url() . 'form/uc_wise_record?&app_id=' . $app_id; ?>",
                    data: datum,
                    success: function(resp) {

                        $.each(resp, function(i) {

                            companyMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(resp[i].long, resp[i].lat),
                                map: map,
                                title: resp[i].category_name,
                                icon: resp[i].icon_filename,
                                zIndex: 3,
                                draggable: false,
                            });
                            companyMarker.setValues({
                                form_result_id: resp[i].form_result_id
                            });

                            google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                                return function() {
                                    $.ajax({
                                        url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + resp[i].form_result_id + '&form_id=' + resp[i].form_id,
                                        success: function(data) {
                                            infowindow.setContent(data);
                                            infowindow.open(map, companyMarker);
                                        }
                                    });
                                }
                            })(companyMarker, i));
                            markers.push(companyMarker);

                        });
                        if (resp.length > 1) {
                            map.setCenter(new google.maps.LatLng(resp[0].long, resp[0].lat));
                            map.setZoom(13);
                        }
                        $('#boundaries option[value="UC"]').prop('selected', true);
                        var kml_name = 'lahore_uc.KML';
                        var final_link = kml_path + kml_name;

                        uc_boundaries = new google.maps.KmlLayer({
                            url: final_link,
                            preserveViewport: true
                        });
                        uc_boundaries.setMap(map);

                        if (typeof towns_boundaries != 'undefined') {
                            // variable is undefined
                            towns_boundaries.setMap(null);

                        }
                        ctaLayer.setMap(null);


                        $('#more_markers').hide();
                        if (!$('#check_all').is(':checked')) {
                            jQuery('#check_all').trigger('click')
                        }
                        $('#overlay_loading').hide();

                        jQuery('#counter_div').text(markers.length);

                    }
                });
            }

        });


        //FOR Sent By AJAX CALL
        $('#sent_by_map').change(function() {
            clearOverlayCluster();
            $('#overlay_loading').show();

            var sent_by_filter_exist = jQuery('#sent_by_map :selected').val();
            if (typeof sent_by_filter_exist != 'undefined') {
                var sent_by_filter = jQuery('#sent_by_map :selected').val();
            }
            else {
                var sent_by_filter = "";
            }

            var uc_filter_exist = jQuery('#uc :selected').val();
            if (typeof uc_filter_exist != 'undefined') {
                var uc_filter = jQuery('#uc :selected').val();
            }
            else {
                var uc_filter = "";
            }


            if (sent_by_filter == "") {
                window.location = window.location;
            } else {

                var datum = 'sent_by_filter=' + sent_by_filter+"&uc_filter="+uc_filter;
                $.ajax({
                    dataType: 'json',
                    url: "<?= base_url() . 'form/sent_by_wise_record?&app_id=' . $app_id; ?>",
                    data: datum,
                    success: function(resp) {

                        $.each(resp, function(i) {

                            companyMarker = new google.maps.Marker({
                                position: new google.maps.LatLng(resp[i].long, resp[i].lat),
                                map: map,
                                title: resp[i].category_name,
                                icon: resp[i].icon_filename,
                                zIndex: 3,
                                draggable: false,
                            });
                            companyMarker.setValues({
                                form_result_id: resp[i].form_result_id
                            });

                            google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                                return function() {
                                    $.ajax({
                                        url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + resp[i].form_result_id + '&form_id=' + resp[i].form_id,
                                        success: function(data) {
                                            infowindow.setContent(data);
                                            infowindow.open(map, companyMarker);
                                        }
                                    });
                                }
                            })(companyMarker, i));
                            markers.push(companyMarker);

                        });
                        if (resp.length > 1) {
                            map.setCenter(new google.maps.LatLng(resp[0].long, resp[0].lat));
                            map.setZoom(13);
                        }
                        $('#boundaries option[value="UC"]').prop('selected', true);
                        var kml_name = 'lahore_uc.KML';
                        var final_link = kml_path + kml_name;

                        uc_boundaries = new google.maps.KmlLayer({
                            url: final_link,
                            preserveViewport: true
                        });
                        uc_boundaries.setMap(map);

                        if (typeof towns_boundaries != 'undefined') {
                            // variable is undefined
                            towns_boundaries.setMap(null);

                        }
                        ctaLayer.setMap(null);


                        $('#more_markers').hide();
                        if (!$('#check_all').is(':checked')) {
                            jQuery('#check_all').trigger('click')
                        }
                        $('#overlay_loading').hide();
                        if(markers[0].icon==undefined){
                            markers.length=0
                        }
                        jQuery('#counter_div').text(markers.length);

                    }
                });
            }

        });



        $("#edit_button_button").live("click", function() {

            $("#edit_info").toggle();
            $("#content").toggle();
        });
        $('#send_data').live('click', function(e) {

            var rowId_for_edit = $("input[name='form_id_hidden']").val();
            console.log(rowId_for_edit);
            $.ajax({url: "<?= base_url() ?>form/edit_form_result_ajax", data: $("#info_edit_form").serialize() + '&form_result_id=' + rowId_for_edit,
                type: 'POST', success: function(data) {
                    $(".success").text(data)
                    $("#edit_info").toggle();
                    $("#content").toggle();
                    $(".success").text('Record Updated Successfully ').show(); //=== Show Success Message==
                    $("#info_window").parent().parent().parent().fadeOut(5000);
                    $('#checkbox_filter_form').submit();
                },
                error: function(data) {
                    console.log(data);
                }
            });
            e.preventDefault(); //=== To Avoid Page Refresh and Fire the Event "Click"===
        });
        $('.show_hide').click(function() {
            $('.CrimeList').show();
            $('.show_hide').hide();
        })
        //show or hide category and other filters
        $('.hide_category').click(function() {
            $('.CrimeList').hide();
            $('.show_hide').show();
        });
        $('input#check_all').change(function() {

            $('#overlay_loading').show();
            if (document.getElementById('check_all').checked) {

                $('input.category').prop('checked', 'checked');
                for (var i = 0; i < markers.length; i++) {

                    markers[i].setVisible(true);
                }
                setTimeout(function() {
                    $('#overlay_loading').hide();

                }, 1500);
                jQuery('#counter_div').text(markers.length);

                var selected_view = $("#view_type option:selected").val();
                if (selected_view == 'Heat') {
                    clusterView(1);
                }
            } else {

                var selected_view = $("#view_type option:selected").val();
                if (selected_view == 'Heat') {
                    clearClusterWithMarkers();
                }
                $('input.category').removeAttr('checked');

                for (var i = 0; i < markers.length; i++) {

                    markers[i].setVisible(false);
                }
                setTimeout(function() {
                    $('#overlay_loading').hide();

                }, 1500);
                jQuery('#counter_div').text("0");
            }

        });

        $('input.category').change(function() {

            var markerCategory = $(this).attr('value');

            var selected_view = $("#view_type option:selected").val();
            if (selected_view == 'Heat') {
                $('#overlay_loading').show();
                if (!$(this).is(':checked')) {
                    counter = 0;
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].title == markerCategory) {
                            counter = counter + 1;
                            markerClusterer.removeMarker(markers[i]);

                        }
                    }


                    var before_counter = jQuery('#counter_div').text();
                    jQuery('#counter_div').text(+before_counter - +counter);
                    $('.overlay').show();
                    if (counter <= 1) {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    } else {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    }

                    setTimeout(function() {
                        $('.overlay').fadeOut();
                    }, 2000);
                    setTimeout(function() {
                        $('#overlay_loading').hide();

                    }, 1000);

                } else {

                    counter = 0;
                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].title == markerCategory) {
                            markerClusterer.addMarker(markers[i], false);
                            counter = counter + 1;
                        }
                    }

                    var before_counter = jQuery('#counter_div').text();
                    jQuery('#counter_div').text(+before_counter + +counter);
                    $('.overlay').show();
                    if (counter <= 1) {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    } else {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    }

                    setTimeout(function() {
                        $('.overlay').fadeOut();
                    }, 2000);
                    setTimeout(function() {
                        $('#overlay_loading').hide();

                    }, 1000);

                }


//                setTimeout(function() {
//                    $('#overlay_loading').hide();
//
//                }, 1000);
            } else {

                var counter = 0;
                $('#overlay_loading').show();
                if (!$(this).is(':checked')) {

                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].title == markerCategory) {
                            markers[i].setVisible(false);
                            counter = counter + 1;
                        }
                    }
                    setTimeout(function() {
                        $('#overlay_loading').hide();

                    }, 700);

                    var before_counter = jQuery('#counter_div').text();
                    jQuery('#counter_div').text(+before_counter - +counter);

                    if (counter <= 1) {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    } else {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    }
                } else {

                    for (var i = 0; i < markers.length; i++) {
                        if (markers[i].title == markerCategory) {
                            marker_date=markers[i].date;
                            marker_date_arr=marker_date.split(" ");
                            marker_date=Date.parse(marker_date_arr[0]);
                            date1=$('#datepicker').val();
                            date2=$('#datepicker2').val();

                            if(date1!='' && date2!=''){
                                date1=Date.parse($('#datepicker').val());
                                date2=Date.parse($('#datepicker2').val());
                                if(marker_date>=date1 && marker_date<=date2) {
                                    markers[i].setVisible(true);
                                    counter = counter + 1;
                                }
                            }else{
                                markers[i].setVisible(true);
                                counter = counter + 1;
                            }

//                            markers[i].setVisible(true);
//                            counter = counter + 1;
                        }
                        $('.overlay').show();
                    }
                    var before_counter = jQuery('#counter_div').text();
                    jQuery('#counter_div').text(+before_counter + +counter);
                    //                console.log(before_counter + '---' +counter);

                    if (counter <= 1) {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    } else {
                        $('.overlay .message').text(counter + ' Record(s) ');
                    }

                    setTimeout(function() {
                        $('.overlay').fadeOut();
                    }, 2000);
                    setTimeout(function() {
                        $('#overlay_loading').hide();

                    }, 1000);
                }
            }
        });

        $('input.category').click(function() {

            $('input#check_all').removeAttr('checked');
        });
        $('#cat_filter').change(function() {


            if ($("#cat_filter").val() != '') {
                var filter_data = $("#cat_filter option:selected").text();
                $('#filter_form').submit();
            }
            else {
                window.location = window.location;
            }
        });
        jQuery(".edit_color_box").live('click', function(e) {


            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            var rowId = jQuery(this).attr('form_result_id');
            var lat = jQuery(this).attr('lat');
            var long = jQuery(this).attr('long');
            //            var rowId = jQuery(this).parents('td').parents('tr').attr('id');
            var datum = 'form_id=' + id + '& form_result_id=' + rowId + '& lat=' + lat + '& long=' + long;
            jQuery(this).colorbox({
                width: "50%",
                height: "60%",
                open: true,
                data: datum,
            });
            e.preventDefault();
            return false;
        });
        jQuery(".data_colorbox").live('click', function(e) {
            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            var datum = jQuery('#add_info_form').serialize() + '&form_id=' + id;
            jQuery(this).colorbox({
                width: "50%",
                height: "60%",
                open: true,
                data: datum, //                title: function() {

            });
            e.preventDefault();
            return false;
        });
        jQuery(".image_colorbox").live('click', function(e) {
            jQuery(this).colorbox({
                width: "55%",
                height: "65%",
                open: true,
                title: function() {
                    var link = "http://dataplug.itu.edu.pk/";
                    var url = $(".image_colorbox").attr('name');
                    var title = $(".image_colorbox").attr('title');
                    return  title;
                }});
            e.preventDefault();
            return false;
        });
    });


// Delete call here
    jQuery('.delete_icon').live('click', function(e) {

        if (confirm('Are you sure? You want to Delelte')) {
            var rowId_for_edit = jQuery(this).attr('form_result_id');
            var lat = jQuery(this).attr('lat');
            var long = jQuery(this).attr('long');
            jQuery.ajax({
                url: "<?= base_url() ?>form/delete_result",
                data: {result_id: rowId_for_edit},
                type: 'POST',
                success: function(data) {
                    for (i = 0; i < locations.length; i++) {
                        if (locations[i][0] == lat && locations[i][1] == long) {
                            markers[i].setMap(null);
                        }
                    }
                    jQuery(".success").text('Record Deleted Successfully ').show().fadeOut(7000); //=== Show Success Message==
                    var counters = jQuery('#counter_div').text();
                    jQuery('#counter_div').text(counters - 1);
                },
                error: function(data) {


                }
            });
        }

        else {
            jQuery('.gm-style-iw').next().click()
            return false;
        }
        e.preventDefault(); //=== To Avoid Page Refresh and Fire the Event "Click"===
    });
    jQuery('.gm-style-iw').live('click', function() {
//    jQuery('.gm-style-iw').next().click();
        clearTempMarkers();
    })



    /**
     *
     * @returns {undefined}
     * check date from and too compatibility
     * auth:ubd
     */
    function check_date_validity() {
        var date_from = jQuery('#datepicker2').val();
        var date_to = jQuery('#datepicker').val();
        if (new Date(date_from).getTime() < new Date(date_to).getTime()) {
            jQuery('#datepicker2').val('');
            jQuery('#datepicker').val('');
            alert('Invalid Date selection');
        }
    }
    /*
     * Clear date filed on doubl
     * click
     * auth:ubd
     */
    function clear_field(obj) {
        jQuery(obj).val("");
    }

//     var templist = [];
//     $('#form_descrip').find('input, textarea, select').each(function() {
//         if ($(this).is('select') || $(this).is(':radio') || $(this).is(':checkbox'))
//         {
//             if ($(this).attr('name') != undefined) {
//                 var field_name = $(this).attr('name');
//                 field_name = field_name.replace('[]', '');
//                 var skip = $(this).attr('rel');
//                 var type = $(this).attr('type');
                //var selected = '<?php //echo $filter; ?>';
//                 //if (type != 'text' && type != 'hidden') {

//                 if ($.inArray(field_name, templist) == '-1')
//                 {
//                     var field_name_display = field_name;
//                     templist.push(field_name);
//                     //                if (field_name != 'Tehsil' && field_name != 'District' && field_name != 'Hospital_Name' && field_name != 'No_of_Citizen_Visited' && field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                     if (field_name != 'District' && field_name != 'Tehsil' && field_name != 'form_id' && field_name != 'security_key' && field_name != 'takepic' && skip != 'skip' && skip != 'norepeat' && type != 'submit')
//                     {
//                         field_name_display.replace(/[_\W](^\s+|\s+$|(.*)>\s*)/g, "");
//                         field_name_display = field_name_display.replace(/_/g, ' ');
//                         field_name_display = capitalize_first_letter(field_name_display);
//                         //field_name_display
//                         if (selected == field_name)
//                         {
//                             field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                             $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" selected>' + field_name_display + '</option>');
//                         }
//                         else {
//                             field_name.replace(/(^\s+|\s+$|(.*)>\s*)/g, "");
//                             $('#filter').append('<option value="' + field_name + '" display_value="' + field_name + '" >' + field_name_display + '</option>');
//                         }
//                     }
//                 }
//             }
//         }
//     });
//    $('#filter').append('<option value="all_visits" display_value="all_visits" >All Visits</option>');
    /**
     * function to update filters
     * and trigger click event on filter
     * button
     * ubaid
     */
    function filter_update(app_id, filter_selected) {
        var filter_selected = filter_selected;
        if (filter_selected != 'all_visits') {
            jQuery.ajax({
                url: "<?= base_url() ?>form/changeFilterMap",
                data: {app_id: app_id, filter_selected: filter_selected},
                type: 'POST',
                success: function(data) {
                    $('#filter_submit').trigger('click');
                },
                error: function(data) {
                }
            });
        } else {
            $("input[name='all_visits_hidden']").val('1');
            $('#filter_submit').trigger('click');
        }
        jQuery('#changed_category').val(filter_selected);
    }
    jQuery(document).ready(function() {
        jQuery('#changed_category').val(jQuery('#filter').val());
    })

    function capitalize_first_letter(str) {
        var words = str.split(' ');
        var html = '';
        $.each(words, function() {
            var first_letter = this.substring(0, 1);
            html += first_letter.toUpperCase() + this.substring(1) + ' ';
        });
        return html;
    }
</script>
<style type="text/css">
    #form_lists {
        width: 95px;
        margin-top: 2px;
    }
    .applicationText{
        padding-bottom: 5px !important;
    }

    .filter_class {
        background: none repeat scroll 0 0 #0E76BD;
        border: 2px solid #FFFFFF;
        border-radius: 4px;
        color: #FFFFFF;
        height: 35px;
        margin-bottom: -36px;
        margin-left: 75px;
        margin-top: 6px;
        position: absolute;
        width: 86%;
        z-index: 999;
    }
    #filter_form{
        display: inline;
        margin-left: 54px;
    }
    tr:hover{
        background: white !important;
    }
</style>

<style>

    .info, .success, .warning, .error, .validation {
        border: 1px solid;
        margin: 10px 0px;
        padding:15px 70px 15px 96px;
        background-repeat: no-repeat;
        background-position: 10px center;
    }

    .success {
        color: #4F8A10;
        background-color: #E5BF00;
        position: absolute;
        z-index: 11;
        margin: 41px 230px 12px;
        display: none;
    }
    .CrimeList {
        display: none;
        position: fixed;
        right: 0;
        top: 210px;
        width: 188px;
        z-index: 9999;
    }
    .crimeListText p {
        border-bottom: 1px solid #E4E3E3;
        color: #7C7B7B;
        display: block;
        float: left;
        font-size: 10px;
        width: 100%;
        z-index: -1;
    }
    .crimeListText p label{
        /*                                margin-left: -3px;*/
    }
    .crimeListText {
        background: none repeat scroll 0 0 #FFFFFF;
        float: left;
        height: 335px;
        margin-bottom: 4px;
        margin-left: 8px;
        margin-top: 30px;
        overflow: auto;
        width: 174px;
    }
    .dropDownDiv {
        background: none repeat scroll 0 0 #0E76BD;
        display: block;
        margin-top: 4px;
        padding: 3px 0;
    }

    .chosen-container {
        -moz-user-select: none;
        display: inline-block;
        font-size: 13px;
        position: relative;
        vertical-align: middle;
    }
    .chosen-container-multi .chosen-choices {
        -moz-box-sizing: border-box;
        background-color: #FFFFFF;
        background-image: -moz-linear-gradient(#EEEEEE 1%, #FFFFFF 15%);
        border: 1px solid #AAAAAA;
        cursor: text;
        height: auto !important;
        margin: 0;
        overflow: hidden;
        padding: 0;
        position: relative;
        width: 100%;
    }
    .chosen-container-multi .chosen-choices li.search-field {
        margin: 0;
        padding: 0;
        white-space: nowrap;
    }
    .chosen-container-multi .chosen-choices li {
        float: left;
        list-style: none outside none;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
        background: none repeat scroll 0 0 transparent !important;
        border: 0 none !important;
        border-radius: 0 0 0 0;
        box-shadow: none;
        color: #666666;
        font-family: sans-serif;
        font-size: 100%;
        height: 15px;
        line-height: normal;
        margin: 1px 0;
        outline: 0 none;
        padding: 5px;
    }
    .chosen-container-multi .chosen-choices li.search-field .default {
        color: #999999;
    }
    .chosen-container .chosen-drop {
        -moz-border-bottom-colors: none;
        -moz-border-image: none;
        -moz-border-left-colors: none;
        -moz-border-right-colors: none;
        -moz-border-top-colors: none;
        -moz-box-sizing: border-box;
        background: none repeat scroll 0 0 #FFFFFF;
        border-color: -moz-use-text-color #AAAAAA #AAAAAA;
        border-right: 1px solid #AAAAAA;
        border-style: none solid solid;
        border-width: 0 1px 1px;
        box-shadow: 0 4px 5px rgba(0, 0, 0, 0.15);
        left: -9999px;
        position: absolute;
        top: -243px;
        width: 100%;
        z-index: 1010;
    }
    .chosen-container-multi .chosen-results {
        margin: 0;
        padding: 0;
    }
    .chosen-container {
        -moz-user-select: none;
        display: inline-block;
        font-size: 13px;
        position: relative;
        vertical-align: middle;
    }
    .chosen-container-multi .chosen-choices {
        -moz-box-sizing: border-box;
        background-color: #FFFFFF;
        background-image: -moz-linear-gradient(#EEEEEE 1%, #FFFFFF 15%);
        border: 1px solid #AAAAAA;
        cursor: text;
        height: auto !important;
        margin: 0;
        overflow: hidden;
        padding: 0;
        position: relative;
        width: 100%;
    }
    .crimeListText p img{
        float: right;
        margin-right: 4px;
        margin-top: 1px;
    }

    .chosen-container  button {
        float: right;
        margin-right: 9px;
    }

</style>
<style>
    .gm-style-iw{
        overflow:hidden !important;
    }
    #form-resutl-listing{
        margin-top: -66px;
        position: fixed;
        z-index: -2;
    }
    h4{
        background-color: #D8C7C7 !important;
        width: 100%;
    }
    .Wraper{
        margin:auto;
        padding:0px;
        width:auto;
    }
    .bgColor{
        height: 83px;
    }
    .form_list{
        width: 90px;
    }

</style>
<style>
    #overlay_loading img{
        margin: 20% 0 0 47%;
    }
    #disricts{
        max-width: 81px;
        width: 169px;
    }
    #view_type {
        margin-top: 7px;
        max-width: 106px;
    }
    .overlay {
        z-index: 1000;
        border: medium none;
        margin: 0pt;
        padding: 0pt;
        top: 0pt;
        left: 0pt;
        opacity: 0.8;

    }
    .overlay .message {
        z-index: 1001;
        position: absolute;
        padding: 0px;
        margin: auto;
        width: 30%;
        top: 43%;
        left: 30%;
        text-align: center;
        color: #090901;
        border: saddlebrown 1px solid;
        border-radius: 6px 6px 6px 6px;
        background-color: #fff;
        background: #F1E870;
        font-size: 18px;
        font-weight: bold;
        padding: 1%;
    }
    .gm-style{
        top: inherit !important;
        left: inherit !important;

    }
    #more_markers{
        background-color: #DDDDDD;
        height: 27px;
        margin-top: 4px;
        width: 98px;
    }
    .filter_list{
        width: 90px;
    }
    .form_class{
        margin-left:3px;
        margin-top: 8px;
    }
    .ms-parent li{
        color: #7C7B7B;
        font-size: 11px;
    }
    .ms-choice{
        height: 20px !important;
        line-height: 19px !important;
    }


</style>
<script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.multiple.select.js"></script>
<link rel="stylesheet" href="<?= base_url() ?>/assets/css//multiple-select.css"/>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/lightbox-2.6.min.js" ></script>
<script>
//    $(".form_list").multipleSelect({
//        filter: true,
//        width: 200,
//    });

//    $(".filter_list").multipleSelect({
//        width: 210,
//        filter: true,
//        onClick: function(view) {
////            $eventResult.text(view.label + '(' + view.value + ') ' +
////                    (view.checked ? 'checked' : 'unchecked'));
//            var changed_value = view.rel;
//
//            var changed_filter = view.rel;
////            var changed_filter = $('#changed_value:contains(' + changed_value + ')').attr('id');
//            var filter_to_update = $('#' + changed_filter).parent().next().children().attr('id');
//            var filter_values = $("#" + changed_filter + "").multipleSelect('getSelects');
//            if (filter_values != '' && filter_to_update != null) {
//                $.ajax({
//                    url: "<?= base_url() ?>form/map_filters_settings/<?php echo $app_id ?>",
//                                        data: {filter_values: filter_values, filter_to_update: filter_to_update, changed_filter: changed_filter},
//                                        type: 'POST',
//                                        success: function(resp) {
//                                            $("#" + filter_to_update + "").empty();
//
//                                            $.each(resp, function(id, type) {
//                                                if (type.length > 23) {
//                                                    type = type.substring(0, 23) + ' ...';
//                                                }
//                                                var opt = $('<option />');
//                                                opt.val(id);
//                                                opt.text(type);
//                                                $("#" + filter_to_update + "").append(opt).multipleSelect("refresh");
//
////                                               $("#" + filter_to_update + "").multipleSelect('setSelects', ["LAHORE CANTT TEHSIL"]);
//
//                                                //$("#" + filter_to_update + "").next().children().next().children().next().find('li input').attr('checked',true);
//                                            });
//                                            $("#" + filter_to_update + "").next().children().next().children().next().find('li input').each(function() {
//                                                //$(this).trigger('click');
//                                            })
//                                            empty_filter(filter_to_update);
//
//
////                                            $("#" + filter_to_update + "").multipleSelect("checkAll");
//                                            $("#" + filter_to_update + "").multipleSelect("refresh");
//                                        },
//                                        error: function(resp) {
//                                            console.log('Error');
//                                        }
//                                    });
//                                }
//                                else
//                                {
//                                    $("#" + filter_to_update + "").empty();
//                                    empty_filter(filter_to_update);
////                                            $("#" + filter_to_update + "").multipleSelect("checkAll");
//                                    $("#" + filter_to_update + "").multipleSelect("refresh");
//
//                                }
//                            }
//                        });
    function empty_filter(filter_to_update)
    {
        var next_id = $("#" + filter_to_update + "").parent().next().children().attr('id');
        if (next_id != undefined)
        {
            $("#" + next_id + "").empty();
            $("#" + next_id + "").multipleSelect("refresh");
            empty_filter(next_id);
        }
    }
    jQuery(document).ready(function() {
        $('.ms-parent li').hover(function() {
            var title = $(this).children().children().attr('value');
            $(this).attr('title', title);
//                            alert($(this).children().children().attr('value'));
        });
    })
    jQuery('#map_view').css('background-color', '#EDB234');

jQuery(".filter_list_mapview").multipleSelect({
    filter: true,
    width: 200,
    placeholder: "Please select"
});

jQuery( document ).ready(function() {
    jQuery(".open_settings").click(function(){
        var tab_id=jQuery('#open_settings').val();
        jQuery.colorbox({
            open: true,
            width: '90%',
            height: '100%',
            iframe:true,
            href: '<?php echo base_url() . 'application-setting/'.$app_id; ?>'+'/'+tab_id,
            onClosed:function(){parent.location.reload();}
        });
    });
});
</script>
<!--<script type="text/javascript" src="--><?//= base_url() ?><!--assets/js/lightbox-2.6.min.js" ></script>-->