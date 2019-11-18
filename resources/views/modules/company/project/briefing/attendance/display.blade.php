<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::file('file_id', [
                'label' => 'Attendance Document',
                'value' => $record["file_id"],
                'type' => $pageType
            ]) }}
        </div>
	</div>
</div>
