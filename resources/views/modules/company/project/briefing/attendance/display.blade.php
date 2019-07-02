<div class="columns">
    <div class="column is-8 is-offset-2">
		<div class="field">
            {{ EGForm::number('briefing_id', [
                'label' => 'Briefing',
                'value' => $record["briefing_id"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::file('file_id', [
                'label' => 'Attendance Document',
                'value' => $record["file_id"],
                'type' => $pageType
            ]) }}
        </div>


	</div>
</div>