<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('name', [
                        'label' => 'Name',
                        'value' => $record["name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('short_name', [
                        'label' => 'Short Name',
                        'value' => $record["short_name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::select('review_timescale', [
                        'label' => 'Review Timescale',
                        'value' => $record->review_timescale ?? 0,
                        'type' => $pageType,
                        'list' => config('egc.review_timescales')
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::text('vtrams_name', [
                        'label' => 'VTRAMS Name',
                        'value' => $record->vtrams_name ?? 'Task RAM',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        @if($pageType == 'edit')
            <div class="columns">
                <div class="column hidden" id="timescale_1">
                    <div class="field">
                        {{ EGForm::select('timescale_update', [
                            'label' => 'Updating Timescale',
                            'value' => 0,
                            'type' => $pageType,
                            'list' => [
                                'forward' => 'Only Apply Going Forward',
                                'all' => 'Apply to All Projects',
                                'select' => 'Select Projects to Change'
                            ]
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column hidden" id="timescale_2">
                    <div class="field">
                        {{ EGForm::multiCheckbox("projects_to_update", [
                            "type"  => $pageType,
                            "label" => "Projects to Update",
                            "list"  => $projects,
                            "values"  => [],
                            "list-style" => "multi-block"
                        ]) }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-8 is-offset-2">
        <h2 class="sub-heading">Contact Information</h2>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('email', [
                        'label' => 'Contact Email',
                        'value' => $record["email"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('phone', [
                        'label' => 'Phone Number',
                        'value' => $record["phone"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('fax', [
                        'label' => 'Fax Number',
                        'value' => $record["fax"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-8 is-offset-2">
        <h2 class="sub-heading">Colours, Labels and Logo</h2>
        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('low_risk_character', [
                        'label' => 'Low Risk Label',
                        'value' => $record->low_risk_character ?? 'L',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('med_risk_character', [
                        'label' => 'Medium Risk Label',
                        'value' => $record->med_risk_character ?? 'M',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('high_risk_character', [
                        'label' => 'High Risk Label',
                        'value' => $record->high_risk_character ?? 'H',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('no_risk_character', [
                        'label' => 'No Risk Label',
                        'value' => $record->no_risk_character ?? '#',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ VTForm::colour('primary_colour', [
                        'label' => 'Primary Colour',
                        'value' => $record->primary_colour ?? '#203878',
                        'type' => $pageType,
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ VTForm::colour('secondary_colour', [
                        'label' => 'Secondary Colour',
                        'value' => $record->secondary_colour ?? '#000000',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::checkbox('light_text', [
                        'label' => 'Dark Text',
                        'value' => $record->light_text ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('accept_label', [
                        'label' => 'Accept Label',
                        'value' => $record->accept_label ?? 'Accept',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('amend_label', [
                        'label' => 'Amend Label',
                        'value' => $record->amend_label ?? 'Amend',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('reject_label', [
                        'label' => 'Reject Label',
                        'value' => $record->reject_label ?? 'Reject',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

        <div class="field">
            {{ EGForm::file('logo', [
                'label' => 'Logo',
                'value' => $record["logo"],
                'type' => $pageType,
                'show_image' => true,
            ]) }}
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-8 is-offset-2">
        <h2 class="sub-heading">VTRAMS Configuration</h2>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('main_description', [
                        'label' => 'Main Description',
                        'value' => $record['main_description'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('post_risk_assessment_text', [
                        'label' => 'Post Risk Assessment Text',
                        'value' => $record['post_risk_assessment_text'],
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
                        'value' => $record['task_description'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('plant_and_equipment', [
                        'label' => 'Plant and Equipment',
                        'value' => $record['plant_and_equipment'],
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
                        'value' => $record['disposing_of_waste'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('first_aid', [
                        'label' => 'First Aid',
                        'value' => $record['first_aid'],
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
                        'value' => $record['noise'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('working_at_height', [
                        'label' => 'Working at Height',
                        'value' => $record['working_at_height'],
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
                        'value' => $record['manual_handling'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('accident_reporting', [
                        'label' => 'Accident Reporting',
                        'value' => $record['accident_reporting'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
	</div>
</div>


@if($pageType == "edit")
    @push('scripts')
        <script>
            $('#review_timescale').change(function() {
                $('#timescale_1').removeClass('hidden');
            });

            $('#timescale_update').on('change', function() {
                if($(this).val() == "select") {
                    $('#timescale_2').removeClass('hidden');
                } else {
                    $('#timescale_2').addClass('hidden');
                }
            });
        </script>
    @endpush
@endif
