<div class="columns">
    <div class="column is-12">
		{{ EGForm::text('col_1', [
            'label' => 'Column 1',
            'value' => $record["col_1"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('col_2', [
            'label' => 'Column 2',
            'value' => $record["col_2"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('col_3', [
            'label' => 'Column 3',
            'value' => $record["col_3"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('col_4', [
            'label' => 'Column 4',
            'value' => $record["col_4"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('list_order', [
            'label' => 'list_order',
            'value' => $record["list_order"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('methodology_id', [
            'label' => 'methodology_id',
            'value' => $record["methodology_id"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('cols_filled', [
            'label' => 'cols_filled',
            'value' => $record["cols_filled"],
            'type' => $pageType
        ]) }}


	</div>
</div>