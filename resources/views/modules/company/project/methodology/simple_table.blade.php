<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Details</p>
        <div class="field">
            {{ EGForm::ckeditor('first_text', [
                'label' => 'First Text',
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
                    <table class="table is-striped is-bordered" id="simple-table" data-next_row="1">
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1 create-div">
        <p class="sub-heading">New Row</p>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('col_1', [
                        'label' => 'Cell 1 (Heading)',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('col_2', [
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
    <div class="column is-10 is-offset-1 create-div">
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('col_3', [
                        'label' => 'Cell 3',
                        'value' => '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('col_4', [
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
    <div class="column is-10 is-offset-1 create-div">
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
            {{ EGForm::ckeditor('last_text', [
                'label' => 'Last Text',
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
        if ($(col_1).val() != "" && $(col_2).val() != "") {
            let row = "<tr data-row='"+row_id+"'>";
                row += "<th><input type='text' name="+row_id+"-1 value='"+$(col_1).val()+"'></input></th>";
                row += "<th><input type='text' name="+row_id+"-2 value='"+$(col_2).val()+"'></input></th>";
                row += "<th><input type='text' name="+row_id+"-3 value='"+$(col_3).val()+"'></input></th>";
                row += "<th><input type='text' name="+row_id+"-4 value='"+$(col_4).val()+"'></input></th>";
            row += "</tr>"
            table.append(row);
            row_id++;
            table.data('next_row', row_id);
            $(col_1).val('');
            $(col_2).val('');
            $(col_3).val('');
            $(col_4).val('');
        } else {
            alert("Please ensure your row is populated");
        }
    })
</script>
