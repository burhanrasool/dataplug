var baseurl = $("#base_url").val();
$("#edit_app_popup").click(function () {
    var a = $("#app_id").val();
    $.colorbox({innerWidth: 485, innerHeight: 275, href: baseurl + "app/editpopup/" + a})
});
$("#add_more_form,#add_more_form_left").click(function () {
    var a = $("#app_id").val();
    $.colorbox({innerWidth: 485, innerHeight: 275, href: baseurl + "form/add/" + a})
});
$(".copy_form").click(function () {
    var app_id = $(this).attr('app_id');
    var form_id = $(this).attr('form_id');
    $.colorbox({innerWidth: 485, innerHeight: 275, href: baseurl + "form/copypopup/" + app_id +'_'+form_id})
});