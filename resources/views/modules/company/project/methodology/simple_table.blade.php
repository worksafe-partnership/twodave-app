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
            {{ EGForm::ckeditor('text_before', [
                'label' => 'Text Before',
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
                    <table class="table is-striped is-bordered" id="simple-table" data-next_row="0">
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
        <p class="sub-heading">New Row</p>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('simple_col_1', [
                        'label' => 'Cell 1 (Heading)',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('simple_col_2', [
                        'label' => 'Cell 2',
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
                    {{ EGForm::text('simple_col_3', [
                        'label' => 'Cell 3',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('simple_col_4', [
                        'label' => 'Cell 4',
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
                    <button type="button" class="button is-primary" id="add-row-simple">Add Row</button>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>

<hr>
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

<script>
    $('#add-row-simple').on('click', function() {
        let table = $('#simple-table');
        let row_id = table.data('next_row');
        if ($('#simple_col_1').val() != "" && $('#simple_col_2').val() != "") {
            let row = "<tr data-row='"+row_id+"'>";
                row += "<th><input type='text' name='row_"+row_id+"__col_1' value='"+$('#simple_col_1').val()+"'></input></th>";
                row += "<td><input type='text' name='row_"+row_id+"__col_2' value='"+$('#simple_col_2').val()+"'></input></td>";
                row += "<td><input type='text' name='row_"+row_id+"__col_3' value='"+$('#simple_col_3').val()+"'></input></td>";
                row += "<td><input type='text' name='row_"+row_id+"__col_4' value='"+$('#simple_col_4').val()+"'></input></td>";
            row += "</tr>"
            table.append(row);
            row_id++;
            table.data('next_row', row_id);
            $('#simple_col_1').val('');
            $('#simple_col_2').val('');
            $('#simple_col_3').val('');
            $('#simple_col_4').val('');
        } else {
            toastr.error('Please ensure your row is populated');
        }
    })
</script>
