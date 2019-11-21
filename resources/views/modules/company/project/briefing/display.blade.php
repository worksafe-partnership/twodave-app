<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('name', [
                        'label' => 'Briefing Name',
                        'value' => $record["name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('briefed_by', [
                        'label' => 'Briefed By',
                        'value' => $record["briefed_by"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::select('vtram_id', [
                        'label' => $company->vtrams_name.' Briefed',
                        'value' => $record["vtram_id"],
                        'type' => $pageType,
                        'list' => $vtrams,
                        'display_value' => $record->vtram->name ?? 'No '.$company->vtrams_name.' Selected',
                        'selector' => 1
                    ]) }}
                </div>
            </div>
        </div>

        @if ($pageType == 'view')
            <div class="field">
                <a download="Attendance Schedule.pdf" href="/attendance_schedule.pdf" class="button">Download Attendance Schedule Template</a>
            </div>
        @endif
        <div class="field">
            {{ EGForm::ckEditor('notes', [
                'label' => 'Notes',
                'value' => $record["notes"],
                'type' => $pageType
            ]) }}
        </div>
	</div>
</div>
