 $('#add_more_app').click(function() {
    $.colorbox({innerWidth: 485, innerHeight: 290, href:Settings.base_url+'/add-new-api'});
});
$('.edit_api').click(function() {
var api_id = $(this).attr('api_id');
var urlcb = Settings.base_url+'edit-new-api/'+api_id;
    $.colorbox({innerWidth: 485, innerHeight: 290, href: urlcb});
});
        
$(document).ready(function() {
    var a = $("#application-listing-app").dataTable({
        "bSort": false,
        jQueryUI: true,
        "aoColumnDefs": [
            {"sType": "html", "aTargets": [0]}
        ],
        paginationType: "full_numbers"});
    jQuery("#delete_api").live("click", function() {
        if (confirm("Are you sure you want to delete this Api?")) {
            var b = jQuery(this).attr("api_id");
            jQuery.ajax({url: Settings.base_url+"apimaker/delete/" + b, type: "POST", success: function(c) {
                    window.location.reload();
                }, });
        } else {
            return true;
        }
    });
});