<div style="margin:0 0 0 49%;">
    <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>"  title='Rotate 360 Degree' id='rotate_360' >
</div>
<div style="margin:28% 0 0 0;float: left">
    <img src="<?= base_url() . 'assets/images/degree_270.png'; ?>"  title='Rotate 270 Degree' id='rotate_270' >
</div>
<div id='gallery'>
    <!--    <a data-lightbox="roadtrip"  class="gallery_1" href="http://localhost/godk/assets/images/data/form-data/users_added1391150786.jpg">
            <img width='310px' height='300px' src="http://localhost/godk/assets/images/data/form-data/users_added1391150786.jpg"/>
        </a>
    
        <a data-lightbox="roadtrip" class="gallery_1" href="http://localhost/godk/assets/images/data/form-data/users_added1391150807.jpg">
        </a>
        <a data-lightbox="roadtrip" class="gallery_1" href="http://localhost/godk/assets/images/data/form-data/users_added1391150786.jpg">
        </a>
        <a data-lightbox="roadtrip"class="gallery_1" href="http://localhost/godk/assets/images/data/form-data/users_added1391150807.jpg">
        </a>-->
    <!--<a  class="gallery_1" href="javascript:void(0)">-->
    <img class="gallery_1" src="<?php echo $images[0]['image']; ?>" width="102%" height="100%"/>
    <!--</a>-->
</div>
<style>
    #cboxLoadedContent{
        overflow: hidden !important;
    }
    #gallery{
        float: right;
        height: 80%;
        margin: 0 50px 0 0;
        padding: 3px 0 2px 3px;
        width: 85%;
    }
</style>
<div style="margin:0 0 0 48%;">

    <img src="<?= base_url() . 'assets/images/degree_180.png'; ?>"  title='Rotate 180 Degree' id='rotate_180' >
</div>
<div style="margin: -34% 0px 0px; float: right">
    <img src="<?= base_url() . 'assets/images/degree_90.png'; ?>"  title='Rotate 90 Degree' id='rotate_90' >
</div>
<script type='text/javascript'>


    jQuery(document).ready(function() {
        jQuery('#rotate_90').click(function() {
            rotate(1);
            function rotate(degree) {
                jQuery(".gallery_1").css({
                    '-webkit-transform': 'rotate(' + degree + 'deg)',
                    '-moz-transform': 'rotate(' + degree + 'deg)',
                    '-o-transform': 'rotate(' + degree + 'deg)',
                    '-ms-transform': 'rotate(' + degree + 'deg)',
                    'transform': 'rotate(' + degree + 'deg)'
                });
                if (degree < 90) {
                    timer = setTimeout(function() {
                        degree = degree + 10;
                        rotate(degree)
                    }, 1);
                }
            }

        });
        jQuery('#rotate_180').click(function() {
            rotate(1);
            function rotate(degree) {
                jQuery(".gallery_1").css({
                    '-webkit-transform': 'rotate(' + degree + 'deg)',
                    '-moz-transform': 'rotate(' + degree + 'deg)',
                    '-o-transform': 'rotate(' + degree + 'deg)',
                    '-ms-transform': 'rotate(' + degree + 'deg)',
                    'transform': 'rotate(' + degree + 'deg)'
                });
                if (degree < 180) {
                    timer = setTimeout(function() {
                        degree = degree + 10;
                        rotate(degree)
                    }, 1);
                }
            }

        });
        jQuery('#rotate_270').click(function() {
            rotate(1);
            function rotate(degree) {
                jQuery(".gallery_1").css({
                    '-webkit-transform': 'rotate(' + degree + 'deg)',
                    '-moz-transform': 'rotate(' + degree + 'deg)',
                    '-o-transform': 'rotate(' + degree + 'deg)',
                    '-ms-transform': 'rotate(' + degree + 'deg)',
                    'transform': 'rotate(' + degree + 'deg)'
                });
                if (degree < 270) {
                    timer = setTimeout(function() {
                        degree = degree + 10;
                        rotate(degree)
                    }, 1);
                }
            }

        });
        jQuery('#rotate_360').click(function() {
            rotate(1);
            function rotate(degree) {
                jQuery(".gallery_1").css({
                    '-webkit-transform': 'rotate(' + degree + 'deg)',
                    '-moz-transform': 'rotate(' + degree + 'deg)',
                    '-o-transform': 'rotate(' + degree + 'deg)',
                    '-ms-transform': 'rotate(' + degree + 'deg)',
                    'transform': 'rotate(' + degree + 'deg)'
                });
                if (degree < 360) {
                    timer = setTimeout(function() {
                        degree = degree + 10;
                        rotate(degree)
                    }, 1);
                }
            }

        });
    });
</script>

