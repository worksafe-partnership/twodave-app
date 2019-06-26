<div class="columns">
    <div class="column is-12">
		{{ EGForm::text('category', [
            'label' => 'Category',
            'value' => $record["category"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('entity', [
            'label' => 'entity',
            'value' => $record["entity"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('entity_id', [
            'label' => 'entity_id',
            'value' => $record["entity_id"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckeditor('text_before', [
            'label' => 'Text Before',
            'value' => $record["text_before"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckeditor('text_after', [
            'label' => 'Text After',
            'value' => $record["text_after"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('image', [
            'label' => 'Image',
            'value' => $record["image"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('image_on', [
            'label' => 'Image On',
            'value' => $record["image_on"],
            'type' => $pageType,
            'list' => config('egc.first_last'),
            'selector' => 1,
            'display_value' => config('egc.first_last')[$record->image_on] ?? ''
        ]) }}

        {{ EGForm::number('list_order', [
            'label' => 'list_order',
            'value' => $record["list_order"],
            'type' => $pageType
        ]) }}


	</div>
</div>