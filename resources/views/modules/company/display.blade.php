<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::text('name', [
                        'label' => 'Name',
                        'value' => $record["name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::text('short_name', [
                        'label' => 'Short Name',
                        'value' => $record["short_name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
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
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Contact Information</h2>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('contact_name', [
                        'label' => 'Contact Name',
                        'value' => $record["contact_name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
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
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Subscription Information</h2>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::number('num_vtrams', [
                        'label' => 'Number of VTRAMS and Templates (counted separately)',
                        'value' => $record->num_vtrams ?? '',
                        'type' => $pageType,
                        'attributes' => [
                            'step' => 1,
                            'min' => 1,
                        ]
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::select('sub_frequency', [
                        'label' => 'Subscription Frequency',
                        'value' => $record->sub_frequency ?? '',
                        'type' => $pageType,
                        'list' => config('egc.sub_frequency'),
                        'selector' => true
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::date('start_date', [
                        'label' => 'Start Date',
                        'value' => $record->start_date ?? '',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Colours, Labels and Logo</h2>
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
                        'value' => $record->secondary_colour ?? '#203878',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::checkbox('light_text', [
                        'label' => 'Dark Text<br>(if a light Primary Colour is chosen, tick this box)',
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
@php
    $user = Auth::User();
@endphp
@if($user->inRole('evergreen') || $user->inRole('admin'))
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('billable', [
                        'label' => 'Billable?',
                        'value' => $record->billable ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('is_principal_contractor', [
                        'label' => 'Is Principal Contractor?',
                        'value' => $record->is_principal_contractor ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('allow_file_uploads', [
                        'label' => 'Allow Users to Upload VTRAMS as PDFs?',
                        'value' => $record->allow_file_uploads ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">PDF Footer Information</h2>
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('show_document_ref_on_pdf', [
                        'label' => 'Show Document Reference?',
                        'value' => $record->show_document_ref_on_pdf ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('show_revision_no_on_pdf', [
                        'label' => 'Show Revision Number on PDF?',
                        'value' => $record->show_revision_no_on_pdf ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('show_message_on_pdf', [
                        'label' => 'Show Message on PDF?',
                        'value' => $record->show_message_on_pdf ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column">
                <div class="field">
                    {{ EGForm::textarea('message', [
                        'label' => 'Message to Show',
                        'value' => $record['message'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">VTRAMS Configuration</h2>
        @include('modules.company.project.vtram.ckeditor-key')
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::ckeditor('main_description', [
                        'label' => 'Title Block Text',
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
	</div>
</div>
@if ($pageType == 'edit')
    <div class="columns">
        <div class="column is-10 is-offset-1">
            @include('modules.company.project.vtram.methodstatements')
            @include('modules.company.project.vtram.script_style_for_both')
        </div>
    </div>
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
                    $('#timescale_2 input[type="checkbox"]').prop('checked', false); // deslect all checkboxes on deselect
                }
            });

            @if(old('review_timescale') && old('review_timescale') != $record['review_timescale']) // ensuring relevant fields appear on failed validation
               $('#timescale_1').removeClass('hidden');
               if ($('#timescale_update').val() == 'select') {
                    $('#timescale_2').removeClass('hidden');
               }
            @endif
        </script>
    @endpush
@endif
