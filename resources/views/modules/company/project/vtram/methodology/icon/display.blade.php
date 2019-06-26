<div class="columns">
    <div class="column is-12">
		{{ EGForm::select('image', [
            'label' => 'Image',
            'value' => $record["image"],
            'type' => $pageType,
            'list' => config('egc.icons'),
            'selector' => 1,
            'display_value' => config('egc.icons')[$record->image] ?? ''
        ]) }}

        {{ EGForm::text('text', [
            'label' => 'Text',
            'value' => $record["text"],
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

        {{ EGForm::select('type', [
            'label' => 'Type',
            'value' => $record["type"],
            'type' => $pageType,
            'list' => config('egc.icon_types'),
            'selector' => 1,
            'display_value' => config('egc.icon_types')[$record->type] ?? ''
        ]) }}


	</div>
</div>