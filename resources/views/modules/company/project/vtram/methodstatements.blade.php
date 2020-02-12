<div class="columns">
    <div class="column is-12">
        <h2 class="sub-heading inline-block">Method Statement</h2>
        <div class="is-pulled-right">
            <div class="meth-type-selector">
                {{ EGForm::select('meth_type', [
                    'label' => 'Select Type',
                    'list' => $methTypeList,
                    'value' => '',
                    'type' => 'create',
                    'selector' => true,
                ]) }}
            </div>
            <a title="Add Method Statement" href="javascript:createMethodology()" class="button is-success is-pulled-right">
                {{ icon('plus2') }}<span class="action-text is-hidden-touch"></span>
            </a>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-12 nopad">
        <div id="main-methodology-container">
            <div id="methodology-list-container">
                <table class="methodology-list-table">
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th></th>
                    </tr>
                    @foreach ($methodologies as $meth)
                        <tr id="methodology-{{ $meth->id }}">
                            <td class="has-text-centered methodology-order">{{ $meth->list_order }}</td>
                            <td class="methodology-title">{{ $meth->title }}</td>
                            <td class="methodology-category">{{ $methTypeList[$meth->category] }}</td>
                            <td class="handms-actions">
                                <a title="Edit" class="handms-icons" onclick="editMethodology({{ $meth->id }})">{{ icon('mode_edit') }}</a>
                                <a title="Delete" class="handms-icons" onclick="deleteMethodology({{ $meth->id }})">{{ icon('delete') }}</a>
                                <a title="Move Up" class="handms-icons" onclick="moveMethodologyUp({{ $meth->id }})">{{ icon('keyboard_arrow_up') }}</a>
                                <a title="Move Down" class="handms-icons" onclick="moveMethodologyDown({{ $meth->id }})">{{ icon('keyboard_arrow_down') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div id="methodology-text-form-container">
                @include('modules.company.project.methodology.text')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
            <div id="methodology-icon-form-container">
                @include('modules.company.project.methodology.icon')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('ICON');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
            <div id="methodology-complex-table-form-container">
                @include('modules.company.project.methodology.complex_table')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('COMPLEX_TABLE');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
            <div id="methodology-process-form-container">
                @include('modules.company.project.methodology.process')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('PROCESS');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
            <div id="methodology-simple-table-form-container">
                @include('modules.company.project.methodology.simple_table')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('SIMPLE_TABLE');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
            <div id="methodology-text-image-form-container">
                @include('modules.company.project.methodology.text_image')
                <div class="field is-grouped is-grouped-centered">
                    <p class="control">
                        <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT_IMAGE');">Save Method Statement</button>
                    </p>
                    <p class="control">
                        <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                    </p>
                </div>
                <br>
            </div>
        </div>
    </div>
</div>
@push ('scripts')
<script>
    // Methodology Scripts
    var methodologies = JSON.parse('{!! str_replace("'", '&apos;',addslashes($methodologies->toJson())) !!}');
    var company = JSON.parse('{!! str_replace("'", '&apos;', addslashes($company->toJson())) !!}');
    var methTypeList = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($methTypeList))) !!}');
    var processes = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($processes))) !!}');
    var tableRows = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($tableRows))) !!}');
    var icons = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($icons))) !!}');
    var deletedImages = [];

    function createMethodology() {
        let type = $('#meth_type').val();
        deletedImages = [];
        if (type == '') {
            toastr.error('Please select a Method Statement Type');
        } else {
            let title = '';
            let content = '';
            let container = 'methodology-text-form-container';
            let cat = "TEXT";
            switch (type) {
                case 'TASK_DESC':
                    title = 'Task Description';
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'PLANT_EQUIP':
                    title = 'Plant & Equipment';
                    container = 'methodology-simple-table-form-container';
                    cat = 'SIMPLE_TABLE';
                    $('#simple-table tbody tr').remove();
                    $('#simple-table').attr('data-next_row', 0);
                    CKEDITOR.instances.simple_text_before.setData('');
                    CKEDITOR.instances.simple_text_after.setData('');
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'CERT_REQ':
                    title = 'Certified Skills Required';
                    container = 'methodology-simple-table-form-container';
                    cat = 'SIMPLE_TABLE';
                    $('#simple-table tbody tr').remove();
                    $('#simple-table').attr('data-next_row', 0);
                    CKEDITOR.instances.simple_text_before.setData('');
                    CKEDITOR.instances.simple_text_after.setData('');
                    $('#' + container + ' #simple_page_break').prop('checked', false);
                    $('#' + container + ' input[name="simple_page_break"]').val('');
                    break;
                case 'DISP_WASTE':
                    title = 'Disposing of Waste';
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'NOISE':
                    title = 'Noise';
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'WORK_HIGH':
                    title = 'Working at Height';
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'MAN_HANDLE':
                    title = 'Manual Handling';
                    CKEDITOR.instances.text_content.setData('');
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'ACC_REPORT':
                    title = 'Accident Reporting';
                    CKEDITOR.instances.text_content.setData();
                    $('#' + container + ' #text_page_break').prop('checked', false);
                    $('#' + container + ' input[name="text_page_break"]').val('');
                    break;
                case 'FIRST_AID':
                    container = 'methodology-text-image-form-container';
                    cat = 'TEXT_IMAGE';
                    title = 'First Aid';
                    $('#'+container+' #image').val('');
                    $('.ti_image').html('');
                    CKEDITOR.instances.image_text_before.setData('');
                    CKEDITOR.instances.image_text_after.setData('');
                    $('#' + container + ' #image_page_break').prop('checked', false);
                    $('#' + container + ' input[name="image_page_break"]').val('');
                    break;
                case 'TEXT_IMAGE':
                    container = 'methodology-text-image-form-container';
                    cat = 'TEXT_IMAGE';
                    $('#'+container+' #image').val('');
                    $('.ti_image').html('');
                    CKEDITOR.instances.image_text_before.setData('');
                    CKEDITOR.instances.image_text_after.setData('');
                    $('#' + container + ' #image_page_break').prop('checked', false);
                    $('#' + container + ' input[name="image_page_break"]').val('');
                    break;
                case 'SIMPLE_TABLE':
                    container = 'methodology-simple-table-form-container';
                    cat = 'SIMPLE_TABLE';
                    $('#simple-table tbody tr').remove();
                    $('#simple-table').attr('data-next_row', 0);
                    CKEDITOR.instances.simple_text_before.setData('');
                    CKEDITOR.instances.simple_text_after.setData('');
                    $('#' + container + ' #simple_page_break').prop('checked', false);
                    $('#' + container + ' input[name="simple_page_break"]').val('');
                    break;
                case 'COMPLEX_TABLE':
                    container = 'methodology-complex-table-form-container';
                    cat = 'COMPLEX_TABLE';
                    $('#complex-table tbody tr').remove();
                    $('#complex-table').attr('data-next_row', 0);
                    CKEDITOR.instances.complex_text_before.setData('');
                    CKEDITOR.instances.complex_text_after.setData('');
                    $('#' + container + ' #complex_page_break').prop('checked', false);
                    $('#' + container + ' input[name="complex_page_break"]').val('');
                    break;
                case 'PROCESS':
                    container = 'methodology-process-form-container';
                    cat = 'PROCESS';
                    $('#process-table tbody tr').remove();
                    $('#process-table').attr('data-next_row', 0);
                    $('#is_heading').prop('checked', false);
                    $('#new_label').val('');
                    $('#new_description').val('');
                    $('#' + container + ' #image_id').val('');
                    $('#' + container + ' #page_break').prop('checked', false);
                    $('#' + container + ' input[name="page_break"]').val('');
                    break;
                case 'ICON':
                    container = 'methodology-icon-form-container';
                    title = 'PPE Requirements';
                    $('#top-body td').remove();
                    $('#bottom-body td').remove();
                    $('#icon_main_heading').val('');
                    $('#icon_sub_heading').val('');
                    $('#' + container + ' input[name=type]')[0].checked = false;
                    $('#' + container + ' input[name=type]')[1].checked = false;
                    $('#icon_list').prop('selectedIndex',0);
                    $('#image_preview').attr('src', '/gfx/icons/no_image.png');
                    $('#words').val('');
                    CKEDITOR.instances.icon_text_after.setData('');
                    cat = 'ICON';
                    $('#' + container + ' #icon_page_break').prop('checked', false);
                    $('#' + container + ' input[name="icon_page_break"]').val('');
                    break;
            }
            $('[id^=methodology-][id$=-form-container]').css('display', 'none');
            $('#methodology-list-container').hide();
            $('#' + container + ' #title').val(title);
            $('#' + container + ' input[name="tickbox_answer"][value="1"]').attr('checked', true);
            $('#' + container + ' #text_page_break').prop('checked', false);
            $('#' + container + ' input[name="text_page_break"]').val('');

            if ($('#' + container + ' input[name=image_on]').length) {
                $('#' + container + ' input[name=image_on]')[0].checked = false;
                $('#' + container + ' input[name=image_on]')[1].checked = false;
            }

            $('#' + container).css('display', 'inherit');
            $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+cat+"')");
            $('#main-methodology-container .submitbutton').attr("disabled", false);
        }
    }

    function editMethodology(id) {
        let methodology = methodologies.filter(methodologies => methodologies.id === id)
        if (methodology.length) {
            methodology = methodology[0];
            let title = methodology.title.replace('&apos;', "'");
            let content = '';
            let container = 'methodology-text-form-container';
            deletedImages = [];
            switch (methodology.category) {
                case 'TEXT':
                    CKEDITOR.instances.text_content.setData(methodology.text_before);
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #text_page_break').prop('checked', true);
                        $('#' + container + ' input[name="text_page_break"]').val('1');
                    } else {
                        $('#' + container + ' #text_page_break').prop('checked', false);
                        $('#' + container + ' input[name="text_page_break"]').val('');
                    }
                    break;
                case 'TEXT_IMAGE':
                    container = 'methodology-text-image-form-container';
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #image_page_break').prop('checked', true);
                        $('#' + container + ' input[name="image_page_break"]').val('1');
                    } else {
                        $('#' + container + ' #image_page_break').prop('checked', false);
                        $('#' + container + ' input[name="image_page_break"]').val('');
                    }
                    var image_on = methodology.image_on;
                    CKEDITOR.instances.image_text_before.setData(methodology.text_before);
                    CKEDITOR.instances.image_text_after.setData(methodology.text_after);
                    $('#image').val('');
                    $('.ti_image').html('<img src="/image/'+methodology.image+'">');
                    break;
                case 'SIMPLE_TABLE':
                    container = 'methodology-simple-table-form-container';
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #simple_page_break').prop('checked', true);
                        $('#' + container + ' input[name="simple_page_break"]').val('1');
                    } else {
                        $('#' + container + ' #simple_page_break').prop('checked', false);
                        $('#' + container + ' input[name="simple_page_break"]').val('');
                    }
                    if (tableRows[methodology.id] !== 'undefined') {
                        let rows = tableRows[methodology.id];
                        $('#simple-table tbody').html('');
                        if (rows !== undefined) {
                            $.each(rows, function(key, row) {
                                let newRow = "<tr class='columns' data-row='"+key+"'>";

                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_1'>";
                                    if (row.col_1 != null) {
                                        newRow += row.col_1+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_2'>";
                                    if (row.col_2 != null) {
                                        newRow += row.col_2+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_3'>";
                                    if (row.col_3 != null) {
                                        newRow += row.col_3+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_4'>";
                                    if (row.col_4 != null) {
                                        newRow += row.col_4+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_5'>";
                                    if (row.col_5 != null) {
                                        newRow += row.col_5+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }

                                    newRow += "<td class='column is-1'><a class='handms-icons delete_icon' onclick='deleteSimpleRow("+parseInt(key)+")'><svg class='eg-delete'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/eg-icons.svg#eg-delete'></use></svg></a>\
                                    </td>";
                                    newRow += "</tr>";
                                $('#simple-table tbody').append(newRow);
                            });
                            $('#simple-table').attr('data-next_row', Object.keys(rows).length);
                        }
                    }
                    CKEDITOR.instances.simple_text_before.setData(methodology.text_before);
                    CKEDITOR.instances.simple_text_after.setData(methodology.text_after);
                    break;
                case 'COMPLEX_TABLE':
                    container = 'methodology-complex-table-form-container';
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #complex_page_break').prop('checked', true);
                        $('#' + container + ' input[name="complex_page_break"]').val('1');
                    } else {
                        $('#' + container + ' #complex_page_break').prop('checked', false);
                        $('#' + container + ' input[name="complex_page_break"]').val('');
                    }
                    if (tableRows[methodology.id] !== 'undefined') {
                        let rows = tableRows[methodology.id];
                        $('#complex-table tbody').html('');
                        if (rows !== undefined) {
                            $.each(rows, function(key, row) {
                                let newRow = "<tr class='columns' data-row='"+key+"'>";

                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_1'>";
                                    if (row.col_1 != null) {
                                        newRow += row.col_1+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_2'>";
                                    if (row.col_2 != null) {
                                        newRow += row.col_2+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_3'>";
                                    if (row.col_3 != null) {
                                        newRow += row.col_3+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_4'>";
                                    if (row.col_4 != null) {
                                        newRow += row.col_4+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }
                                    newRow += "<th class='column'><textarea rows='3' name='row_"+key+"__col_5'>";
                                    if (row.col_5 != null) {
                                        newRow += row.col_5+"</textarea></td>";
                                    } else {
                                        newRow += "</textarea></td>";
                                    }

                                newRow += "<td class='column is-1'><a class='handms-icons delete_icon' onclick='deleteComplexRow("+parseInt(key)+")'><svg class='eg-delete'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/eg-icons.svg#eg-delete'></use></svg></a></td>";
                                newRow += "</tr>";
                                $('#complex-table tbody').append(newRow);
                                $('#complex-table').attr('data-next_row', Object.keys(rows).length);
                            });
                        }
                    }
                    CKEDITOR.instances.complex_text_before.setData(methodology.text_before);
                    CKEDITOR.instances.complex_text_after.setData(methodology.text_after);
                    break;
                case 'PROCESS':
                    container = 'methodology-process-form-container';
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #page_break').prop('checked', true);
                        $('#' + container + ' input[name="page_break"]').val('1');
                    } else {
                        $('#' + container + ' #page_break').prop('checked', false);
                        $('#' + container + ' input[name="page_break"]').val('');
                    }
                    if (processes[methodology.id] !== 'undefined') {
                        let rows = processes[methodology.id];
                        $('#process-table tbody tr').remove();
                        if (rows !== 'undefined') {
                            $.each(rows, function(key, row) {
                                let checked = '';
                                if (row.heading == 1) {
                                    checked = 'checked';
                                }
                                let label = '';
                                if (row.label) {
                                    label = row.label;
                                }
                                let description = '';
                                if (row.description) {
                                    description = row.description;
                                }

                                let newRow = "<tr class='columns' data-row='"+key+"' style='margin:0'>";
                                    newRow += "<td class='column is-2'><input type='checkbox' name='row_"+key+"__heading' "+checked+"></input></td>";
                                    newRow += "<td class='column is-1'><input  type='text' name='row_"+key+"__label' value='"+label+"'></input></td>";
                                    newRow += "<td class='column is-3'><textarea name='row_"+key+"__description'>"+description+"</textarea></td>";
                                    if (row.image) {
                                        newRow += "<td class='column is-3 image-cell'><img class='process-image' src='/image/"+row.image+"' data-image_id='"+row.image+"' data-process_row='row_"+key+"__image'</td>";
                                    } else {
                                        newRow += "<td class='column is-3 image-cell'>No Image</td>";
                                    }
                                    newRow += '<td class="column is-3 handms-actions" style="height:150px">\
                                                <a title="Delete" class="handms-icons delete_process" onclick="deleteProcess('+key+')">{{ icon("delete") }}</a>\
                                                <a title="Move Up" class="handms-icons move_process_up" onclick="moveProcessUp('+key+')">{{ icon("keyboard_arrow_up") }}</a>\
                                                <a title="Move Down" class="handms-icons move_process_down" onclick="moveProcessDown('+key+')">{{ icon("keyboard_arrow_down") }}</a>\
                                                <div class="field image_picker">\
                                                    <input type="hidden" name="file" value="">\
                                                    <input type="hidden" name="file" id="file" value=""><div class="control">\
                                                    <input type="file" name="image_id" class="form-control  input " id="edit_image_'+key+'" value="">\
                                                </div>\
                                                <button class="button is-primary is-small image-button edit-image" onclick="editProcessImage('+key+')">Update Image</button>';

                                    if (row.image) {
                                        newRow +='<button class="button is-primary is-small image-button delete-image" onclick="deleteProcessImage('+key+')">Remove Image</button>';
                                    }
                                    newRow += '</td>';
                                newRow += "</tr>"
                                $('#process-table tbody').append(newRow);
                                $('#process-table').attr('data-next_row', Object.keys(rows).length);
                            });
                        }
                        $('#is_heading').prop('checked', false);
                        $('#new_label').val('');
                        $('#' + container + ' #image_id').val('');
                        $('#new_description').val('');
                    }
                    break;
                case 'ICON':
                    container = 'methodology-icon-form-container';
                    if (methodology.page_break == 1) {
                        $('#' + container + ' #icon_page_break').prop('checked', true);
                        $('#' + container + ' input[name="icon_page_break"]').val('1');
                    } else {
                        $('#' + container + ' #icon_page_break').prop('checked', false);
                        $('#' + container + ' input[name="icon_page_break"]').val('');
                    }
                    if (icons[methodology.id] !== 'undefined') {
                        let tables = icons[methodology.id];
                        $('#top-body td').remove();
                        $('#bottom-body td').remove();
                        if (tables !== 'undefined') {
                            $.each(tables, function(table, tds) {
                                if (table == "MAIN") {
                                    var tr = $('#top-body tr');
                                    var location = 'top';
                                } else {
                                    var tr = $('#bottom-body tr');
                                    var location = 'bottom';
                                }

                                for (let index = 0; index < Object.keys(tds).length; index++) {
                                    tr.append(renderTableData(tr, index, location, tds[index]));

                                    // correct selection of select field after rendering
                                    let selectName = 'icon_list_'+location+"_"+index;
                                    $('[name='+selectName+']').val(tds[index]['image']);
                                }
                            })
                            $('#icon_main_heading').val(methodology.icon_main_heading);
                            $('#icon_sub_heading').val(methodology.icon_sub_heading);
                            $('#' + container + ' input[name=type]')[0].checked = false;
                            $('#' + container + ' input[name=type]')[1].checked = false;
                            $('#icon_list').prop('selectedIndex',0);
                            $('#image_preview').attr('src', '/gfx/icons/no_image.png');
                            $('#words').val('');
                        }
                    }
                    CKEDITOR.instances.icon_text_after.setData(methodology.text_after);
                    break;
            }
            $('#methodology-list-container').hide();
            $('[id^=methodology-][id$=-form-container]').css('display', 'none');

            $('#' + container + ' #title').val(title);

            // text + image
            if ($('#' + container + ' input[name=image_on]')) {
                if (image_on == "BEFOR") {
                    $('input:radio[name=image_on]')[0].checked = true;
                } else if (image_on == "AFTER") {
                    $('input:radio[name=image_on]')[1].checked = true;
                }
            }

            $('#' + container + ' input[name="tickbox_answer"][value="' + methodology.tickbox_answer + '"]').prop('checked', true);

            $('#' + container).css('display', 'inherit');
            $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+methodology.category+"',"+id+","+methodology.list_order+")");
            $('#main-methodology-container .submitbutton').attr("disabled", false);
        }
    }

    function submitMethodologyForm(category, editId=null, listOrder=null) {

        // stop double click submits
        $('#main-methodology-container .submitbutton').attr("disabled", true);

        var form_data = new FormData();

        form_data.append('_token', '{{ csrf_token() }}');
        form_data.append('entityType', '{{ $entityType }}');
        if(listOrder === null) {
            listOrder = methodologies.length +1; // create only.
        }
        form_data.append('list_order', listOrder);
        form_data.append('category', category);

        switch (category) {
            case 'TEXT':
                var tickbox = $('#methodology-text-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('title', $('#methodology-text-form-container #title').val());
                form_data.append('tickbox_answer', tickbox);
                form_data.append('text_before', CKEDITOR.instances.text_content.getData());
                form_data.append('page_break', $('#methodology-text-form-container input[type="hidden"][name="text_page_break"]').val());
                break;
            case 'TEXT_IMAGE':
                var tickbox = $('#methodology-text-image-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('tickbox_answer', tickbox);
                form_data.append('title', $('#methodology-text-image-form-container #title').val());
                if ($('#methodology-text-image-form-container #image').prop('files')[0] !== undefined){
                    form_data.append('image', $('#methodology-text-image-form-container #image').prop('files')[0]);
                }

                let imageCheck = $('.ti_image img');
                if (imageCheck.length > 0) {
                    let image = $(imageCheck[0]).attr('src');
                    form_data.append('image_check', image);
                }

                let checked = $('input[name=image_on]:checked').val();
                if (checked && checked !== "undefined") {
                    form_data.append('image_on', checked);
                }
                form_data.append('text_before', CKEDITOR.instances.image_text_before.getData());
                form_data.append('text_after', CKEDITOR.instances.image_text_after.getData());
                form_data.append('page_break', $('#methodology-text-image-form-container input[type="hidden"][name="image_page_break"]').val());
                break;
            case 'SIMPLE_TABLE':
                var tickbox = $('#methodology-simple-table-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('title', $('#methodology-simple-table-form-container #title').val());
                form_data.append('tickbox_answer', tickbox);

                // get all inputs within the $('#simple-table') element and attach them?
                let simple_inputs = $('#simple-table textarea[name^=row_]');
                $.each(simple_inputs, function(key, input) {
                    form_data.append(input.name, input.value);
                })

                form_data.append('text_before', CKEDITOR.instances.simple_text_before.getData());
                form_data.append('text_after', CKEDITOR.instances.simple_text_after.getData());
                form_data.append('page_break', $('#methodology-simple-table-form-container input[type="hidden"][name="simple_page_break"]').val());
                break;
            case 'COMPLEX_TABLE':
                var tickbox = $('#methodology-complex-table-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('title', $('#methodology-complex-table-form-container #title').val());
                form_data.append('tickbox_answer', tickbox);

                let complex_inputs = $('#complex-table textarea[name^=row_]');
                $.each(complex_inputs, function(key, input) {
                    form_data.append(input.name, input.value);
                })

                form_data.append('text_before', CKEDITOR.instances.complex_text_before.getData());
                form_data.append('text_after', CKEDITOR.instances.complex_text_after.getData());
                form_data.append('page_break', $('#methodology-complex-table-form-container input[type="hidden"][name="complex_page_break"]').val());
                break;
            case 'PROCESS':
                var tickbox = $('#methodology-process-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('title', $('#methodology-process-form-container #title').val());
                form_data.append('tickbox_answer', tickbox);
                form_data.append('page_break', $('#methodology-process-form-container input[type="hidden"][name="page_break"]').val());
                form_data.append('replacedImages', deletedImages);

                let process_lines = $('#process-table input[name^=row_], #process-table textarea[name^=row_]');
                $.each(process_lines, function(key, input) {
                    if (input.type == "text" || input.type == "textarea") {
                        form_data.append(input.name, input.value);
                    } else {
                        form_data.append(input.name, input.checked)
                    }
                });

                let images = $('#process-table img');
                $.each(images, function(key, image) {
                    var name = $(images[key]).attr('data-process_row');
                    var value = $(images[key]).attr('data-image_id');
                    form_data.append(name, value);
                })
                break;
            case 'ICON':
                var tickbox = $('#methodology-icon-form-container [name="tickbox_answer"]:checked').val();
                if (tickbox == undefined) {
                    tickbox = '';
                }
                form_data.append('title', $('#methodology-icon-form-container #title').val());
                form_data.append('tickbox_answer', tickbox);
                form_data.append('text_after', CKEDITOR.instances.icon_text_after.getData());

                form_data.append('icon_main_heading', $('#methodology-icon-form-container #icon_main_heading').val());
                form_data.append('icon_sub_heading', $('#methodology-icon-form-container #icon_sub_heading').val());

                $.each($('#methodology-icon-form-container table td input'), function(key, input) {
                    form_data.append(input.name, input.value)
                });

                $.each($('#methodology-icon-form-container table td select'), function(key, select) {
                    form_data.append(select.name, select.value);
                });
                form_data.append('page_break', $('#methodology-icon-form-container input[type="hidden"][name="icon_page_break"]').val());
                break;
        }

        let url = 'methodology/create';
        if (editId) {
            url = 'methodology/'+editId+'/edit';
        }

        if ($('#related_methodologies_div .control select').length > 0) {
            var selectize = $('#related_methodologies_div .control select')[0].selectize;
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if (category != "TEXT_IMAGE") {
                    var id = data;
                    var image = form_data.get('image');
                } else {
                    data = JSON.parse(data);
                    var id = data.id;
                    var image = data.image;
                }

                if (!editId) {// create
                    methodologies.push({
                        id: parseInt(id),
                        title: form_data.get('title'),
                        text_before: form_data.get('text_before'),
                        list_order: form_data.get('list_order'),
                        category: category,
                        entity: '{{$entityType}}',
                        image: image,
                        image_on: form_data.get('image_on'),
                        text_after: form_data.get('text_after'),
                        icon_main_heading: form_data.get('icon_main_heading'),
                        icon_sub_heading: form_data.get('icon_sub_heading'),
                        tickbox_answer: form_data.get('tickbox_answer'),
                        page_break: form_data.get('page_break')
                    });
                    $('.methodology-list-table').append('<tr id="methodology-' + id + '">\
                            <td class="has-text-centered methodology-order">' + form_data.get('list_order')+ '</td>\
                            <td class="methodology-title">' + form_data.get('title') + '</td>\
                            <td class="methodology-category">' +  methTypeList[category] + '</td>\
                            <td class="handms-actions">\
                                <a title="Edit" class="handms-icons" onclick="editMethodology('+ id +')">{{ icon('mode_edit') }}</a>\
                                <a title="Delete" class="handms-icons" onclick="deleteMethodology('+id+')">{{ icon('delete') }}</a>\
                                <a title="Move Up" class="handms-icons" onclick="moveMethodologyUp('+id+')">{{ icon('keyboard_arrow_up') }}</a>\
                                <a title="Move Down" class="handms-icons" onclick="moveMethodologyDown('+id+')">{{ icon('keyboard_arrow_down') }}</a>\
                            </td>\
                        </tr>');

                    // add option to the methodology list within hazard form
                    if (typeof selectize !== "undefined") {
                        selectize.addOption({value: id, text:form_data.get('title')});
                    }

                } else { // edit
                    for (let i = 0; i < methodologies.length; i++) {
                        if (methodologies[i]['id'] === editId) {
                            methodologies[i]['title'] = form_data.get('title');
                            var textBefore = form_data.get('text_before');
                            if (textBefore != null) {
                                textBefore = textBefore.replace("'", '&apos;');
                            }
                            methodologies[i]['text_before'] = textBefore;
                            methodologies[i]['list_order'] = form_data.get('list_order');
                            methodologies[i]['category'] = category;
                            methodologies[i]['entity'] = form_data.get('entity');
                            methodologies[i]['image'] = image;
                            methodologies[i]['image_on'] = form_data.get('image_on');
                            var textAfter = form_data.get('text_after');
                            if (textAfter != null) {
                                textAfter = textAfter.replace("'", '&apos;');
                            }
                            methodologies[i]['text_after'] = textAfter;
                            var iconMain = form_data.get('icon_main_heading');
                            if (iconMain != null) {
                                iconMain = iconMain.replace("'", '&apos;');
                            }
                            methodologies[i]['icon_main_heading'] = iconMain;
                            var iconSub = form_data.get('icon_sub_heading');
                            if (iconSub != null) {
                                iconSub = iconSub.replace("'", '&apos;');
                            }
                            methodologies[i]['icon_sub_heading'] = iconSub;
                            methodologies[i]['tickbox_answer'] = form_data.get('tickbox_answer');
                            methodologies[i]['page_break'] = form_data.get('page_break');

                            // need to edit methodology table
                            $('tr#methodology-' + editId + ' .methodology-order').html(form_data.get('list_order'));
                            var methTitle = form_data.get('title');
                            if (methTitle != null) {
                                methTitle = methTitle.replace("'", '&apos;');
                            }
                            $('tr#methodology-' + editId + ' .methodology-title').html(methTitle);
                            $('tr#methodology-' + editId + ' .methodology-category').html(methTypeList[category]);
                            break;
                        }
                    }

                    // edit methodology name within methodolgy list with hazard form.
                    let data = {value: id, text:form_data.get('title')};
                    if (typeof selectize !== "undefined") {
                        selectize.updateOption(id, data);
                    }
                }

                // write them to the local array to display when you hit Edit.
                switch (category) {
                    case 'SIMPLE_TABLE':
                        delete tableRows[id];
                        let simple_rows = $('#simple-table tr');
                        tableRows[id] = [];
                        $.each(simple_rows, function(key, row) {
                            let inputs = $(row).find("textarea[name^=row_]");
                            let finalInputs = [];
                            for(let i = 0; i <= 4; i++) {
                                var tempCol = inputs[i].value;
                                if (tempCol != null) {
                                    tempCol = tempCol.replace("'", '&apos;');
                                }
                                finalInputs[i] = tempCol;
                            }
                            tableRows[id][key] = {
                                col_1: finalInputs[0],
                                col_2: finalInputs[1],
                                col_3: finalInputs[2],
                                col_4: finalInputs[3],
                                col_5: finalInputs[4]
                            };
                            $(row).remove();
                        });
                        break;
                    case "COMPLEX_TABLE":
                        delete tableRows[id];
                        let complex_rows = $('#complex-table tr');
                        tableRows[id] = [];
                        $.each(complex_rows, function(key, row) {
                            let inputs = $(row).find("textarea[name^=row_]");
                            let finalInputs = [];
                            for(let i = 0; i <= 4; i++) {
                                var tempCol = inputs[i].value;
                                if (tempCol != null) {
                                    tempCol = tempCol.replace("'", '&apos;');
                                }
                                finalInputs[i] = tempCol;
                            }
                            tableRows[id][key] = {
                                col_1: finalInputs[0],
                                col_2: finalInputs[1],
                                col_3: finalInputs[2],
                                col_4: finalInputs[3],
                                col_5: finalInputs[4]
                            };
                            $(row).remove();
                        });
                        break;
                    case "PROCESS":
                        delete processes[id];
                        let processRows = $('#process-table tbody tr');
                        processes[id] = [];
                        $.each(processRows, function(key, row) {
                            let inputs = $(row).find("input[name^=row_], textarea[name^=row]");
                            let checked = 0;

                            if (inputs[0].checked) {
                                checked = 1;
                            }

                            let image_id = null;
                            let image = $(row).find("img");
                            if (image !== 'undefined') {
                                image_id = $(image[0]).attr('data-image_id');
                            }

                            var label = inputs[1].value;
                            if (label != null) {
                                label = label.replace("'", '&apos;');
                            }
                            var description = inputs[2].value;
                            if (description != null) {
                                description = description.replace("'", '&apos;');
                            }

                            processes[id][key] = {
                                heading: checked,
                                label: label,
                                description: description,
                                image: image_id
                            }
                            $(row).remove();
                        });
                        break;
                    case "ICON":
                        delete icons[id];

                        let top = [];
                        let topData = $('#top-body td');
                        $.each(topData, function(key, cell) {
                            var td = $(cell);
                            var description = td.find('.wording').val();
                            if (description != null) {
                                description = description.replace("'", '&apos;');
                            }
                            top[key] = {
                                id: key,
                                text: description,
                                image: td.find('.td_icon_list').val(),
                                list_order: key
                            }
                        })

                        let bottom = [];
                        let bottomData = $('#bottom-body td');
                        
                        $.each(bottomData, function(key, cell) {
                            var td = $(cell);
                            var description = td.find('.wording').val();
                            if (description != null) {
                                description = description.replace("'", '&apos;');
                            }
                            bottom[key] = {
                                id: key,
                                text: description,
                                image: td.find('.td_icon_list').val(),
                                list_order: key
                            }
                        })

                        icons[id] = { MAIN: top, SUB: bottom };
                        break;
                    default: // text, all others not listed above
                        break;
                }

                $('#methodology-list-container').show();
                $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                $('#meth_type').val('');
            },
            error: function (data) {
                $('#main-methodology-container .submitbutton').attr("disabled", false);
                if (data.status == 422) {
                    var errorList = JSON.parse(data.responseText);
                    $.each(errorList.errors, function(key,val) {
                        toastr.error(val);
                    });
                } else if (data.status == 401) {
                    toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                } else {
                    toastr.error('An error has occured when saving the Method Statement');
                }
            }
        });
    }

    function deleteMethodology(id) {
        if (confirm("Are you sure you want delete this Method Statement?")) {
            var data = {
                _token: '{{ csrf_token() }}',
                methodology_id: id,
            };
            $.ajax({
                url: '/methodology/'+id+'/delete_methodology',
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response != "disallow") {
                        let listOrder = 0;
                        for (let i = 0; i < methodologies.length; i++) {
                            if (methodologies[i]['id'] == id) {
                                // remove
                                $('tr#methodology-' + id).remove();
                                listOrder = methodologies[i]['list_order'];
                                delete methodologies[i];
                            } else if (listOrder != 0 && methodologies[i]['list_order'] > listOrder) {
                                // decrement order
                                let newOrder = methodologies[i]['list_order'] - 1;
                                $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(newOrder);
                                methodologies[i]['list_order'] = newOrder;
                            }
                        }
                        methodologies = methodologies.filter(function (item) {
                            return item !== undefined;
                        });

                        // unset it in the hazards form's methodology field
                        if ($('#related_methodologies_div .control select').length > 0) {
                            var selectize = $('#related_methodologies_div .control select')[0].selectize;
                            selectize.removeOption(id);
                        }

                        // loop through each of the hazard methodology's data array and wipe out any instances of the deleted key.
                        $.each(window.hazardMethodologies, function (key, methodologyArray) {
                            window.hazardMethodologies[key] = methodologyArray.filter(function(methodologyKey) {
                                return methodologyKey != id;
                            })
                        })

                        toastr.success('Method Statement was deleted');
                    } else {
                        toastr.error('An error has occured when deleting the Method Statement');
                    }
                },
                error: function (data) {
                    if (data.status == 422) {
                        var errors = '';
                        $.each(data.responseJSON.errors, function(key,val) {
                            toastr.error(val);
                        });
                    } else if (data.status == 401) {
                        toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                    } else {
                        toastr.error('An error has occured when deleting the Method Statement');
                    }
                }
            });
        }
    }

    function moveMethodologyUp(id) {
        moveMethodology(id, "move_up")
    }

    function moveMethodologyDown(id) {
        moveMethodology(id, "move_down")
    }

    function moveMethodology(id, slug) {
        var data = {
            _token: '{{ csrf_token() }}',
            methodology_id: id,
        };
        $.ajax({
            url: '/methodology/'+id+'/'+slug,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response != "disallow") {
                    for (let i = 0; i < methodologies.length; i++) {
                        if (slug == 'move_down' && methodologies[i]['id'] == id) {
                            let firstOrder = methodologies[i]['list_order'];
                            let lastOrder = methodologies[i + 1]['list_order'];
                            $('tr#methodology-' + methodologies[i + 1]['id'] + ' .methodology-order').html(firstOrder);
                            $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(lastOrder);
                            methodologies[i + 1]['list_order'] = firstOrder;
                            methodologies[i]['list_order'] = lastOrder;
                            $('tr#methodology-' + methodologies[i + 1]['id']).after($('tr#methodology-' + methodologies[i]['id']));
                        } else if (slug == 'move_up' && methodologies[i]['id'] == id) {
                            let firstOrder = methodologies[i]['list_order'];
                            let lastOrder = methodologies[i - 1]['list_order'];
                            $('tr#methodology-' + methodologies[i - 1]['id'] + ' .methodology-order').html(firstOrder);
                            $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(lastOrder);
                            methodologies[i - 1]['list_order'] = firstOrder;
                            methodologies[i]['list_order'] = lastOrder;
                            $('tr#methodology-' + methodologies[i]['id']).after($('tr#methodology-' + methodologies[i - 1]['id']));
                        }
                    }
                    methodologies = bubbleSort(methodologies, 'list_order');
                    toastr.success('Method Statement was moved');
                } else {
                    toastr.error('An error has occured when moving the Method Statement');
                }
            },
            error: function (data) {
                if (data.status == 422) {
                    var errors = '';
                    $.each(data.responseJSON.errors, function(key,val) {
                        toastr.error(val);
                    });
                } else if (data.status == 401) {
                    toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                } else {
                    toastr.error('An error has occured when moving the Method Statement');
                }
            }
        });
    }
</script>
@endpush
