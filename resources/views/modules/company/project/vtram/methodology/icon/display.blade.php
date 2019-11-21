<div class="columns">
    <div class="column is-10 is-offset-1">
		<div class="field">
            {{ EGForm::select('image', [
                'label' => 'Image',
                'value' => $record["image"],
                'type' => $pageType,
                'list' => config('egc.icons'),
                'selector' => 1,
                'display_value' => config('egc.icons')[$record->image] ?? ''
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('text', [
                'label' => 'Text',
                'value' => $record["text"],
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
            {{ EGForm::number('methodology_id', [
                'label' => 'methodology_id',
                'value' => $record["methodology_id"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
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
</div>
