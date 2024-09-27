<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Create Dropdown API URL</h2>
            <input type="hidden" name="api_secret" id="api_secret"
				value="<?php echo $api_secret; ?>"/>
            <table cellpadding="0" cellspacing="0" border="0" class="display"
				id="example" style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 230px;">
                <tbody>
                    <tr>
                        <td>

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Value</strong>
                                </label>
                                <div>
                                    <select name="mainvalue" id="mainvalue" class="textBoxLogin">
                                        <option value="">Select Value</option>
                                        <?php foreach ($fields_name as $fields) {?>
                                            <option value="<?php echo $fields; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                               
                            </div>
                            <br />
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Parent Value</strong>
                                </label>
                                <div>
                                    <select name="parentvalue" id="parentvalue" class="textBoxLogin">
                                        <option value="">Select Parent Value</option>
                                        <?php foreach ($fields_name as $fields) {?>
                                            <option value="<?php echo $fields; ?>"><?php echo $fields; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                 <label for="d1_textfield">
                                    <strong>Sorting Status</strong>
                                </label>
                                <div>
                                    <select name="sortvalue" id="sortvalue" class="textBoxLogin">
                                            <option value="on">On</option>
                                            <option value="off">Off</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" >
                                
                                <label for="d1_textfield">
                                    <strong>URL : </strong>
                                </label>
                                <div id="urldiv" style="margin-top: 15px; background-color: yellow;"
									onclick="select_all(this)">
                                    
                                </div>

                            </div>
                            <div class="row" >
                                
                                <label for="d1_textfield">
                                    <strong> </strong>
                                </label>
                                <div>
                                    <a href="<?php echo base_url().'apimaker'?>"  class="genericBtn" style="">
                                    Back
                                    </a>
                                    <a href="" id="checkurl" target="_new" 
										class="genericBtn" style="display: none;">
                                    Click for Check API
                                    </a>
                                </div>
                            </div>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="text/javascript">

</script>


<script type="text/javascript">
    var Settings = {
        base_url: '<?php echo base_url(); ?>',
        api_id: '<?php echo $api_id;?>'
    }
</script>

<style>
    .error p{
        color: red;
        font-weight: bold;
    }
    #availability_status {
        color: green;
    }
       .row div{
        float: left;
    }
    .row label{
        display: block;
        float: left;
        width: 148px;
    }
    .row div input{
        float: none;

    }

    .row {
        display: block;
        float: left;

        color:#333333;
        font-size: 100%;
        clear: both;
    }
    tr:hover{
        background-color: #FFFFFF;
    }


    h2{
        font-size: 2em;
        margin-bottom: 24px;
        color: #333333;
    }
</style>