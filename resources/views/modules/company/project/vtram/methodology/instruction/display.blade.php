<div class="columns">
    <div class="column is-10 is-offset-1">
		<div class="field">
            {{ EGForm::textarea('description', [
                'label' => 'Description',
                'value' => $record["description"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('label', [
                'label' => 'Label',
                'value' => $record["label"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::checkbox('heading', [
                'label' => 'Heading',
                'value' => $record["heading"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::number('list_order', [
                'label' => 'list_order',
                'value' => $record["list_order"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::file('image', [
                'label' => 'Image',
                'value' => $record["image"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::number('methodology_id', [
                'label' => 'methodology_id',
                'value' => $record["methodology_id"],
                'type' => $pageType
            ]) }}
        </div>


	</div>
</div>
