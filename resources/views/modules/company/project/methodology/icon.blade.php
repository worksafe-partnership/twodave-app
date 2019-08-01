<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Details</p>
        <div class="field">
            {{ EGForm::text('title', [
                'label' => 'Title',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::text('icon_main_heading', [
                'label' => 'Main Table Heading',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <table class="table is-bordered">
            <thead>
                <th colspan="5" id="top_heading">Main Table</th>
            </thead>
            <tbody id="top-body">
                <tr class="columns is-multiline" style="margin:0">
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::text('icon_sub_heading', [
                'label' => 'Sub Table Heading',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <table class="table is-bordered">
            <thead>
                <th colspan="5" id="sub_heading">Sub Table</th>
            </thead>
            <tbody id="bottom-body">
                <tr class="columns is-multiline" style="margin:0">
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::ckeditor('text_after', [
                'label' => 'Text After',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column">
                <p class="sub-heading">Add New Icon</p>
                {{ EGForm::radio('type', [
                    'label' => 'Type',
                    'list' => config('egc.icon_types'),
                    'type' => $pageType,
                ]) }}
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
                {{ EGForm::select('icon_list', [
                    'label' => 'Select Icon',
                    'value' => '',
                    'type' => $pageType,
                    'list' => config('egc.icons'),
                ]) }}
            </div>
            <div class="column is-2">
                <div>
                    <image id="image_preview" src="/gfx/icons/no_image.png" class="image-box">

                    </img>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                {{ EGForm::text('words', [
                    'label' => 'Wording',
                    'value' => '',
                    'type' => $pageType,
                ]) }}
            </div>
        </div>
    </div>
</div>



<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <button type="button" class="button is-primary" id="add-icon">Add Icon</button>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>


<script>
    let images = JSON.parse('{!! str_replace(['\'', '\\'], ['\\\'', '\\\\'], $iconImages) !!}');
    let iconSelect = JSON.parse('{!! str_replace(['\'', '\\'], ['\\\'', '\\\\'], json_encode($iconSelect)) !!}');

    function getImageSrc(key) {
        if (images[key]) {
            return images[key];
        } else {
            return '';
        }
    }

    $('#icon_list').on('change', function() {
        let src = getImageSrc( $(this).val() );
        if (src != "") {
            $('#image_preview').attr("src", "/"+src);
        }
    })

    $(document).on('change', '.td_icon_list', function() {
        let src = getImageSrc( $(this).val() );
        if (src != "") {
            let field = $(this).closest('.field');
            let image = $(field).find('.logo-img').attr("src", "/"+src);
        }
    })

    $('#methodology-icon-form-container #icon_main_heading').on('change keyup', function() {
        $('#top_heading').html($(this).val());
    })


    $('#methodology-icon-form-container #icon_sub_heading').on('change keyup', function() {
        $('#sub_heading').html($(this).val());
    })

    $('#add-icon').on('click', function() {
        let checked = $('input[name=type]:checked').val();

        if (checked && checked !== "undefined") {
            let table_location = '';
            if (checked == "MAIN") {
                table_location = 'top';
            } else {
                table_location = 'bottom';
            }
            let table_row = $('#' + table_location + '-body tr');
            let list_order = $('#' + table_location + '-body td').length;

            let td = '<td data-order="'+list_order+'" class="column is-3">\
                <div class="field">';

            let selectedImage = $('#icon_list').children("option:selected").val();
            let src = images[selectedImage];

            // add image box
            td += '<div class="image-box">\
                <image class="logo-img" src="/'+src+'"></image>\
            </div>'

            let select = '<label for="icon_list_">Select Icon:</label>\
            <div class="control">\
                <div class="select">\
                    <select name="icon_list_'+table_location+'_'+list_order+'" class="form-control td_icon_list">\
                        @foreach($iconSelect as $value => $label)
                            <option value="{{$value}}">{{$label}}</option>\
                        @endforeach
                        ';

            select += '</select>\
                </div>\
            </div>';

            td += select;

            let wording = '<div class="field">\
                            <label for="wording_'+table_location+'_'+list_order+'">Wording:</label>\
                            <div class="control">\
                                <input type="text" name="wording_'+table_location+'_'+list_order+'" class="form-control input wording" value="'+$('#words').val()+'">\
                            </div>\
                          </div>'

            td += wording;

            let buttons = '<div class="field">\
                            <a class="handms-icons move_left" onclick="moveIconLeft('+parseInt(list_order)+', \''+table_location+'\')"><svg class="eg-keyboard_arrow_left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-keyboard_arrow_left"></use></svg></a>\
                            <a class="handms-icons move_right" onclick="moveIconRight('+parseInt(list_order)+', \''+table_location+'\')"><svg class="eg-keyboard_arrow_right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-keyboard_arrow_right"></use></svg></a>\
                            <a class="handms-icons delete_icon" onclick="deleteIcon('+parseInt(list_order)+', \''+table_location+'\')"><svg class="eg-delete"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-delete"></use></svg></a>\
                          </div>'

            td += buttons;



            // close td and field class
            td += '</div>\
            </td>';

            $(table_row).append(td);

            // correct selection of select field after rendering
            let selectName = 'icon_list_'+table_location+"_"+list_order;
            $('[name='+selectName+']').val($('#icon_list').val());

            // wipe out the select fields
            $('input[name=type]')[0].checked = false;
            $('input[name=type]')[1].checked = false;
            $('#icon_list').prop('selectedIndex',0);

            $('#image_preview').attr("src", "/gfx/icons/no_image.png");
            $('#words').val('');
        } else {
            toastr.warning('Please select a table!');

        }
    });

    function renderTableData(row, list_order, loc, tdData)
    {
        let td = '<td data-order="'+list_order+'" class="column is-3">\
            <div class="field">';

            let src = images[tdData['image']];

            // add image box
            td += '<div class="image-box">\
                <image class="logo-img" src="/'+src+'"></image>\
            </div>'


        let select = '<label for="icon_list_">Select Icon:</label>\
        <div class="control">\
            <div class="select">\
                <select name="icon_list_'+loc+'_'+list_order+'" class="form-control td_icon_list">\
                    @foreach($iconSelect as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>\
                    @endforeach
                    ';

        select += '</select>\
            </div>\
        </div>';

        td += select;

        let wording = '<div class="field">\
                        <label for="wording">Wording:</label>\
                        <div class="control">\
                            <input type="text" name="wording_'+loc+'_'+list_order+'" class="form-control input wording" value="'+tdData['text']+'">\
                        </div>\
                      </div>'

        td += wording;

        let buttons = '<div class="field">\
                        <a class="handms-icons move_left" onclick="moveIconLeft('+parseInt(list_order)+', \''+loc+'\')"><svg class="eg-keyboard_arrow_left"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-keyboard_arrow_left"></use></svg></a>\
                        <a class="handms-icons move_right" onclick="moveIconRight('+parseInt(list_order)+', \''+loc+'\')"><svg class="eg-keyboard_arrow_right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-keyboard_arrow_right"></use></svg></a>\
                        <a class="handms-icons delete_icon" onclick="deleteIcon('+parseInt(list_order)+', \''+loc+'\')"><svg class="eg-delete"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/eg-icons.svg#eg-delete"></use></svg></a>\
                      </div>'

        td += buttons;

        td += '</field>\
            </div>\
        </div>';

        return td;
    }

    function moveIconLeft(key, table) {
        moveIcon(key, table, 'left');
    }

    function moveIconRight(key, table) {
        moveIcon(key, table, 'right');
    }

    function moveIcon(key, table, direction) {
        let tds = $('#' + table + '-body td');
        if (tds.length == 1) {
            toastr.warning('Cannot move if only one icon!');
        } else {
            if (direction == "left") {
                if (key != 0) {
                    let leftKey = key-1;
                    let leftTd = $('#'+table+'-body td[data-order='+leftKey+']')
                    let td = $('#'+table+'-body td[data-order='+key+']');
                    td.after(leftTd);

                    leftTd.attr('data-order', key);
                    leftTd.find('.td_icon_list').attr('name', 'icon_list_'+table+'_'+key);
                    leftTd.find('.wording').attr('name', 'wording_'+table+'_'+key);
                    leftTd.find('.move_left').attr("onclick", "moveIconLeft('"+key+"', '"+table+"')");
                    leftTd.find('.move_right').attr("onclick", "moveIconRight('"+key+"', '"+table+"')");
                    leftTd.find('.delete_icon').attr('onclick', "deleteIcon('"+key+"', '"+table+"')");

                    td.attr('data-order', leftKey);
                    td.find('.td_icon_list').attr('name', 'icon_list_'+table+'_'+leftKey);
                    td.find('.wording').attr('name', 'wording_'+table+'_'+leftKey);
                    td.find('.move_left').attr("onclick", "moveIconLeft('"+leftKey+"', '"+table+"')");
                    td.find('.move_right').attr("onclick", "moveIconRight('"+leftKey+"', '"+table+"')");
                    td.find('.delete_icon').attr('onclick', "deleteIcon('"+leftKey+"', '"+table+"')");
                } else {
                    toastr.warning('Cannot move first icon left!');
                }
            } else { // right
                if (key < tds.length-1) {
                    let rightKey = parseInt(key)+1;
                    let rightTd = $('#'+table+'-body td[data-order='+rightKey+']');
                    let td = $('#'+table+'-body td[data-order='+key+']');
                    rightTd.after(td);

                    rightTd.attr('data-order', key);
                    rightTd.find('.td_icon_list').attr('name', 'icon_list_'+table+'_'+key);
                    rightTd.find('.wording').attr('name', 'wording_'+table+'_'+key);
                    rightTd.find('.move_left').attr("onclick", "moveIconLeft('"+key+"', '"+table+"')");
                    rightTd.find('.move_right').attr("onclick", "moveIconRight('"+key+"', '"+table+"')");
                    rightTd.find('.delete_icon').attr('onclick', "deleteIcon('"+key+"', '"+table+"')");

                    td.attr('data-order', rightKey);
                    td.find('.td_icon_list').attr('name', 'icon_list_'+table+'_'+rightKey);
                    td.find('.wording').attr('name', 'wording_'+table+'_'+rightKey);
                    td.find('.move_left').attr("onclick", "moveIconLeft('"+rightKey+"', '"+table+"')");
                    td.find('.move_right').attr("onclick", "moveIconRight('"+rightKey+"', '"+table+"')");
                    td.find('.delete_icon').attr('onclick', "deleteIcon('"+rightKey+"', '"+table+"')");
                } else {
                    toastr.warning('Cannot move last icon right!');
                }
            }
        }
    }

    function deleteIcon(key, table) {
        let deleted = $('#' + table + '-body '+"td[data-order='"+key+"']").remove();
        let remaining = $('#' + table + '-body td');
        $.each(remaining, function(index, td) {
            if (index >= key) { // only need to update those which are higher than the deleted one.
                let cell = $(td);
                cell.attr('data-order', index);
                cell.find('.td_icon_list').attr('name', 'icon_list_'+table+'_'+index);
                cell.find('.wording').attr('name', 'wording_'+table+'_'+index);
                cell.find('.move_left').attr("onclick", "moveIconLeft('"+index+"', '"+table+"')");
                cell.find('.move_right').attr("onclick", "moveIconRight('"+index+"', '"+table+"')");
                cell.find('.delete_icon').attr('onclick', "deleteIcon('"+index+"', '"+table+"')");
            }
        });
    }
</script>


@push('styles')
    <style>
        .radio-inline {
            padding-right: 15px;
        }

        .image-box {
            height: 100px;
            width: 100px;
            border: 1px solid black;
        }
    </style>
@endpush
