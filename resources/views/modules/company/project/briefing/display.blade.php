<div class="columns">
    <div class="column is-12">
		{{ EGForm::number('project_id', [
            'label' => 'project_id',
            'value' => $record["project_id"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('vtram_id', [
            'label' => 'vtram_id',
            'value' => $record["vtram_id"],
            'type' => $pageType,
            'list' => $vtrams,
            'display_value' => $record->vtram->name ?? 'No VTRAM Selected',
            'selector' => 1
        ]) }}

        {{ EGForm::text('briefed_by', [
            'label' => 'Briefed By',
            'value' => $record["briefed_by"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('name', [
            'label' => 'Briefing Name',
            'value' => $record["name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckEditor('notes', [
            'label' => 'Notes',
            'value' => $record["notes"],
            'type' => $pageType
        ]) }}


	</div>
</div>