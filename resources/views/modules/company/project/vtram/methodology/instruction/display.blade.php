<div class="columns">
    <div class="column is-12">
		{{ EGForm::textarea('description', [
            'label' => 'Description',
            'value' => $record["description"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('label', [
            'label' => 'Label',
            'value' => $record["label"],
            'type' => $pageType
        ]) }}

        {{ EGForm::checkbox('heading', [
            'label' => 'Heading',
            'value' => $record["heading"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('list_order', [
            'label' => 'list_order',
            'value' => $record["list_order"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('image', [
            'label' => 'Image',
            'value' => $record["image"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('methodology_id', [
            'label' => 'methodology_id',
            'value' => $record["methodology_id"],
            'type' => $pageType
        ]) }}


	</div>
</div>