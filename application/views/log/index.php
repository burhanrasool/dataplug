<style>.applicationText a{position:relative;}.Category a{text-decoration:underline!important;}#cboxCurrent{display:none!important;}</style>
<div class="applicationText">

    <br clear="all" />
</div>
<div class="tableContainer" style="width:109%;">
    <div>
        <table cellspacing="0" cellpadding="0" id="application-listing-app" class="display logajaxtable">
            <thead>
                <tr>
                    <th class="Categoryh">User Name</th>
                    <th class="Categoryh">Department Name</th>
                    <th class="Categoryh">Action</th>
                    <th class="Categoryh">Action Description</th>
                    <th class="Categoryh">Before</th>
                    <th class="Categoryh">After</th>
                    <th class="Categoryh">Application Name</th>
                    <th class="Categoryh">Form</th>
                    <th class="Categoryh">Controller</th>
                    <th class="Categoryh">Method</th>

                    <th class="Categoryh">Created Date Time</th>
                </tr>
            </thead>

            <tfoot>
            <tr>
                <th class="Categoryh">User Name</th>
                <th class="Categoryh">Department Name</th>
                <th class="Categoryh">Action</th>
                <th class="Categoryh">Action Description</th>
                <th class="Categoryh">Before</th>
                <th class="Categoryh">After</th>
                <th class="Categoryh">Application Name</th>
                <th class="Categoryh">Form</th>
                <th class="Categoryh">Controller</th>
                <th class="Categoryh">Method</th>

                <th class="Categoryh">Created Date Time</th>
            </tr>
            </tfoot>

        </table>


    </div>
</div>
<div id="detail_dialogue" title="Information">
    <?php echo '<pre>'; ?>
    <p>

    </p>
</div>
<style>
    .ui-dialog .ui-dialog-titlebar-close span{
        margin:-8px;
    }
    .after,.before{
        cursor: pointer;
    }
    .tableContainer{
        overflow: auto;
    }
    table.display thead th{
        cursor: initial;
    }
    #restult_div{
        color: #6F5555;
    }
    #pagination{
        float: left;
        font:11px Tahoma, Verdana, Arial, "Trebuchet MS", Helvetica, sans-serif;
        color:#3d3d3d;
        margin-top: 20px;
        margin-left:auto;
        margin-right:auto;
        margin-bottom:20px;
        width:100%;
    }
    #pagination a, #pagination strong{
        list-style-type: none;
        display: inline;
        padding: 5px 8px;
        text-decoration: none; 
        background-color: #dcdcdc;
        color: #EC7117;
        font-weight: bold;
    }
    #pagination strong{
        color: #ffffff;
        background-color:#0E76BD;
        background-position: top center;
        background-repeat: no-repeat;
        text-decoration: none; 
    }
    #pagination a:hover{
        color: #ffffff;
        background-color:#E59C2B;
        background-position: top center;
        background-repeat: no-repeat;
        text-decoration: none; 
    }

    .text_filter{
        width:40px;
        border:solid 1px #CCC;
        color: #c4c4c4;
    }

    .text_filter:focus{
        color: #000000;
    }
    .date_range_filter{
        width:80px;
    }

</style>