<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='http://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>
<div id="content"> 
    <div id="siteNotice">
<!--        <a href='<?php echo base_url() . "form/edit_form_partial"; ?>' class="edit_color_box" title="Edit Form " name = "Pakistan" lat ='<?php echo $locations[0]['lat']; ?>' long ='<?php echo $locations[0]['long']; ?>' form_result_id='<?php echo $locations[0]['form_result_id']; ?>'>
            <img class="edit_icon" style="float:right;padding: 8px;" title="Edit"  src="<?php echo base_url() . "assets/images/tableLink1.png"; ?>">
        </a>-->
        <!--<img class="delete_icon" lat='<?php echo $locations[0]['lat']; ?>' long='<?php echo $locations[0]['long']; ?>' form_result_id='<?php echo $locations[0]['form_result_id']; ?>' style="cursor:pointer;float:right; padding: 8px;" title="Delete"  src="<?php echo base_url() . "assets/images/tableLink3.png"; ?>">-->
    </div> 
    <h1 class="firstHeading" id="firstHeading">
        <b> Activity Detail</b>

    </h1> 

    <div id="dashboard-popup"> 
        <div class="monitor-listing"> 
            <ul> 
                <?php
                $exclude_array = array('image', 'date_time', 'ID', 'LOCATION');
//                echo '<pre>'; print_r($data_array); die;
                foreach ($data_array as $key => $data) {
                    if (!in_array($key, $exclude_array)) {
                        ?>
                        <li class="department"><?php echo $key; ?>:<span><?php echo str_replace(',', ', ', $data); ?></span></li> 
                        <?php
                    }
                }
                if (isset($data_array['date_time'])) {
                    ?>
                    <li class="name">Date  :<span><?php echo date('Y-m-d', strtotime($data_array['date_time'])); ?></span></li> 
                    <li class="name">Time :<span><?php echo date('H:i:s A', strtotime($data_array['date_time'])); ?></span></li> 
                    <?php
                }
                ?>
            </ul> 
            <div class="picture-data"> 
                <div style="width: 223px;height: 192px;overflow: hidden;" id="before" class="picture-before"> 
                    <?php
                    if (isset($data_array['image']) && $data_array['image'][0]['image']) {
                        foreach ($data_array['image'] as $image) {
                            $inside_title = strtoupper($image['title']);
                            if (array_key_exists($inside_title, $data_array)) {
                                $title = $data_array[$inside_title];
                            } else {
                                $title = $image['title'];
                            }
                            ?>
                            <a rel="lightbox[]"  href="<?php echo $image['image'] ?>" title="<b><span style='font-weight: 900;font-family: Sans-serif'><?php echo ($title) ? ucwords(strtolower($title)) : 'No Title'; ?></span><b>">
                                <img width="97%" height="96%" src="<?php echo $image['image'] ?>" title="Zoom"> 
                            </a>
                            <a rel="lightbox['<?php echo $locations[0]['id']; ?>']" class="zoomIcon lightbox" href="<?php echo $image['image'] ?>" title="<b><span style='font-weight: bold;font-family: Sans-serif'><?php echo ($title) ? ucwords(strtolower($title)) : 'No Title'; ?></span><b>">
                            </a>
                            <?php
                        }
                    } else {
                        ?>
                        <a rel="lightbox['<?php echo $locations[0]['id']; ?>']"  href="<?php echo base_url() . 'assets/images/no_image.png'; ?>">
                            <img width="97%" height="96%" src="<?php echo base_url() . 'assets/images/no_image.png'; ?>"> 
                        </a>
                    <?php } ?>

                    <div class="picture-before-meta">Locality Image </div> 

                </div>  
                <!--                              <div style="width: 213px;height: 192px;overflow: hidden;" id="after" class="picture-after">  
                                                  <img width="97%" height="96%" src="http://s3.amazonaws.com/denguetrackingdev/pictures/764767/original/afterPicture.jpg?1395385714"> 
                                                  <div class="picture-after-meta">After Action Picture </div> 
                                                  <a rel="group1" class="zoomIcon lightbox" href="http://s3.amazonaws.com/denguetrackingdev/pictures/764767/original/afterPicture.jpg?1395385714"></a>
                                              </div>-->
            </div>  
        </div> 
    </div> 
</div>
<style>
    .gm-style .gm-style-iw, .gm-style .gm-style-iw a, 
    .gm-style .gm-style-iw span, .gm-style .gm-style-iw label,
    .gm-style .gm-style-iw div {
        font-size: 13px;
        font-weight: 300;
    }
    .firstHeading {
        background-color: #DDDDDD;
        color: #333333;
        font-family: Arial,Helvetica,sans-serifr;
        font-size: 15px;
        margin-top: 14px;
        padding: 8px 0;
        text-align: center;
        text-transform: uppercase;
    }
    #dashboard-popup {
        overflow: hidden;
        width: 517px !important;
    }
    .monitor-listing {
        float: left;
        margin: 10px 0;
        width: 520px;
    }
    .monitor-listing ul {
        float: left;
        height: 192px;
        overflow-x: hidden;
        overflow-y: visible;
        margin-top: -1px;
    }
    ol, ul {
        list-style: none outside none;
    }
    .picture-data {
        float: left;
        width: 217px;
    }
    .monitor-listing li.department {
        background-position: 5px 3px;
    }
    .monitor-listing li {
        background:  #DDDDDD;
        color: #333333;
        margin: 2px 13px 0 0;
        padding: 6px 0 6px 10px;
        width: 255px;
    }
    .monitor-listing span {
        color: #0E76BD;
        margin: 0 5px;
    }
    .picture-before, .picture-after {
        float: left;
        margin: 0 10px 0 0;
        position: relative;
        width: 213px;
    }
    .picture-before img, .picture-after img {
        border: 3px solid #DDDDDD;
        float: left;
    }
    .picture-after-meta, .picture-before-meta {
        background-color: #DDDDDD;
        bottom: 3px;
        color: #333333;
        font-size: 13px;
        left: 3px;
        padding: 10px 0 10px 10px;
        position: absolute;
        text-transform: uppercase;
        width: 92.4%;
    }
    /*    a.zoomIcon {
            background: url("../../../assets/images/zoomicos.jpg") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
            bottom: 5px;
            cursor: pointer;
            display: block;
            height: 27px;
            position: absolute;
            right: 0;
            width: 29px;
        }*/

    html, body, div, span, applet, object, 
    iframe, h1, h2, h3, h4, h5, h6, p, blockquote,
    pre, a, abbr, acronym, address, big, cite, code, del, 
    dfn, em, img, ins, kbd, q, s, samp, small, strike, strong,
    sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, 
    li, fieldset, form, label, legend, table, caption, 
    tbody, tfoot, thead, tr, th, td, article, aside, canvas,
    details, embed, figure, figcaption, footer, header, hgroup, 
    menu, nav, output, ruby, section, summary, 
    time, mark, audio, video {
        padding: 0;

    }
    #siteNotice{
        float: left;
        margin-left: 0px;
    }

</style>
