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
                            <th>Heading</th>
                            <th>No</th>
                            <th>Description</th>
                            <th>Thumbnail</th>
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
            let row = "<tr data-row='"+row_id+"'>";
                row += "<td><input type='checkbox' name='row_"+row_id+"__heading' "+checked+"></input></td>";
                row += "<td><input type='text' name='row_"+row_id+"__label' value='"+$('#new_label').val()+"'></input></td>";
                row += "<td><input type='text' name='row_"+row_id+"__description' value='"+$('#new_description').val()+"'></input></td>";
                row += "<td>[Image details]</input></td>";
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
</script>
