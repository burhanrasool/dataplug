<div class="callbacks_container">
      <ul class="rslides" id="slider4">
        <li>
         <img src="<?= base_url() ?>assets/home/images/slider-01.jpg" > 
          <p class="caption">This is a caption</p>
        </li>
        <li>
         <img src="<?= base_url() ?>assets/home/images/slider-02.jpg" >
          <p class="caption">This is another caption</p>
        </li>
       
      </ul>
    </div>
<div class="homeFeatured">
    <div class="featuredWrap">
        <ul>
            <li> <img src="<?= base_url() ?>assets/home/images/intuativeIco.png" alt="Intuative" />
                <h2>Intuitive</h2>
                <p>Create your own mobile application within 5 minutes</p>
                <?php if (isset($login_user_fullname)) { ?>
                <a href="<?= base_url() ?>apps" class="tryBtn">Start Using</a>
            <?php } else { ?>
                <a href="<?= base_url() ?>guest" class="tryBtn">Start Using</a> 
            <?php } ?>
                
            </li>
            <li> <img src="<?= base_url() ?>assets/home/images/collaborativeIco.png" alt="Collaborative" />
                <h2>Dynamic</h2>
                <p>DataPlug applications can be updated remotely on the fly with just one click</p>
                <a class="downloadBtn" id="downloadcode" style="cursor: pointer;">Download DataPlug</a>
            </li>
            <li> <img src="<?= base_url() ?>assets/home/images/easiertoadaptIco.png" alt="Easier to Adapt" />
                <h2>Easy to Adapt</h2>
                <p>DataPlug can be used by Government agencies, NGOs and other organizations for large-scale data gathering.</p>
                <a href="<?= base_url() ?>DataPlugForum" class="forumBtn" target="_new">Community Forum</a> </div>
            </li>
        </ul>

</div>
<div class="affiliationFeatured">
    <div class="affiliationWrap">
        <ul class="affulLft">
            <li> <img src="<?= base_url() ?>assets/home/images/itu.png" alt="Information Technology" /> </li>

        </ul>  
        <ul class="affulRt">
            <h3>Sponsored by</h3>

            <li class="sponsered"> <img src="<?= base_url() ?>assets/home/images/worldjob.png" alt="The World Bank" /> </li>
            <li class="sponsered"> <img src="<?= base_url() ?>assets/home/images/ukaid.png" alt="UkAid" /> </li>

        </ul>
    </div>
</div>
