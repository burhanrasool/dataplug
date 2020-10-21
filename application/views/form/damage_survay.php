

<input type="button" value="Go Back" onclick="history.back();" style="width: 100px; margin-bottom: 10px; height: 30px; color: blue; font-size: 18px;">

<div>
    <div id="container">
        <div class="tableContainer">
            <div class="success"></div>
            <div style="overflow: auto">
                <form accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?= base_url() . 'form/edit_form_result_ajax'; ?>" id='form_edit'>
                    <table cellspacing="0" cellpadding="0" id="application-listing-listview" class="display">
                        <thead>
                            <tr>

                                <th class="Categoryh">S. No</th>
                                <th class="Categoryh">District</th>
                                <th class="Categoryh">Uploaded crop affectees</th>
                                <th class="Categoryh">Validated crop damaged affectee</th>
                                <th class="Categoryh">Rejected in TPV and Internal validation</th>
                                <th class="Categoryh">Damaged crop uploaded - Area in Acres</th>
                                <th class="Categoryh">Crop area validated Acres</th>
                                <th class="Categoryh">Damaged Houses - uploaded</th>
                                <th class="Categoryh">Damaged houses validated</th>
                                <th class="Categoryh">House data rejected</th>

                            </tr>
                        </thead>

                        <tbody>


                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >1</td>
                                <td class="Category " style="position:relative;" >Dera Ghazi Khan</td>
                                <td class="Category " style="position:relative;" > 2,307</td>
                                <td class="Category " style="position:relative;" >1,991</td>
                                <td class="Category " style="position:relative;" > 	 316</td>
                                <td class="Category " style="position:relative;" > 	 13,549</td>
                                <td class="Category " style="position:relative;" > 	 11,410</td>
                                <td class="Category " style="position:relative;" > 	 1,055</td>
                                <td class="Category " style="position:relative;" > 	 810</td>
                                <td class="Category " style="position:relative;" > 	 245 
</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >2</td>
                                <td class="Category " style="position:relative;" >Khushab</td>
                                <td class="Category " style="position:relative;" >2,498</td>
                                <td class="Category " style="position:relative;" > 	 2,135</td>
                                <td class="Category " style="position:relative;" > 	 363</td>
                                <td class="Category " style="position:relative;" > 	 18,600</td>
                                <td class="Category " style="position:relative;" > 	 15,700</td>
                                <td class="Category " style="position:relative;" > 	 4,230</td>
                                <td class="Category " style="position:relative;" > 	 1,993</td>
                                <td class="Category " style="position:relative;" > 	 2,237</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >3</td>
                                <td class="Category " style="position:relative;" >Layyah</td>
                                <td class="Category " style="position:relative;" >14,449 </td>
                                <td class="Category " style="position:relative;" > 	 11,367</td>
                                <td class="Category " style="position:relative;" > 	 3,082</td>
                                <td class="Category " style="position:relative;" > 	 62,890</td>
                                <td class="Category " style="position:relative;" > 	 44,530</td>
                                <td class="Category " style="position:relative;" > 	 886</td>
                                <td class="Category " style="position:relative;" > 	 567</td>
                                <td class="Category " style="position:relative;" > 	 319</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >4</td>
                                <td class="Category " style="position:relative;" > Mianwali</td>
                                <td class="Category " style="position:relative;" >1,456</td>
                                <td class="Category " style="position:relative;" > 	 1,305</td>
                                <td class="Category " style="position:relative;" > 	 151</td>
                                <td class="Category " style="position:relative;" > 	 10,963</td>
                                <td class="Category " style="position:relative;" > 	 9,926</td>
                                <td class="Category " style="position:relative;" > 	 5,580</td>
                                <td class="Category " style="position:relative;" > 	 5,299</td>
                                <td class="Category " style="position:relative;" > 	 281</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >5</td>
                                <td class="Category " style="position:relative;" >Muzaffargarh</td>
                                <td class="Category " style="position:relative;" >4,559</td>
                                <td class="Category " style="position:relative;" > 	 3,642</td>
                                <td class="Category " style="position:relative;" > 	 917</td>
                                <td class="Category " style="position:relative;" > 	 23,619</td>
                                <td class="Category " style="position:relative;" > 	 18,669</td>
                                <td class="Category " style="position:relative;" > 	 2,843</td>
                                <td class="Category " style="position:relative;" > 	 145</td>
                                <td class="Category " style="position:relative;" > 	 2,698</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >6</td>
                                <td class="Category " style="position:relative;" >Rahim Yar Khan</td>
                                <td class="Category " style="position:relative;" >7,070</td>
                                <td class="Category " style="position:relative;" > 	 5,645</td>
                                <td class="Category " style="position:relative;" > 	 1,425</td>
                                <td class="Category " style="position:relative;" > 	 23,519</td>
                                <td class="Category " style="position:relative;" > 	 19,119</td>
                                <td class="Category " style="position:relative;" > 	 219</td>
                                <td class="Category " style="position:relative;" > 	 216</td>
                                <td class="Category " style="position:relative;" > 	 3</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" >7</td>
                                <td class="Category " style="position:relative;" >Rajanpur</td>
                                <td class="Category " style="position:relative;" >5,537</td>
                                <td class="Category " style="position:relative;" > 	 4,854</td>
                                <td class="Category " style="position:relative;" > 	 683</td>
                                <td class="Category " style="position:relative;" > 	 41,113</td>
                                <td class="Category " style="position:relative;" > 	 30,391</td>
                                <td class="Category " style="position:relative;" > 	 7,621</td>
                                <td class="Category " style="position:relative;" > 	 6,463</td>
                                <td class="Category " style="position:relative;" > 	 1,158</td>
                            </tr>
                            <tr class="trSelect" id="">
                                <td class="Category " style="position:relative;" ></td>
                                <td class="Category " style="position:relative;" >Total </td>
                                <td class="Category " style="position:relative;" >37,876</td>
                                <td class="Category " style="position:relative;" > 	 30,939</td>
                                <td class="Category " style="position:relative;" > 	 6,937</td>
                                <td class="Category " style="position:relative;" > 	 194,253</td>
                                <td class="Category " style="position:relative;" > 	 149,745</td>
                                <td class="Category " style="position:relative;" > 	 22,434</td>
                                <td class="Category " style="position:relative;" > 	 15,493</td>
                                <td class="Category " style="position:relative;" > 	 6,941</td>
                            </tr>
                            


                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>



    <style>
        
        .Categoryh{
            background: none;
                height: 45px;
    padding: 10px 0 11px 10px;
        }
        .Category{
            height: 30px;
        }
        #filter_span {
            margin: 0px 0px 0 204px;
        }
        #filter_span_district {
            margin: 0px 0 0 0px;
        }
        #overlay_loading img{
            margin: 20% 0 0 47%;
        }
        #cat_filter{
            width:130px;
            max-width: 130px;
        }
        #voilation{
            width:188px;
            max-width: 105px;
        }
        .Category a {
            padding: 0;
        }

        .ui-state-default{
            background: none;
        }
        .ui-dropdownchecklist{
            width: 200px;
        }
        .ui-dropdownchecklist-text {
            color: #000000;
            font-size: 14px !important;
        }
        .ui-dropdownchecklist-selector{
            padding:1px !important;
        }
        .element.style {
            cursor: default;
            display: inline-block;
            overflow: hidden;
        }
        #ddcl-cat_filter {
            width: 110px;
        }
        #form_lists {
            width: 95px;
            margin-top: 2px;
        }
        #ddcl-voilation {
            width: 110px;
        }
        .ui-dropdownchecklist {
            width: 30%;
        }
        .ui-dropdownchecklist-selector-wrapper, .ui-widget .ui-dropdownchecklist-selector-wrapper {
            margin-bottom: 2px;
        }
        .ms-parent li{
            color: #7C7B7B;
            font-size: 11px;
        }

        .ms-choice{
            width: 130px !important;
        }
        .crimeListTexta {
            color: #777777;
            display: inline;
            float: left;
            margin: 7px 2px -33px 0;
            position: relative;
        }
        #filter,#town_filter,#district_list,#sent_by_list,#form_list{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;

            color: #444444;
            cursor: pointer;
            height: 26px;
            line-height: 26px;
            overflow: hidden;
            padding: 0;
            text-align: left;
            margin-right: 0;
            text-decoration: none;
            white-space: nowrap;
            max-width: 141px;
            width: 135px;
        }
        .form_class div{
            position: relative;
            display: inline;
        }
        #datepicker,#datepicker2{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;
            color: #444444;
            cursor: pointer;
            height: 22px;
            line-height: 26px;
            overflow: hidden;
            padding-left:20px;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
        }
        #search_text{
            background-color: #FFFFFF;
            border: 1px solid #0E76BD;
            color: #444444;
            height: 22px;
            line-height: 26px;
            overflow: hidden;
            padding-left:10px;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
        }
        #search_text_span{
            margin-left: 38px;

        }
        .ui-widget-content {
            background: url("images/ui-bg_flat_75_ffffff_40x100.png") repeat-x scroll 50% 50% #ffffff !important;
            border: medium none !important;
            color: #222222 !important;
        }

        input.genericBtnreset {
            background: none repeat scroll 0 0 #2da5da;
            border: medium none;
            color: #fff;
            cursor: pointer;
            float: right;
            margin-left: 10px;
            margin-right: 3px;
            outline: medium none;
            padding: 5px 20px;
        }
        .export_div {
            right: 170px;
            position:static !important;
        }

        #filter_customized{
            background-color: #ffffff;
            border: 1px solid #0e76bd;
            color: #444444;
            cursor: pointer;
            height: 26px;
            line-height: 26px;
            margin-right: 0;
            max-width: 141px;
            overflow: hidden;
            padding: 0;
            text-align: left;
            text-decoration: none;
            white-space: nowrap;
            width: 135px;
        }
        table.display thead th div.DataTables_sort_wrapper span{
            display: none;
        }
    </style>