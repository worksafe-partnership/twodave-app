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
        <p class="sub-heading">Table:</p>
        <div class="columns">
            <div class="column">
                <div class="field">
                    <table class="table is-striped is-bordered" id="complex-table" data-next_row="0">
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
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <button type="button" class="button is-primary" id="add-row-complex">Add Row</button>
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
    $('#add-row-complex').on('click', function() {
        let table = $('#complex-table');
        let row_id = table.attr('data-next_row');
        let type = "td";
        if (row_id == 1) {
            type = "th";
        }
        let row = "<tr class='columns' data-row='"+row_id+"'>";
            row += "<"+type+" class='column'><input type='text' name='row_"+row_id+"__col_1' value=''></input></"+type+">";
            row += "<"+type+" class='column'><input type='text' name='row_"+row_id+"__col_2' value=''></input></"+type+">";
            row += "<"+type+" class='column'><input type='text' name='row_"+row_id+"__col_3' value=''></input></"+type+">";
            row += "<"+type+" class='column'><input type='text' name='row_"+row_id+"__col_4' value=''></input></"+type+">";
            row += "<td class='column is-1'><a class='handms-icons delete_icon' onclick='deleteComplexRow("+parseInt(row_id)+")'><svg class='eg-delete'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/eg-icons.svg#eg-delete'></use></svg></a></td>";
            row += "</tr>"
        table.append(row);
        row_id++;
        table.attr('data-next_row', row_id);
    })

    function deleteComplexRow(key) {
        $('#complex-table tbody tr[data-row='+key+']').remove();

        let remaining = $('#complex-table tbody tr');
        $.each(remaining, function(index, tr) {
            if (index >= key) {
                let cell = $(tr);
                cell.attr('data-row', index);
                cell.find('[name*="col_1"]').attr('name', 'row_'+index+'__col_1');
                cell.find('[name*="col_2"]').attr('name', 'row_'+index+'__col_2');
                cell.find('[name*="col_3"]').attr('name', 'row_'+index+'__col_3');
                cell.find('[name*="col_4"]').attr('name', 'row_'+index+'__col_4');
                cell.find('.delete_icon').attr('onclick', 'deleteComplexRow('+index+')');
            }
        });
        $('#complex-table').attr('data-next_row', remaining.length);
    }

</script>

<style>
    #complex-table input{
        width: 100%;
    }

    #complex-table * {
        box-sizing: border-box;
    }
</style>
