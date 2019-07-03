<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="field">
            {{ EGForm::file('file_id', [
                'label' => 'Attendance Document',
                'value' => $record["file_id"],
                'type' => $pageType
            ]) }}
        </div>
	</div>
</div>
