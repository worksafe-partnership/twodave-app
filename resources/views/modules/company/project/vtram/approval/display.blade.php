<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('type', [
                        'label' => 'type',
                        'value' => $record->niceType(),
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('completed_by', [
                        'label' => 'Completed By',
                        'value' => $record["completed_by"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('approved_date', [
                       'label' => 'Completed Date',
                        'value' => $record->niceApproved(),
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('resubmit_date', [
                        'label' => 'Resubmit Date',
                        'value' => $record->niceResubmit(),
                        'type' => $pageType,
                    ]) }}
                </div>
            </div>
        </div>
        <div class="field">
            {{ EGForm::ckEditor('comment', [
                'label' => 'Comment',
                'value' => $record["comment"],
                'type' => $pageType
            ]) }}
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('status_at_time', [
                        'label' => 'Status At Time',
                        'value' => $record->niceStatus(),
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('review_document', [
                        'label' => 'Review Document',
                        'value' => $record["review_document"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
	</div>
</div>
