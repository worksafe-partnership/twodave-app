<div class="columns">
    @if(isset($createdFromId))
        {{ EGForm::hidden('created_from_id', [
            'value' => isset($createdFromId) ? $createdFromId : null,
            'type' => $pageType
        ]) }}
        {{ EGForm::hidden('created_from_entity', [
            'value' => isset($createdFromEntity) ? $createdFromEntity : null,
            'type' => $pageType
        ]) }}
    @endif
    @if (isset($key_points))
        {{ EGForm::hidden('key_points', [
            'value' => $key_points,
            'type' => 'create',
        ]) }}
    @endif
    <div class="column is-8 is-offset-2">
        <div class="columns">
            <div class="column is-3">
                <div class="field">
                    {{ EGForm::text('name', [
                        'label' => 'Task Name',
                        'value' => isset($name) ? $name : $record['name'],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-3">
                <div class="field">
                    @if (strpos($identifierPath, 'template') !== false)
                        @if (strpos($identifierPath, 'template.previous') !== false)
                            {{ EGForm::select('company_id', [
                                'label' => 'Company',
                                'value' => $companyId,
                                'type' => $pageType,
                                'list' => [
                                    $companyId => $companies[$companyId]
                                ],
                                'display_value' => $record->company->name ?? 'No Company Selected',
                            ]) }}
                            {{ EGForm::hidden('company_id', [
                                'value' => $companyId,
                                'type' => $pageType,
                            ]) }}
                        @elseif (strpos($identifierPath, 'company.template') === false)
                            {{ EGForm::autocomplete('company_id', [
                                'label' => 'Company',
                                'value' => $record["company_id"] ?? Auth::user()->company_id,
                                'type' => $pageType,
                                'list' => $companies,
                                'display_value' => $record->company->name ?? 'No Company Selected',
                                'selector' => 1
                            ]) }}
                        @else
                            {{ EGForm::select('company_id', [
                                'label' => 'Company',
                                'value' => $parentId,
                                'type' => $pageType,
                                'list' => [
                                    $parentId => $companies[$parentId]
                                ],
                                'display_value' => $record->company->name ?? 'No Company Selected',
                            ]) }}
                            {{ EGForm::hidden('company_id', [
                                'value' => $parentId,
                                'type' => $pageType,
                            ]) }}
                        @endif
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
                        'value' => isset($logo) ? $logo : $record['logo'],
                        'type' => $pageType,
                        'show_image' => true,
                    ]) }}
                </div>
            </div>
            @if (strpos($identifierPath, 'template') === false)
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
            @endif
        </div>
        <div class="columns">
            @if (strpos($identifierPath, 'template') === false)
                <div class="column is-3">
                    <div class="field area-check">
                        {{ EGForm::checkbox('show_area', [
                            'label' => 'Show Area on PDF',
                            'value' => $record['show_area'],
                            'type' => $pageType,
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field area-details">
                        {{ EGForm::text('area', [
                            'label' => 'Area Name',
                            'value' => $record['area'],
                            'type' => $pageType,
                        ]) }}
                    </div>
                </div>
            @endif
            @if (strpos($identifierPath, 'vtram') !== false)
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::checkbox('client_on_pdf', [
                            'label' => 'Show Client Name on PDF',
                            'value' => $record['client_on_pdf'],
                            'type' => $pageType,
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::checkbox('pc_on_pdf', [
                            'label' => 'Show Principal Contractor Name on PDF (if applicable)',
                            'value' => $record['pc_on_pdf'],
                            'type' => $pageType,
                        ]) }}
                    </div>
                </div>
            @endif
        </div>
        @if ($pageType != 'create')
        </div>
    </div>
    <hr>
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <h2 class="sub-heading">Configuration</h2>
            @include('modules.company.project.vtram.ckeditor-key')
            <div class="columns">
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::ckeditor('main_description', [
                            'label' => 'Title Block Text',
                            'value' => $record["main_description"],
                            'type' => $pageType
                        ]) }}
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::ckeditor('post_risk_assessment_text', [
                            'label' => 'Post Risk Assessment Text',
                            'value' => isset($post_risk_assessment_text) ? $post_risk_assessment_text : $record['post_risk_assessment_text'],
                            'type' => $pageType
                        ]) }}
                    </div>
                </div>
            </div>
        @endif
        @if ($pageType == 'create' && !isset($_GET['template']))
        </div>
    </div>
    <hr>
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <h2 class="sub-heading">Configuration</h2>
            @include('modules.company.project.vtram.ckeditor-key')
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
        @endif
        @if ($pageType == 'view')
            @if(isset($comments) && $comments->isNotEmpty())
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-8 is-offset-2">
                <h2 class="sub-heading">Comments</h2>
                <div class="columns">
                    <div class="column">
                        @foreach($comments as $comment)
                            <p>{{$comment->comment}} - <i>{{$comment->completedByName()}} {{$comment->created_at->format('d/m/Y')}}</i></p>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
    <hr>
    <div class="columns">
        <div class="column is-8 is-offset-2">
            <h2 class="sub-heading">Approval Information</h2>
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
                        {{ EGForm::text('resubmit_by', [
                            'label' => 'Resubmit By',
                            'value' => $record->niceResubmitByDate(),
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
                        {{ EGForm::text('approved_date', [
                            'label' => 'Approved Date',
                            'value' => $record->niceApprovedDate(),
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::text('review_due', [
                            'label' => 'Review Due',
                            'value' => $record->niceReviewDueDate(),
                            'type' => $pageType,
                            'disabled' => 1
                        ]) }}
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        {{ EGForm::text('date_replaced', [
                            'label' => 'Date Replaced',
                            'value' => $record->niceDateReplaced(),
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
                        'value' => $record->createdFrom,
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
        $oldArea = old('show_area');
    @endphp
    @if (($oldR != "1" && $pageType == 'create') || (isset($record) && $record->show_responsible_person != "1" && ($pageType == 'edit'  || $pageType == 'view') && $oldR != "1"))
        @push('styles')
            <style>
               .responsible-details {
                    display: none;
                }
            </style>
        @endpush
    @endif
    @if (($oldArea != "1" && $pageType == 'create') || (isset($record) && $record->show_area != "1" && ($pageType == 'edit' || $pageType == 'view') && $oldArea != "1"))
        @push('styles')
            <style>
               .area-details {
                    display: none;
                }
            </style>
        @endpush
    @endif
    <style>
        .radio-inline {
            padding-right: 15px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $('.responsible-check [id^=show_responsible_person]').click(function() {
            var name = $(this).prev().val();
            if (name == "1") {
                $('#responsible_person').val('');
                $('.responsible-details').hide();
            } else {
                $('.responsible-details').show();
            }
        });
        $('.area-check [id^=show_area]').click(function() {
            var name = $(this).prev().val();
            if (name == "1") {
                $('#area').val('');
                $('.area-details').hide();
            } else {
                $('.area-details').show();
            }
        });
    </script>
    @if (strpos($identifierPath, 'template') !== false)
        <script>
            $('select[name="company_id"]').change(function () {
                $.ajax({
                    url: '/company/' + $(this).val() + '/getPresets',
                    method: 'GET',
                    success: function (data) {
                        data = JSON.parse(data);
                        if (data.status == 'success') {
                            CKEDITOR.instances.task_description.setData(data.text.task_description);
                            CKEDITOR.instances.plant_and_equipment.setData(data.text.plant_and_equipment);
                            CKEDITOR.instances.disposing_of_waste.setData(data.text.disposing_of_waste);
                            CKEDITOR.instances.first_aid.setData(data.text.first_aid);
                            CKEDITOR.instances.noise.setData(data.text.noise);
                            CKEDITOR.instances.working_at_height.setData(data.text.working_at_height);
                            CKEDITOR.instances.manual_handling.setData(data.text.manual_handling);
                            CKEDITOR.instances.accident_reporting.setData(data.text.accident_reporting);
                            toastr.success("New Company Presets applied");
                        } else {
                            toastr.error("Failed to get Company Preset values");
                        }
                    },
                    error: function (data) {
                        if (data.status == 422) {
                            var errors = '';
                            $.each(data.responseJSON.errors, function(key,val) {
                                toastr.error(val);
                            });
                        } else if (data.status == 401) {
                            toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                        } else {
                            toastr.error('An error has occured when collecting company presets');
                        }
                    }
                });
            });
        </script>
    @endif
@endpush

@include('modules.company.project.vtram.create-modal')
@if ($pageType == 'view' && strpos($identifierPath, 'template') !== false)
    @include('modules.company.template.create-vtrams-modal')
@endif
