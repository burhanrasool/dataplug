<div class="team">
    <div class="summaryWrap">

      <div class="heading-title">
      <div style="float: left;"><a href="<?= base_url() ?>complaintSystem" class="genericBtnSubmit" style="width: 100%" >Back</a></div>
        <h1>Summary</h1>
        <small>Review Summary for details</small>

      </div>
        <ul>
            <div class="summaryTier">
               
               <li class="greeny"> 

                  <h3>Complaint No.</h3>
                  <strong><?php echo $complaint_info['c_id'];?></strong>   
            
                </li>
                <li> 

                  <h3>Created Date </h3>
                  <strong><?php echo $complaint_info['c_date_time'];?></strong>   
            
                </li>
                <li> 

                  <h3>Complaint Type </h3>
                  <strong><?php echo $complaint_info['c_type'];?></strong>   
            
                </li>

                 <li> 

                  <h3>Resolution Time </h3>
                  <strong><?php echo $complaint_info['c_resolution_time'];?></strong>   
            
                </li>
  <li> 

                  <h3>Application Name</h3>
                  <strong><?php echo $complaint_info['app_name'];?></strong>   
            
                </li>

                 <li> 

                  <h3>Added by</h3>
                  <strong><?php echo $complaint_info['user_name'];?></strong>   
            
                </li>
                 

                  <li class="full"> 

                  <h3>Complaint Title</h3>
                  <strong><?php echo $complaint_info['c_title'];?></strong>   
            
                </li>



              

                 <li class="full"> 

                  <h3>Complaint Description </h3>
                  <p><?php echo $complaint_info['c_description'];?>

                  </p>   
            
                </li>
                <li class="full"> 

                  <h3>Complaint Status </h3>
                  <form method="post" action="<?php echo base_url();?>complaint/change_status">
                  <input type="hidden" name="complaint_id" value="<?php echo $complaint_info['c_id'];?>">

                  <?php if($this->acl->hasPermission('complaint', 'edit')) {?>
                    <strong style="vertical-align: top;">
                      <select id="c_status" name="c_status">
                        <option value="pending" <?php if($complaint_info['c_status']=='pending'){echo "selected";}?>>Pending</option>
                        <option value="processing" <?php if($complaint_info['c_status']=='processing'){echo "selected";}?>>Processing</option>
                        <option value="completed" <?php if($complaint_info['c_status']=='completed'){echo "selected";}?>>Completed</option>
                        <option value="closed" <?php if($complaint_info['c_status']=='closed'){echo "selected";}?>>Close</option>
                      </select>
                    </strong> 
                    <??>
                    <input type="submit" id="submit_change_status" name="" style="display: none;" value="Save Changed Status">

                    <textarea name="c_description" id="c_description" style="margin: 12px;display: none;" rows="6" cols="100%" placeholder="Enter comments"></textarea>  
                    <?php }else{ ?>
                        <strong style="vertical-align: top;"><?php echo $complaint_info['c_status'];?></strong>
                    <?php }?>
                  </form>
                </li>
                <?php if($complaint_info['c_type']=='Telecom'){?>
                  <li class="full"> 

                    <h3>Send Email to Telco's </h3>
                    <form method="post" action="<?php echo base_url();?>complaint/send_email_telco">
                    <input type="hidden" name="complaint_id" value="<?php echo $complaint_info['c_id'];?>">

                    <?php if($this->acl->hasPermission('complaint', 'edit')) {?>
                      
                      <strong><span style="color:red"> * </span>Subject </strong>
                      <input type="text" name="subject" value="<?php echo $complaint_info['c_type']." - ". $complaint_info['c_title'];?>">
                      <textarea name="message" id="message" style="margin: 12px;" rows="6" cols="100%" >
<?php echo strip_tags($complaint_info['c_description']);?>
                      </textarea>  
                      <br />
                      <input type="submit" id="send_email_button" name="" style="" value="Send Email">
                      <?php } ?>
                          
                    </form>
                  </li>
                <?php }?>
            </div>

        </ul>



        <div class="heading-title">
        <h1>History</h1>
        <small>Review History for details</small>

      </div>
        <ul>
            <div class="summaryTier history">

            <?php foreach($complaint_history as $history){?>
                <li class="full off-white"> 

                  <h3><?php echo $history['ch_status'];?></h3>
                  <strong><?php echo date('M, d Y',strtotime($history['ch_datetime']));?> | <?php echo date('H:i',strtotime($history['ch_datetime']));?> | by <?php echo $history['user_name'];?></strong> <br>  
                  <strong><?php echo $history['ch_description'];?></strong>
                </li>



            <?php }?>
          
            </div>

        </ul>
    </div>
</div>




<script type="text/javascript">
    var Settings = {
        base_url: '<?php echo base_url(); ?>',
        old_status: '<?php echo $complaint_info['c_status'];?>'
    }
</script>