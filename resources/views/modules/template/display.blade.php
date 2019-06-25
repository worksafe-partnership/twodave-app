<div class="columns">
    <div class="column is-12">
		{{ EGForm::select('company_id', [
            'label' => 'Company (Leave blank to make available for all Companies)',
            'value' => $record["company_id"],
            'type' => $pageType,
            'list' => $companies,
            'display_value' => $record->company->name ?? 'No Company Selected',
            'selector' => 1
        ]) }}

        {{ EGForm::text('name', [
            'label' => 'Name',
            'value' => $record["name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckeditor('description', [
            'label' => 'Description',
            'value' => $record["description"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('logo', [
            'label' => 'Logo (Overrides Company Logo on VTRAMs)',
            'value' => $record["logo"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('reference', [
            'label' => 'Reference',
            'value' => $record["reference"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckeditor('key_points', [
            'label' => 'Key Points',
            'value' => $record["key_points"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('havs_noise_assessment', [
            'label' => 'HAVs/Noise Assessment Document',
            'value' => $record["havs_noise_assessment"],
            'type' => $pageType
        ]) }}

        {{ EGForm::file('coshh_assessment', [
            'label' => 'COSHH Assessment Document',
            'value' => $record["coshh_assessment"],
            'type' => $pageType
        ]) }}

        {{ EGForm::date('review_due', [
            'label' => 'Review Due',
            'value' => $record["review_due"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::date('approved_date', [
            'label' => 'Approved Date',
            'value' => $record["approved_date"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::number('original_id', [
            'label' => 'original_id',
            'value' => $record["original_id"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::number('revision_number', [
            'label' => 'Revision Number',
            'value' => $record["revision_number"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::select('status', [
            'label' => 'Status',
            'value' => 'NEW',
            'type' => $pageType,
            'disabled' => 1,
            'list' => config('egc.vtram_status'),
            'display_value' => isset($record->status) ? config('egc.vtram_status')[$record->status] : ''
        ]) }}

        {{ EGForm::number('created_by', [
            'label' => 'Created By',
            'value' => $record->created->name ?? '',
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::number('updated_by', [
            'label' => 'Updated By',
            'value' => $record->updated->name ?? '',
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::number('submitted_by', [
            'label' => 'Submitted By',
            'value' => $record->submitted->name ?? '',
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::number('approved_by', [
            'label' => 'Approved By',
            'value' => $record->approved->name ?? '',
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::date('date_replaced', [
            'label' => 'Date Replaced',
            'value' => $record["date_replaced"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}

        {{ EGForm::date('resubmit_by', [
            'label' => 'Resubmit By',
            'value' => $record["resubmit_by"],
            'type' => $pageType,
            'disabled' => 1
        ]) }}


	</div>
</div>
