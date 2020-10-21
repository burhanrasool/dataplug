$('#text').live('click', function() {
    var i = 1;
    $("input[type='text']").each(function() {
        if ($('#text_' + i).length == 0)
        {
            return false;
        }
        i++;
    });

    var textWidget = getTextWidget(i);
    $('#form').append(textWidget);
    return false;

});

$('#select').live('click', function() {
    var i = 1;
    $("select").each(function() {
        if ($('#select_' + i).length == 0)
        {
            return false;
        }
        i++;
    });
    var selectWidget = getSelectWidget(i);
    $('#form').append(selectWidget);
    return false;
});



function getTextWidget(id)
{
    return '<div id="textWidget_' + id + '" onclick="return setattributes(\'text\',' + id + ');"><label id="text_label_' + id + '">Label here</label><input type="text" id="text_' + id + '" name=""><div onclick="return removeElements(\'text\',' + id + ');">remove</div></div>';
}

function getSelectWidget(id)
{
    return '<div id="selectWidget_' + id + '" onclick="return setattributes(\'select\',' + id + ');"><label id="select_label_' + id + '">Label here</label><select id="select_' + id + '" name=""></select><div onclick="return removeElements(\'select\',' + id + ');">remove</div></div>';
}

function removeElements(type, id)
{
    if (type == 'text')
    {
        $('#textWidget_' + id).remove();
        return true;
    }
    else if (type == 'select')
    {
        $('#selectWidget_' + id).remove();
        return true;
    }
}

function setattributes(type, id)
{
    if (type == 'text')
    {
        //var nameWidget = name_attr(type,id);
        var nameWidget = 'Name<br /><input type="text" id="text_attr_name_' + id + '" onkeyup="return name_attr(\'text\',' + id + ');"><br />';
        var labelWidget = 'Label<br /><input type="text" id="text_attr_label_' + id + '" onkeyup="return label_attr(\'text\',' + id + ');">';
        $('#attributes').html(nameWidget + labelWidget);
        $('#text_attr_name_' + id).val($('#text_' + id).attr('name'));
        $('#text_attr_label_' + id).val($('#text_label_' + id).html());

    }
    else if (type == 'select')
    {
        //var nameWidget = name_attr(type,id);
        var nameWidget = 'Name<br /><input type="text" id="select_attr_name_' + id + '" onkeyup="return name_attr(\'select\',' + id + ');"><br />';
        var labelWidget = 'Label<br /><input type="text" id="select_attr_label_' + id + '" onkeyup="return label_attr(\'select\',' + id + ');">';
        $('#attributes').html(nameWidget + labelWidget);
        $('#select_attr_name_' + id).val($('#select_' + id).attr('name'));
        $('#select_attr_label_' + id).val($('#select_label_' + id).html());

    }
}

function name_attr(type, id)
{
    if (type == 'text')
    {
        var nameval = $('#text_attr_name_' + id).val();
        $('#text_' + id).attr('name', nameval);
    }
    else if (type == 'select')
    {
        var nameval = $('#select_attr_name_' + id).val();
        $('#select_' + id).attr('name', nameval);
    }


}
function label_attr(type, id)
{
    if (type == 'text')
    {
        var labelval = $('#text_attr_label_' + id).val();
        $('#text_label_' + id).html(labelval);
    }
    else if (type == 'select')
    {
        var labelval = $('#select_attr_label_' + id).val();
        $('#select_label_' + id).html(labelval);
    }
}