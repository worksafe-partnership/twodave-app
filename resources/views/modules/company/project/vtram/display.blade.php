<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="columns">
            <div class="column is-3"> 
                <div class="field">
                    {{ EGForm::text('name', [
                        'label' => 'Name',
                        'value' => $record["name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('reference', [
                        'label' => 'Reference',
                        'value' => $record["reference"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::select('status', [
                        'label' => 'Status',
                        'value' => 'NEW',
                        'type' => $pageType,
                        'disabled' => 1,
                        'list' => config('egc.vtram_status'),
                        'display_value' => isset($record->status) ? config('egc.vtram_status')[$record->status] : ''
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::number('revision_number', [
                        'label' => 'Revision Number',
                        'value' => $record["revision_number"],
                        'type' => $pageType,
                        'disabled' => 1
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::file('logo', [
                        'label' => 'Logo (Company Logo used if blank)',
                        'value' => $record["logo"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::checkbox('show_responsible_person', [
                        'label' => 'Show Responsible Person',
                        'value' => $record->show_responsible_person ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('responsible_person', [
                        'label' => 'Responsible Person',
                        'value' => $record["responsible_person"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('description', [
                        'label' => 'Description',
                        'value' => $record["description"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('key_points', [
                        'label' => 'Key Points',
                        'value' => $record["key_points"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('havs_noise_assessment', [
                        'label' => 'HAVs/Noise Assessment Document',
                        'value' => $record["havs_noise_assessment"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('coshh_assessment', [
                        'label' => 'COSHH Assessment Document',
                        'value' => $record["coshh_assessment"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('pre_risk_assessment_text', [
                        'label' => 'Pre Risk Assessment Text',
                        'value' => $record["pre_risk_assessment_text"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('post_risk_assessment_text', [
                        'label' => 'Post Risk Assessment Text',
                        'value' => $record["post_risk_assessment_text"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        <div class="field">
            {{ EGForm::checkbox('dynamic_risk', [
                'label' => 'Dynamic Risk (Adds Dynamic Risk boxes to the VTRAM)',
                'value' => $record->dynamic_risk ?? false,
                'type' => $pageType
            ]) }}
        </div>

        @if ($pageType == 'view')
            <div class="columns">
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::number('created_by', [
                            'label' => 'Created By',
                            'value' => $record->created->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div> 
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::number('updated_by', [
                            'label' => 'Updated By',
                            'value' => $record->updated->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div> 
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::number('submitted_by', [
                            'label' => 'Submitted By',
                            'value' => $record->submitted->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div> 
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::date('resubmit_by', [
                            'label' => 'Resubmit By',
                            'value' => $record["resubmit_by"],
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::number('approved_by', [
                            'label' => 'Approved By',
                            'value' => $record->approved->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::date('approved_date', [
                            'label' => 'Approved Date',
                            'value' => $record["approved_date"],
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::date('review_due', [
                            'label' => 'Review Due',
                            'value' => $record["review_due"],
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::date('date_replaced', [
                            'label' => 'Date Replaced',
                            'value' => $record["date_replaced"],
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
            </div>

            <div class="field">
                {{ EGForm::number('created_from', [
                    'label' => 'Created From',
                    'value' => $record->createdFrom->name ?? '',
                    'type' => $pageType,
                    'disabled' => 1
                ]) }}
            </div>
        @endif
	</div>
</div>
