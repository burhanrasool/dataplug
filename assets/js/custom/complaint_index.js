
$.datepicker.regional[""].dateFormat = 'yy-mm-dd';
$.datepicker.setDefaults($.datepicker.regional['']);
$(document).ready(function() {


	 var appaja = $('.appajaxtable').dataTable({
	       "lengthMenu": [[25, 50, 100], [25, 50, 100]]
	       ,"processing": true
	       ,"serverSide": true
	       ,"sPaginationType": "full_numbers"
	       ,"bJQueryUI":true
	       ,searchHighlight : true
           ,stateSave: false
	       ,"aoColumns":[
	           {"mDataProp":"image"},
	           {"mDataProp":"c_id"},
	           {"mDataProp":"c_date_time"},
	           {"mDataProp":"department_name"},
	           {"mDataProp":"app_name"},
	           {"mDataProp":"c_type"},
	           {"mDataProp":"c_title"},
	           {"mDataProp":"c_description"},
	           {"mDataProp":"c_status"},
	           {"mDataProp":"user_name"},
	           {"mDataProp":"action","bSortable": false}
	       ]
	       ,"dom": 'lBfrtip'
	       ,"buttons": [
	            {
	                extend: 'collection',
	                text: 'Export',
	                buttons: [
	                    {
	                        extend: "excel",
	                        text: 'Excel',
	                        exportOptions: {
	                            "columns": [ 1,2,3,4,5,6,7 ]
	                        }

	                    },
	                    {
	                        extend: "print",
	                        text: 'Print',
	                        exportOptions: {
	                            "columns": [ 1,2,3,4,5,6,7]
	                        }

	                    },
	                ]
	            }
	        ]
	       ,"aaSorting": [[1,'DESC']]
	       ,"sAjaxSource": Settings.base_url+"complaint/ajaxComplaints"

	   }).columnFilter({
	       sPlaceHolder: "head:before",
	       aoColumns: [
	       		null,
	           { sSelector:"#renderingComplaintId", type: "number"},
	           { sSelector:"#renderingComplaintDate", type: "date-range"},
	           { sSelector:"#renderingDeptNameFilter", type: "select",values:getDepartments() },
	           { sSelector:"#renderingApplicationFilter", type: "select",values:getApplications() },
	           { sSelector:"#renderingComplaintType", type: "select",values:['Telecom','Dashboard','Application','Others'] },
	           { sSelector:"#renderingComplaintTitle", type: "select",values:['Duplicate SIM','SIM blocked','Internet & Balance Issue','Signal Problem','Balance Deduction','Sim Mapping/Activation','Ownership Change','User Status Change','IMEI Update','Mark Leave','Login Credentials Issues','User Transferred','Dashboard Not Working','User Showing Absent on dashboard','Activities Missing','Data Not Showing','App not Working','App Crash/Foreced Stopped','APK Required','Unautherized User'] },
	           null,
	           { sSelector:"#renderingComplaintStatus", type: "select",values:['pending','processing','completed','closed'] },
	           //null,
	            //{ sSelector:"#renderingComplaintBy", type: "select",values:[{"value":"2","label":"zahid nadee"},{"value":"4","label":"zahid nad"},{"value":"5","label":"help desk"}]},
	           { sSelector:"#renderingComplaintBy", type: "select",values:getComplantsBy()},
	       ]
	
	   });
});


var department_options=[];
        function getDepartments(){
            $.ajax({
                method: "POST",
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

        var complaint_by_options=[];
        function getComplantsBy(){
            //console.log('aaa');
            $.ajax({
                method: "POST",
                url: Settings.base_url+"complaint/get_complaints_by",
                success: function(data) {
                    complaint_by_options=$.parseJSON(data);
                },
                async: false

            });
            return complaint_by_options;
        }

        var app_options=[];
        function getApplications(){
            $.ajax({
                method: "POST",
                url: Settings.base_url+"complaint/get_application_all",
                success: function(data) {
                    app_options=$.parseJSON(data);
                },
                async: false

            });
            return app_options;
        }