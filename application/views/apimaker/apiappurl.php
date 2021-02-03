<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Create API URL</h2>
            <input type="hidden" name="app_secret" 
			id="app_secret" value="<?php echo $app_secret; ?>"/>
            <input type="hidden" name="app_id" id="app_id" value="<?php echo $app_id; ?>"/>
            <table cellpadding="0" cellspacing="0" border="0"
			class="display" id="example" 
			style="box-shadow: 0 0 6px rgba(0, 0, 0, 0.25);min-height: 230px;">
                <tbody>
                    <tr>
                        <td>
                            <div class="row" style="margin-top: 20px;">
                                <label for="d1_textfield">
                                    <strong>From </strong>
                                </label>
                                <div>
                                    <input size ="15" type="text" id="from_date" value=""
									name="from_date"  onchange="check_date_validity()"
									ondblclick="clear_field(this)" class="textBoxLogin">
                                </div>

                            </div>

                            <div class="row" >
                                <label for="d1_textfield">
                                    <strong>To </strong>
                                </label>
                                <div>
                                    <input size="15" type="text" id="to_date" value=""
									name="to_date"  onchange="check_date_validity()"
									ondblclick="clear_field(this)" class="textBoxLogin">
                                </div>

                            </div>
                            <div class="row" style="text-align: center;">

                                <div id="urldiv" style="margin-top: 15px; background-color: yellow;"
									onclick="select_all(this)">

                                </div>

                            </div>
                            <div class="row" style="margin-top: 20px;">

                                <label for="d1_textfield">
                                    <strong> </strong>
                                </label>
                                <div>
                                    <a href="<?php echo base_url() .
										'app-landing-page/' . $app_id ?>"  class="genericBtn" style="">
                                        Back
                                    </a>
                                    <a href="" id="checkurl" target="_new" class="genericBtn" style="">
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
    function select_all(el) {
        if (typeof window.getSelection != "undefined" 
				&& typeof document.createRange != "undefined") {
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        } else if (typeof document.selection != "undefined" 
				&& typeof document.body.createTextRange != "undefined") {
            var textRange = document.body.createTextRange();
            textRange.moveToElementText(el);
            textRange.select();
        }
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