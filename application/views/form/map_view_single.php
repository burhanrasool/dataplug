<!-- Javascript Files -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
<style>
    #overlay_loading img{
        margin: 20% 0 0 47%;
    }
    #disricts{
        max-width: 106px;
        width: 169px;
    }
    #view_type{
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

</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/markerclusterer.js'; ?>"></script>


<script type="text/javascript">
    var default_image = '<?php echo base_url() . "assets/images/map_pins/default_pin.png"; ?>';
    var map;
    var markers = [];
    var markerClusterer;
    var locations = [<?= $locations ?>];
    var app_id = '<?= $app_id ?>';
    var urls = '<?php echo base_url() . "form/getMapPartial"; ?>';
    var urls_edit = '<?php echo base_url() . "form/edit_form_partial_map"; ?>';
    var kmz_file = '<?php echo base_url() . "assets/kml/punjab_districts.kml"; ?>';
    var lahore_kml = 'http://denguetrackingcedar.herokuapp.com/Lahore_Towns.KML';
    var ctaLayer;
    var infowindow = new google.maps.InfoWindow({
        maxWidth: 500
    });
    $(document).ready(function() {

        var zoom_level_db = 14;
        var latitude_db = locations[0][0];
        var longitude_db = locations[0][1];
        var myLatlng = new google.maps.LatLng(latitude_db, longitude_db);
        var options = {
            zoom: zoom_level_db,
            center: myLatlng,
            mapTypeControl: true,
            mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
            navigationControl: true,
            navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        }
        map = new google.maps.Map(document.getElementById("map"), options);
        var infowindow = new google.maps.InfoWindow({
            maxWidth: 500
        });
        for (i = 0; i < locations.length; i++) {

            companyMarker = new google.maps.Marker({
                position: new google.maps.LatLng(locations[i][0], locations[i][1]),
                map: map,
                title: locations[i][5],
                icon: locations[i][3],
                zIndex: 3,
                draggable: false,
            });

            companyMarker.setValues({
                form_result_id: locations[i][4]
            });

            google.maps.event.addListener(companyMarker, 'click', (function(companyMarker, i) {
                return function() {
                    $.ajax({
                        url: '<?= base_url() ?>form/map_activity_popup?form_result_id=' + locations[i][4]+'&form_id='+'<?php echo $form_id; ?>',
                        success: function(data) {
                            infowindow.setContent(data);
                            infowindow.open(map, companyMarker);
                        }
                    });
                }
            })(companyMarker, i));
            markers.push(companyMarker);
            google.maps.event.trigger(markers[i], 'click');
        }
        map.setCenter(myLatlng);
        ctaLayer = new google.maps.KmlLayer({
            url: kmz_file,
            preserveViewport: true
        });
        ctaLayer.setMap(map);
        var form_id = '<?php echo $form_id; ?>';
        if (form_id == 9) {
            var district = "Lahore";
        }

        if (district == "Lahore") {
            lhrKml = new google.maps.KmlLayer({
                url: lahore_kml
            });
            lhrKml.setMap(map);
            ctaLayer.setMap(null);
        } else {
            ctaLayer.setMap(map);
            if (typeof lhrKml != 'undefined') {
                // variable is undefined
                lhrKml.setMap(null);
            }
        }
    });

    /*
     * Loading wait status for map
     */
    loading_image();
    function loading_image() {
        $(function() {

            var docHeight = $(document).height();
            $("body").append('<div  id="overlay_loading" title="Please Wait while the map load">\n\
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

</script>

<div id="container">
    <div class="inner-wrap">

    </div>
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="form-resutl-listing">
        <thead>
            <tr>
            </tr>
        </thead>
        <tbody>
            <tr class="gradeX">
                <td style='padding:51px 0px !important'>
                    <div id="map" style="position: static !important; height:696px; width: 1279px;margin:19px 0 0 -194px; ">
                        <!--Herer is the map to ber-->
                    </div>


                </td>  
            </tr>

        </tbody>
    </table>
    <div style="position: absolute; bottom: 15px;margin-left:12%;font-weight: bold;"><?php echo FOOTER_TEXT;?></div>
</div>
</div>
</div>


<script type="text/javascript">

    $('#overlay_loading').show();
    $(window).load(function() {

        $('#overlay_loading').hide();
    });
    jQuery(document).ready(function() {


        jQuery(".edit_color_box").live('click', function(e) {


            var url = window.location.pathname;
            var id = url.substring(url.lastIndexOf('/') + 1);
            var rowId = jQuery(this).attr('form_result_id');
            var lat = jQuery(this).attr('lat');
            var long = jQuery(this).attr('long');
            var datum = 'form_id=' + app_id + '& form_result_id=' + rowId + '& lat=' + lat + '& long=' + long;
            jQuery(this).colorbox({
                width: "50%",
                height: "60%",
                open: true,
                data: datum,
                onClosed: function() {
                    location.reload(true);
                }
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
                    window.close();
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
</script>
<style type="text/css">
    .applicationText{
        padding-bottom: 5px !important;
    }
    .filter_class{
        background: none repeat scroll 0 0 #D8C7C7 !important;
        border: 2px solid #E5BF00;
        border-radius: 4px;
        color: #777777;
        height: 26px;
        margin-left: 11px;
        padding-top: 2px;
        width: 97%;
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
        margin: 0px 0px 11px 0px;
        display: none;
    }


</style>
<style>
    .gm-style-iw{
        overflow:hidden !important;
    }
    #form-resutl-listing{
        margin-top: -66px;
        /*        position: fixed;*/
        z-index: -2;
    }
    h4{
        background-color: #D8C7C7 !important;
        width: 100%;
    }
    .Wraper{
        margin:-4px auto;
        width: 890px  !important;
    }
    .bgColor{
        height: 138px;
    }

</style>
