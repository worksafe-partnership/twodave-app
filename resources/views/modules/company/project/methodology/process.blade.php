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

    function moveProcessUp(index) {
        alert('move up');
    }

    function moveProcessDown(key) {
        alert('move down');
    }

    function moveProcess(key, direction) {
        alert('move '+direction);
    }


</script>


<style>
    #process-table input{
        width: 100%;
    }

    #process-table * {
        box-sizing: border-box;
    }
</style>
