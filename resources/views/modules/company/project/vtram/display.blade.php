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
                    @if (strpos($identifierPath, 'template') !== false)
                        {{ EGForm::select('company_id', [
                            'label' => 'Company (Leave blank for all)',
                            'value' => $record["company_id"],
                            'type' => $pageType,
                            'list' => $companies,
                            'display_value' => $record->company->name ?? 'No Company Selected',
                            'selector' => 1
                        ]) }}
                    @else
                        {{ EGForm::text('number', [
                            'label' => 'Number',
                            'value' => $record["number"],
                            'type' => $pageType,
                            'disabled' => 1,
                        ]) }}
                    @endif
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::select('status', [
                        'label' => 'Status',
                        'value' => $record->status ?? 'NEW',
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
                    {{ EGForm::file('logo', [
                        'label' => 'Logo (Company Logo used if blank)',
                        'value' => $record["logo"],
                        'type' => $pageType,
                        'show_image' => true,
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field responsible-check">
                    {{ EGForm::checkbox('show_responsible_person', [
                        'label' => 'Show Responsible Person',
                        'value' => $record->show_responsible_person ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field responsible-details">
                    {{ EGForm::text('responsible_person', [
                        'label' => 'Responsible Person',
                        'value' => $record["responsible_person"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        @if ($pageType != 'create')
            <div class="columns">
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::ckeditor('description', [
                            'label' => 'Company Description',
                            'value' => $record["description"],
                            'type' => $pageType
                        ]) }}
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::ckeditor('post_risk_assessment_text', [
                            'label' => 'Post Risk Assessment Text (Duplicated - need to remove one)',
                            'value' => isset($post_risk_assessment_text) ? $post_risk_assessment_text : $record['post_risk_assessment_text'],
                            'type' => $pageType
                        ]) }}
                    </div>
                </div>
            </div>
        @endif
        <hr>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('main_description', [
                        'label' => 'Main Description',
                        'value' => isset($main_description) ? $main_description : $record['main_description'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('post_risk_assessment_text', [
                        'label' => 'Post Risk Assessment Text (Duplicated - need to remove one)',
                        'value' => isset($post_risk_assessment_text) ? $post_risk_assessment_text : $record['post_risk_assessment_text'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('task_description', [
                        'label' => 'Task Description',
                        'value' => isset($task_description) ? $task_description : $record['task_description'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('plant_and_equipment', [
                        'label' => 'Plant and Equipment',
                        'value' => isset($plant_and_equipment) ? $plant_and_equipment : $record['plant_and_equipment'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('disposing_of_waste', [
                        'label' => 'Disposing of Waste',
                        'value' => isset($disposing_of_waste) ? $disposing_of_waste : $record['disposing_of_waste'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('first_aid', [
                        'label' => 'First Aid',
                        'value' => isset($first_aid) ? $first_aid : $record['first_aid'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('noise', [
                        'label' => 'Noise',
                        'value' => isset($noise) ? $noise : $record['noise'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('working_at_height', [
                        'label' => 'Working at Height',
                        'value' => isset($working_at_height) ? $working_at_height : $record['working_at_height'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('manual_handling', [
                        'label' => 'Manual Handling',
                        'value' => isset($manual_handling) ? $manual_handling : $record['manual_handling'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('accident_reporting', [
                        'label' => 'Accident Reporting',
                        'value' => isset($accident_reporting) ? $accident_reporting : $record['accident_reporting'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        @if ($pageType == 'view')
            <div class="columns">
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::text('created_by', [
                            'label' => 'Created By',
                            'value' => $record->createdBy->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::text('updated_by', [
                            'label' => 'Updated By',
                            'value' => $record->updatedBy->name ?? '',
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::text('submitted_by', [
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
                        {{ EGForm::text('approved_by', [
                            'label' => 'Approved By',
                            'value' => $record->approvedName() ?? '',
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
            @if (strpos($identifierPath, 'vtram') !== false)
                <div class="field">
                    {{ EGForm::text('created_from', [
                        'label' => 'Created From',
                        'value' => $record->createdFrom->name ?? '',
                        'type' => $pageType,
                        'disabled' => 1
                    ]) }}
                </div>
            @endif
        @endif



	</div>
</div>
@push('styles')
    @php
        $oldR = old('show_responsible_person');
    @endphp
    @if (($oldR != "1" && $pageType == 'create') || (isset($record) && $record->show_responsible_person != "1"))
        @push('styles')
            <style>
               .responsible-details {
                    display: none;
                }
            </style>
        @endpush
    @endif
@endpush
@push('scripts')
    <script>
        $('.responsible-check [id^=show_responsible_person]').click(function() {
            var name = $(this).prev().val();
            if (name == "1") {
                $('.responsible-details').hide();
            } else {
                $('.responsible-details').show();
            }
        });
    </script>
@endpush
