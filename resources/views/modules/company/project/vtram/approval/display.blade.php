<div class="columns">
    <div class="column is-8 is-offset-2">
		<div class="field">
            {{ EGForm::text('entity', [
                'label' => 'entity',
                'value' => $record["entity"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::number('entity_id', [
                'label' => 'entity_id',
                'value' => $record["entity_id"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::ckEditor('comment', [
                'label' => 'Comment',
                'value' => $record["comment"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('type', [
                'label' => 'type',
                'value' => $record["type"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('completed_by', [
                'label' => 'completed_by',
                'value' => $record["completed_by"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::number('completed_by_id', [
                'label' => 'completed_by_id',
                'value' => $record["completed_by_id"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::date('approved_date', [
                'label' => 'approved_date',
                'value' => $record["approved_date"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::date('resubmit_date', [
                'label' => 'Resubmit Date',
                'value' => $record["resubmit_date"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::text('status_at_time', [
                'label' => 'status_at_time',
                'value' => $record["status_at_time"],
                'type' => $pageType
            ]) }}
        </div>

        <div class="field">
            {{ EGForm::file('review_document', [
                'label' => 'Review Document',
                'value' => $record["review_document"],
                'type' => $pageType
            ]) }}
        </div>


	</div>
</div>