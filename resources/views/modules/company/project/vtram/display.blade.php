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
    <div class="column is-10 is-offset-1">
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
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::checkbox('general_rams', [
                        'label' => 'General RAMS?',
                        'value' => $record['general_rams'],
                        'type' => $pageType,
                    ]) }}
                </div>
            </div>
            @if (strpos($identifierPath, 'vtram') !== false)
            <div class="column">
                {{ EGForm::select('company_logo_id', [
                    'label' => 'Select Company For Logo',
                    'value' => $record->company_logo_id ?? $company->id,
                    'list' => $compAndContractors,
                    'display_value' => isset($record) && $record->company_logo != null ? $record->company_logo->name : $company->name,
                    'type' => $pageType,
                ]) }}
            </div>
            @endif
        </div>
        @if (strpos($identifierPath, 'vtram') !== false)
        <hr>
        <div class="columns">
            <div class="column">
                <h2 class="sub-heading">Users</h2>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12">
                <div class="field">
                    @if ($pageType == 'view')
                        {{ EGForm::multicheckbox('associated_users', [
                            'label' => 'Associated Users',
                            'values' => $associatedUsers,
                            'list' => $projectUsers,
                            'type' => $pageType,
                            'list-style' => 'multi-block',
                        ]) }}
                    @else
                        @php
                            $old = old('associated_users');

                            if (count($old) > 0) {
                                foreach ($old as $val) {
                                    $associated_users[$val] = true;
                                }
                            }
                        @endphp

                        {{ VTForm::multiSelect('associated_users[]', [
                            'label' => 'Associated Users',
                            'value' => $associatedUsers,
                            'list' => $projectUsers,
                            'type' => $pageType,
                        ]) }}
                    @endif
                </div>
            </div>
        </div>
        @endif
        @if ($pageType != 'create')
        </div>
    </div>
    <hr>
    <div class="columns">
        <div class="column is-10 is-offset-1">
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
        @if ($pageType == 'edit')
        </div>
    </div>
    <hr>
    <div class="columns is-multiline">
        <div class="column is-6 box-container">
            @include('modules.company.project.vtram.methodstatements')
            <hr>
        </div>
        <div class="column is-6 box-container">
            @include('modules.company.project.vtram.hazards')
            <hr>
        </div>
    </div>
        @include('modules.company.project.vtram.script_style_for_both')
        @endif

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <a download="Noise Vibration Assessment.xls" href="/Noise_Vibration_Assessment.xls" class="button">Download HAVS/Noise Assessment</a>
                </div>
            </div>
            <div class="column is-6">
                @if (strpos($identifierPath, 'vtram') !== false)
                    <div class="field">
                        {{ EGForm::checkbox('dynamic_risk', [
                            'label' => 'Dynamic Risk (Adds Dynamic Risk boxes to the Risk Assessment)',
                            'value' => $record['dynamic_risk'] ?? false,
                            'type' => $pageType
                        ]) }}
                    </div>
                @endif
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('coshh_assessment', [
                        'label' => 'COSHH Assessment Document',
                        'value' => $record["coshh_assessment"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('havs_noise_assessment', [
                        'label' => 'HAVS/Noise Assessment Document',
                        'value' => $record["havs_noise_assessment"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="field">
                    {{ EGForm::ckeditor('key_points', [
                        'label' => 'Key Points',
                        'value' => $record["key_points"],
                        'type' => 'edit'
                    ]) }}
                </div>
            </div>
        </div>
        @if ($pageType == 'view')
            @if(isset($comments) && $comments->isNotEmpty())
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-10 is-offset-1">
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
        <div class="column is-10 is-offset-1">
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
<div class="modal" id="save_as_template_modal">
        <div class="modal-background" style="background-color: rgba(10, 10, 10, 0.4)"></div>
        <div class="modal-card" style="width:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">Save as Template...</p>
            </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">

                        <div class="column">
                            {{ EGForm::select('save_as_template_id', [
                                'label' => 'Would you like to make a new revision of an existing template?',
                                'value' => "",
                                'list' => isset($saveAsTemplates) ? $saveAsTemplates : [],
                                'type' => 'edit',
                            ]) }}
                        </div>
                    </div>
                </section>
            <footer class="modal-card-foot">
                <button class="button is-success" id="save_new_template_button">Save</button>
                <button class="button" id="close_modal">Cancel</button>
            </footer>
        </div>
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
        $('.selectize-multiple').selectize({
            @if ($pageType == 'edit')
                onInitialize: function() {
                    $(this.$control).css('height', '38px');
                    $(this.$control).css('z-index', 'initial');
                },
            @endif
            onFocus: function() {
                $(this.$control).css('height', 'initial');
                $(this.$control).css('z-index', '999');
            },
            onBlur: function() {
                $(this.$control).css('height', '38px');
                $(this.$control).css('z-index', 'initial');
            },
        });
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

        $('#save_as_template_pill').on('click', function() {
            $('#save_as_template_modal').addClass('is-active');
        })

        $('#save_new_template_button').on('click', function() {
            $.ajax({
                url: '{{$record->id}}/save_as_template',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    vtram : "{{$record->id}}",
                    template_id: $('#save_as_template_id').val()
                },
                success: function(url) {
                    window.location.href = url;
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });

        })
    </script>
@endpush

@include('modules.company.project.vtram.create-modal')
@if ($pageType == 'view' && strpos($identifierPath, 'template') !== false)
    @include('modules.company.template.create-vtrams-modal')
@endif

@push('styles')
    <style>
        .selectize-control {
            min-width:100% !important;
        }
        .selectize-input {
            max-height: 100px;
            overflow-y: scroll;
        }
        .select:not(.is-multiple)::after {
            right: 1.7em !important;
        }
    </style>
@endpush
