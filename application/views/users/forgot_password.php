<style>
    .teamWrap h2, .teamWrap h3 {
        display: inline-block;
        margin: 60px 0 !important;
    }

</style>
<div class="team">
    <div class="teamWrap">
        <h1> Password Recovery</h1>

        <?php echo form_open('forgotpassword', 'id="forgot_form" class="full validate "'); ?>

        <div class="message welcome">
            <ul>
                <div class="teamMember">
                    <li> 
                        <p>
                            <input type="text" class="" id="email" name="email" placeholder="Type Your Email ..."/>
                        </p>
                    </li>
                    <li> 
                        <p>
                            <button class="genericBtn">Submit</button>
                        </p>
                    </li>
                </div>
            </ul>
            <?php echo form_close(); ?>
        </div>
    </div>

    <script>

//        $(document).ready(function() {
//            $("#forgot_form").validate({
//                rules: {
//                    email: {required: true, email: true},
//                },
//                messages: {
//                    email: {required: "Email is required", email: "Invalid Email"},
//                }
//            });
//
//        });

    </script>

    <style>
        .teamMember{
            border-bottom: none;

        }
        .teamMember p input {
            background: none repeat scroll 0 0 padding-box #FFFFFF;
            border: 1px solid #0E76BD;
            color: #000000;
            display: inline;
            float: left;
            font-size: 12px;
            margin: 5px 0 10px;
            padding: 10px;
            width: 95%;
        }
        .teamMember p .genericBtn {
            float: left !important;
            margin-left: 0;
            margin-top: 4px;
            min-width: 70px;
            padding: 10px 0;
        }
        .teamMember p .genericBtn:hover {
                                        color:#d3caca;
                                    }
        p {
            color: #FF0000;
            font-size: 12px;
            margin: 0;
        }
    </style>
