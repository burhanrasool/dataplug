<div class="homeFooter">
    <div class="footerWrap">
        <p><?php echo FOOTER_TEXT;?></p>
    </div>
</div>



    <!-- Javascript Files -->
    <script type="text/javascript" src="<?= base_url() ?>assets/home/DataPlug_files/jquery_004.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.colorbox.js" ></script>
    <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.js" ></script>
    <script src="<?= base_url() ?>assets/home/js/responsiveslides.min.js"></script>
    <script src="<?= base_url() ?>assets/home/js/common-function.js"></script>
    <script>
        $(function(){$("#slider4").responsiveSlides({auto:true,pager:false,nav:false,speed:500,namespace:"callbacks",before:function(){$(".events").append("<li>before event fired.</li>")},after:function(){$(".events").append("<li>after event fired.</li>")}})});
    </script>

</body>
</html>