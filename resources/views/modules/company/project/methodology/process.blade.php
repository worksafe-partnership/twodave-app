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
        <p class="sub-heading">Table:</p>
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
        let row_id = table.attr('data-next_row');
        if ($('#new_description').val() != "") {
            var image_id = '';

            if ($('#methodology-process-form-container #image_id').prop('files')[0] !== undefined){
                var form_data = new FormData();
                form_data.append('_token', '{{ csrf_token() }}');
                form_data.append('image', $('#methodology-process-form-container #image_id').prop('files')[0]);

                $.ajax({
                    url: '/methodology/add_image',
                    type: 'POST',
                    data: form_data,
                    dataType    : 'text',
                    cache       : false,
                    contentType : false,
                    processData : false,
                    success: function (id) {
                        image_id = id;
                        sortOutNewProcessRow(table, checked, row_id, image_id); // functionised otherwise AJAX request comes back after this code starts executing.
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
                            toastr.error('An error has occured when saving the methodology');
                        }
                    }
                });
            } else { // functionised otherwise AJAX request comes back after this code starts executing.
                sortOutNewProcessRow(table, checked, row_id, image_id);
            }
        } else {
            toastr.error('Please ensure your row is populated');
        }
    });

    function sortOutNewProcessRow(table, checked, row_id, image_id) {
        let row = "<tr class='columns' data-row='"+row_id+"' style='margin:0'>";
            row += "<td class='column is-2'><input type='checkbox' name='row_"+row_id+"__heading' "+checked+"></input></td>";
            row += "<td class='column is-1'><input type='text' name='row_"+row_id+"__label' value='"+$('#new_label').val()+"'></input></td>";
            row += "<td class='column is-3'><input type='text' name='row_"+row_id+"__description' value='"+$('#new_description').val()+"'></input></td>";
            if (image_id != '') {
                row += "<td class='column is-3 image-cell'><img class='process-image' src='/image/"+image_id+"' data-image_id='"+image_id+"' data-process_row='row_"+row_id+"__image'></img></td>";
            } else {
                row += "<td class='column is-3 image-cell'>No Image</td>";
            }
            row += '<td class="column is-3 handms-actions" style="height: 150px">\
                    <a class="handms-icons delete_process" onclick="deleteProcess('+row_id+')">{{ icon("delete") }}</a>\
                    <a class="handms-icons move_process_up" onclick="moveProcessUp('+row_id+')">{{ icon("keyboard_arrow_up") }}</a>\
                    <a class="handms-icons move_process_down" onclick="moveProcessDown('+row_id+')">{{ icon("keyboard_arrow_down") }}</a><br>\
                    <div class="field image_picker">\
                        <input type="hidden" name="file" value="">\
                        <input type="hidden" name="file" id="file" value=""><div class="control">\
                        <input type="file" name="image_id" class="form-control  input " id="edit_image_'+row_id+'" value="">\
                    </div>\
                    <button class="button is-primary is-small image-button edit-image" onclick="editProcessImage('+row_id+')">Update Image</button>';
            if (image_id != '') {
                row += '<button class="button is-primary is-small image-button delete-image" onclick="deleteProcessImage('+row_id+')">Remove Image</button>';
            }
        row += "</td></tr>";
        table.append(row);
        row_id++;
        table.attr('data-next_row', row_id);
        $('#image_id').val('');
        $('#new_label').val('');
        $('#new_description').val('');
        $('#is_heading').prop('checked', false);
    }

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
                    upperTr.find('input[name="image_id"]').attr('id', "edit_image_"+key);
                    upperTr.find('.edit-image').attr('onclick', "editProcessImage("+key+")");
                    upperTr.find('.delete-image').attr('onclick', "deleteProcessImage("+key+")");
                    upperTr.find('.process-image').attr('data-process_row', 'row_'+key+'__image');

                    tr.attr('data-row', upperKey);
                    tr.find('[name*="heading"]').attr('name', 'row_'+upperKey+'__heading');
                    tr.find('[name*="label"]').attr('name', 'row_'+upperKey+'__label');
                    tr.find('[name*="description"]').attr('name', 'row_'+upperKey+'__description');
                    tr.find('.delete_process').attr("onclick", "deleteProcess("+upperKey+")");
                    tr.find('.move_process_up').attr("onclick", "moveProcessUp("+upperKey+")");
                    tr.find('.move_process_down').attr('onclick', "moveProcessDown("+upperKey+")");
                    tr.find('input[name="image_id"]').attr('id', "edit_image_"+upperKey);
                    tr.find('.edit-image').attr('onclick', "editProcessImage("+upperKey+")");
                    tr.find('.delete-image').attr('onclick', "deleteProcessImage("+upperKey+")");
                    tr.find('.process-image').attr('data-process_row', 'row_'+upperKey+'__image');

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
                    lowerTr.find('input[name="image_id"]').attr('id', "edit_image_"+key);
                    lowerTr.find('.edit-image').attr('onclick', "editProcessImage("+key+")");
                    lowerTr.find('.delete-image').attr('onclick', "deleteProcessImage("+key+")");
                    lowerTr.find('.process-image').attr('data-process_row', 'row_'+key+'__image');

                    tr.attr('data-row', lowerKey);
                    tr.find('[name*="heading"]').attr('name', 'row_'+lowerKey+'__heading');
                    tr.find('[name*="label"]').attr('name', 'row_'+lowerKey+'__label');
                    tr.find('[name*="description"]').attr('name', 'row_'+lowerKey+'__description');
                    tr.find('.delete_process').attr("onclick", "deleteProcess("+lowerKey+")");
                    tr.find('.move_process_up').attr("onclick", "moveProcessUp("+lowerKey+")");
                    tr.find('.move_process_down').attr('onclick', "moveProcessDown("+lowerKey+")");
                    tr.find('input[name="image_id"]').attr('id', "edit_image_"+lowerKey);
                    tr.find('.edit-image').attr('onclick', "editProcessImage("+lowerKey+")");
                    tr.find('.delete-image').attr('onclick', "deleteProcessImage("+lowerKey+")");
                    tr.find('.process-image').attr('data-process_row', 'row_'+lowerKey+'__image');
                } else {
                    toastr.warning('Cannot move last icon right!');
                }
            }
        }
    }

    function editProcessImage(row) {
        var form_data = new FormData();
        form_data.append('_token', '{{ csrf_token() }}');
        form_data.append('image', $('#methodology-process-form-container #edit_image_'+row).prop('files')[0]);

        $.ajax({
            url: '/methodology/edit_image',
            type: 'POST',
            data: form_data,
            dataType    : 'text',
            cache       : false,
            contentType : false,
            processData : false,
            success: function (fileId) {
                // id is the new one.
                // find out if an image already exists with this row id
                let image = $('img[data-process_row="row_'+row+'__image"]');

                // if so update the source
                if (image.length > 0) {
                    image.attr("src","/image/"+fileId);
                    image.attr("data-image_id", fileId);
                } else {
                    // if not, add it in the relevant row
                    var cell = $('#methodology-process-form-container .columns [data-row="'+row+'"] .image-cell');
                    cell.html('<img src="/image/'+fileId+'" data-image_id="'+fileId+'" data-process_row="row_'+row+'__image">');
                    var imagePickerCell = $('#process-table tr[data-row='+row+'] .image_picker');
                    var deleteExists = imagePickerCell.find('.delete_image');
                    if (!deleteExists) {
                        imagePickerCell.append('<button class="button is-primary is-small image-button delete-image" onclick="deleteProcessImage('+row+')">Remove Image</button>');
                    }
                }
                $('#edit_image_'+row).val('');
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
                    toastr.error('An error has occured when saving the methodology');
                }
            }
        });
    }

    function deleteProcessImage(row) {
        let image = $('img[data-process_row="row_'+row+'__image"]');

        if (image) {
            let fileId = image.attr('data-image_id');
            let column = image.parent();

            var form_data = new FormData();
            form_data.append('_token', '{{ csrf_token() }}');
            form_data.append('file', fileId);

            $.ajax({
                url: '/methodology/delete_image',
                type: 'POST',
                data: form_data,
                dataType    : 'text',
                cache       : false,
                contentType : false,
                processData : false,
                success: function (id) {
                    column.html("No Image");
                    toastr.success('Image deleted - remember to save your changes');
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
                        toastr.error('An error has occured when saving the methodology');
                    }
                }
            });
        } else {
            toastr.error('No image to delete!');
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

    .image-button {
        margin-top: 5px;
    }

    .image_picker {
        margin-top: 5px;
    }
</style>
@endpush
