$('#add_more_app').click(function() {
	$.colorbox({innerWidth: 485, innerHeight: 345, href: Settings.base_url+'add-new-app/'+Settings.login_department_id});
});

var department_options=[];
function getDepartments(){
    $.ajax({
        method: "POST",
                async: true,
        url: Settings.base_url+"app/get_departments",
        success: function(data) {
            arr=data.split(",");
            for (i = 0; i < arr.length; i++){
                department_options[i]=arr[i];
            }
        },
        async: false

    });
    return department_options;
}

$(document).ready(function() {
    var a = $("#application-listing-app").dataTable({
        "bSort": false,
        jQueryUI: true,
        "aoColumnDefs": [
            {"sType": "html", "aTargets": [0]}
        ],
        paginationType: "full_numbers"});
    
    $("#delete_app").on("click", function() {
        if (confirm("Are you sure you want to delete this App?")) {
            var b = $(this).attr("app_id");
            $.ajax({url: Settings.base_url+"app/delete/" + b, type: "POST",
                async: true, success: function(c) {
                    window.location.reload();
                }, });
        } else {
            return true;
        }
    });


});


/*
making datatable ajax loading
for apps view irfan
*/

$(document).ready(function() {

	if(Settings.super_admin){
	   $('.appajaxtable').dataTable( {
           "aaSorting": [[4,'desc']]
	       ,"lengthMenu": [[10,25, 50, 100], [10,25, 50, 100]]
	       ,"processing": true
	       ,"serverSide": true
	       ,"sPaginationType": "full_numbers"
	       ,"bJQueryUI":true
	//       ,"sAjaxDataProp": "aaData"
	
	       ,"aoColumns":[
	           {"mDataProp":"name"}
	           ,{"mDataProp":"icon","bSortable": false}
	           //if(Settings.super_admin){
	           ,{"mDataProp":"department_name","bSortable": false}
	           ,{"mDataProp":"user_name","bSortable": false}
	           //}
	           ,{"mDataProp":"created_datetime","bSortable": true}
	           ,{"mDataProp":"qr_code_file","bSortable": false}
	           ,{"mDataProp":"action","bSortable": false}
	       ]
	       ,"sAjaxSource": Settings.base_url+"app/ajaxApps"
	       ,"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
	           $('td:eq(1)', nRow).addClass('imagecenter');
	           $('td:eq(5)', nRow).addClass('imagecenter');
	           $('td:eq(2)', nRow).addClass('after');
	           $('td:eq(2)', nRow).attr('title', 'your new title');
	           return nRow;
	       }
	
	   } ).columnFilter({
	       sPlaceHolder: "head:before",
	       aoColumns: [
	           { sSelector:"#renderingNameFilter", type: "text"},
	           null,
	           //if(Settings.super_admin){
	           { sSelector:"#renderingDeptNameFilter", type: "select",values:getDepartments() },
	           { sSelector:"#renderingCreatedByFilter",type: "text" }
	           //}
	
	       ]
	
	   });
	}
	else{
	 $('.appajaxtable').dataTable( {
	       "lengthMenu": [[25, 50, 100], [25, 50, 100]]
	       ,"processing": true
	       ,"serverSide": true
	       ,"sPaginationType": "full_numbers"
	       ,"bJQueryUI":true
//		       ,"sAjaxDataProp": "aaData"

	       ,"aoColumns":[
	           {"mDataProp":"name"}
	           ,{"mDataProp":"icon","bSortable": false}
	           ,{"mDataProp":"created_datetime","bSortable": true}
	           ,{"mDataProp":"qr_code_file","bSortable": false}
	           ,{"mDataProp":"action","bSortable": false}
	       ]
	       ,"sAjaxSource": Settings.base_url+"app/ajaxApps"
	       ,"fnRowCallback": function( nRow, aData, iDisplayIndex ) {
	           $('td:eq(1)', nRow).addClass('imagecenter');
	           $('td:eq(5)', nRow).addClass('imagecenter');
	           $('td:eq(2)', nRow).addClass('after');
	           $('td:eq(2)', nRow).attr('title', 'your new title');
	           return nRow;
	       }

	   } ).columnFilter({
	       sPlaceHolder: "head:before",
	       aoColumns: [
	           { sSelector:"#renderingNameFilter", type: "text"},
	           null,
	       ]

	   });
	}
   $("#application-listing-app3_filter").hide();

});

