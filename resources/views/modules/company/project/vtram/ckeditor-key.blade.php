<h4>Substitution Key</h4>
<table class="key-table">
    <tr>
        <th>&lbrace;&lbrace;title&rbrace;&rbrace;</th>
        <td>Task Name</td>
    </tr>
    <tr>
        <th>&lbrace;&lbrace;company_name&rbrace;&rbrace;</th>
        <td>Company Name</td>
    </tr>
    <tr>
        <th>&lbrace;&lbrace;company_short_name&rbrace;&rbrace;</th>
        <td>Company Short Name</td>
    </tr>
    <tr>
        <th>&lbrace;&lbrace;page_break&rbrace;&rbrace;</th>
        <td>Inserts a Page Break</td>
    </tr>
</table>
@push('styles')
<style>
   .key-table {
        margin-bottom: 20px;
    } 
    .key-table tr:not(:last-of-type) {
        border-bottom: 1px solid black;
    }
    .key-table th, .key-table td {
        padding: 5px;
    }
</style>
@endpush
