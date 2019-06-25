<div class="columns">
    <div class="column is-12">
		{{ EGForm::number('briefing_id', [
            'label' => 'Briefing',
            'value' => $record["briefing_id"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('file_id', [
            'label' => 'Attendance Document',
            'value' => $record["file_id"],
            'type' => $pageType
        ]) }}


	</div>
</div>