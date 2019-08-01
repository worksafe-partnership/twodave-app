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
        <p class="sub-heading">Table: (needs formatting)</p>
        <div class="columns">
            <div class="column">
                <div class="field">
                    <table class="table is-striped is-bordered" id="process-table" data-next_row="1">
                        <thead>
                            <tr class="columns" style='margin:0'>
                                <th class="column is-2">Heading</th>
                                <th class="column is-1">No</th>
                                <th class="column is-3">Description</th>
                                <th class="column is-3">Thumbnail</th>
                                <th class="column is-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Add Row</p>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::checkbox('is_heading', [
                        'label' => 'This Row is a Heading',
                        'value' => false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('new_label', [
                        'label' => 'Label/No',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::file('image_id', [
                        'label' => 'Image',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">
                <div class="field">
                    {{ EGForm::ckeditor('new_description', [
                        'label' => 'Description',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <button type="button" class="button is-primary" id="add-row-process">Add Row</button>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>

@push('scripts')
<script>
    $('#add-row-process').on('click', function() {
        let checked = '';
        if ($('#is_heading').is(':checked')) {
            checked = 'checked';
        }
        let table = $('#process-table');
        let row_id = table.data('next_row');
        if ($('#new_description').val() != "") {
            let row = "<tr class='columns' data-row='"+row_id+"' style='margin:0'>";
                row += "<td class='column is-2'><input type='checkbox' name='row_"+row_id+"__heading' "+checked+"></input></td>";
                row += "<td class='column is-1'><input type='text' name='row_"+row_id+"__label' value='"+$('#new_label').val()+"'></input></td>";
                row += "<td class='column is-3'><input type='text' name='row_"+row_id+"__description' value='"+$('#new_description').val()+"'></input></td>";
                row += "<td class='column is-3'>[Image]</td>";
                row += '<td class="column is-3 handms-actions" style="height:38px">\
                        <a class="handms-icons delete_process" onclick="deleteProcess('+row_id+')">{{ icon("delete") }}</a>\
                        <a class="handms-icons move_process_up" onclick="moveProcessUp('+row_id+')">{{ icon("keyboard_arrow_up") }}</a>\
                        <a class="handms-icons move_process_down" onclick="moveProcessDown('+row_id+')">{{ icon("keyboard_arrow_down") }}</a>\
                       </td>';
            row += "</tr>"
            table.append(row);
            row_id++;
            table.data('next_row', row_id);
            $('#new_label').val('');
            $('#new_description').val('');
            $('#is_heading').prop('checked', false);
        } else {
            toastr.error('Please ensure your row is populated');
        }
    })

    function deleteProcess(key) {
        if (confirm("Are you sure you want to delete this process?")) {
            let deleted = $('#process-table tbody tr[data-row='+key+']').remove();
            let remaining = $('#process-table tbody tr');
            $.each(remaining, function(index, tr) {
                if (index >= key) {
                    let cell = $(tr);
                    cell.attr('data-row', index);
                    cell.find('[name*="heading"]').attr('name', 'row_'+index+'__heading');
                    cell.find('[name*="label"]').attr('name', 'row_'+index+'__label');
                    cell.find('[name*="description"]').attr('name', 'row_'+index+'__description');
                    cell.find('.delete_process').attr("onclick", "deleteProcess("+index+")");
                    cell.find('.move_process_up').attr("onclick", "moveProcessUp("+index+")");
                    cell.find('.move_process_down').attr('onclick', "moveProcessDown("+index+")");
                }
            });
        }
    }

    function moveProcessUp(key) {
        moveProcess(key, 'up');
    }

    function moveProcessDown(key) {
        moveProcess(key, 'down');
    }

    function moveProcess(key, direction) {
        let trs = $('#process-table tr');
        if (trs.length == 1) {
            toastr.warning('Cannot move if only process icon!');
        } else {
            if (direction == "up") {
                if (key != 0) {
                    let upperKey = key-1;
                    let upperTr = $('#process-table tr[data-row='+upperKey+']')
                    let tr = $('#process-table tr[data-row='+key+']');
                    tr.after(upperTr);

                    upperTr.attr('data-row', key);
                    upperTr.find('[name*="heading"]').attr('name', 'row_'+key+'__heading');
                    upperTr.find('[name*="label"]').attr('name', 'row_'+key+'__label');
                    upperTr.find('[name*="description"]').attr('name', 'row_'+key+'__description');
                    upperTr.find('.delete_process').attr("onclick", "deleteProcess("+key+")");
                    upperTr.find('.move_process_up').attr("onclick", "moveProcessUp("+key+")");
                    upperTr.find('.move_process_down').attr('onclick', "moveProcessDown("+key+")");

                    tr.attr('data-row', upperKey);
                    tr.find('[name*="heading"]').attr('name', 'row_'+upperKey+'__heading');
                    tr.find('[name*="label"]').attr('name', 'row_'+upperKey+'__label');
                    tr.find('[name*="description"]').attr('name', 'row_'+upperKey+'__description');
                    tr.find('.delete_process').attr("onclick", "deleteProcess("+upperKey+")");
                    tr.find('.move_process_up').attr("onclick", "moveProcessUp("+upperKey+")");
                    tr.find('.move_process_down').attr('onclick', "moveProcessDown("+upperKey+")");

                } else {
                    toastr.warning('Cannot move first icon left!');
                }
            } else { // down
                if (key < trs.length-1) {
                    let lowerKey = parseInt(key)+1;
                    let lowerTr = $('#process-table tr[data-row='+lowerKey+']')
                    let tr = $('#process-table tr[data-row='+key+']');
                    lowerTr.after(tr);

                    lowerTr.attr('data-row', key);
                    lowerTr.find('[name*="heading"]').attr('name', 'row_'+key+'__heading');
                    lowerTr.find('[name*="label"]').attr('name', 'row_'+key+'__label');
                    lowerTr.find('[name*="description"]').attr('name', 'row_'+key+'__description');
                    lowerTr.find('.delete_process').attr("onclick", "deleteProcess("+key+")");
                    lowerTr.find('.move_process_up').attr("onclick", "moveProcessUp("+key+")");
                    lowerTr.find('.move_process_down').attr('onclick', "moveProcessDown("+key+")");

                    tr.attr('data-row', lowerKey);
                    tr.find('[name*="heading"]').attr('name', 'row_'+lowerKey+'__heading');
                    tr.find('[name*="label"]').attr('name', 'row_'+lowerKey+'__label');
                    tr.find('[name*="description"]').attr('name', 'row_'+lowerKey+'__description');
                    tr.find('.delete_process').attr("onclick", "deleteProcess("+lowerKey+")");
                    tr.find('.move_process_up').attr("onclick", "moveProcessUp("+lowerKey+")");
                    tr.find('.move_process_down').attr('onclick', "moveProcessDown("+lowerKey+")");
                } else {
                    toastr.warning('Cannot move last icon right!');
                }
            }
        }
    }
</script>
@endpush

@push('styles')
<style>
    #process-table input{
        width: 100%;
    }

    #process-table * {
        box-sizing: border-box;
    }
</style>
@endpush
