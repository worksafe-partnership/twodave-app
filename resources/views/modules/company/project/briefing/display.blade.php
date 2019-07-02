<div class="columns">
    <div class="column is-8 is-offset-2">
		<div class="field">
            {{ EGForm::number('project_id', [
                'label' => 'project_id',
                'value' => $record["project_id"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::select('vtram_id', [
                'label' => 'vtram_id',
                'value' => $record["vtram_id"],
                'type' => $pageType,
                'list' => $vtrams,
                'display_value' => $record->vtram->name ?? 'No VTRAM Selected',
                'selector' => 1
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('briefed_by', [
                'label' => 'Briefed By',
                'value' => $record["briefed_by"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('name', [
                'label' => 'Briefing Name',
                'value' => $record["name"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::ckEditor('notes', [
                'label' => 'Notes',
                'value' => $record["notes"],
                'type' => $pageType
            ]) }}
        </div>


	</div>
</div>