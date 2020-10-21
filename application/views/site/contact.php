<div id="container">
    <div class="inner-wrap">
        <div class="table-sec">
            <h2>Contact</h2>

            <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
                <tbody>
                    <tr>
                        <td>
                            <?php echo form_open(base_url() . 'Contact', 'id="contact_form" class="full validate add_task_form " enctype="multipart/form-data"'); ?>

                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Name </strong>
                                </label>
                                <div>
                                    <input class="required" type="text" name="name" id="name" />
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Email </strong>
                                </label>
                                <div>
                                    <input class="required" type="email" name="email" id="email" />
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Subject</strong>
                                </label>
                                <div>
                                    <input class="required" type="text" name="subject" id="subject" />
                                    <span id='availability_status'></span>
                                </div>
                            </div>
                            <div class="row">
                                <label for="d1_textfield">
                                    <strong>Message</strong>
                                </label>
                                <div>
                                    <textarea type="text" name="message" id="message" /></textarea>
                                    <span id='availability_status'></span>
                                </div>
                            </div>

                            <div class="actions">
                                <div class="right">
                                    <button class="genericBtn">Send</button>
                                </div>

                            </div>

                            <?php echo form_close(); ?>
                        </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>

    $(document).ready(function() {
        $("#contact_form").validate({
                rules: {
                    name: "required",
                    email: {"required":true,"type":"email"},
                    subject: "required",
                    message: "required",
                    
                },
                messages: {
                    name:"Name is required",
                    email:{"required":"Email is required","type":"Invalid email"},
                    subject:"Subject is required",
                    message:"Message is required",
            
                }
            });
       
    });
    
</script>
<style>
    .error p{
        color: red;
        font-weight: bold;
    }
    #availability_status {
        color: green;
    }
</style>