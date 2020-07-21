<div class="columns">
    <div class="column is-10 is-offset-1">
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
                    {{ EGForm::text('ref', [
                        'label' => 'Reference',
                        'value' => $record["ref"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('client_name', [
                        'label' => 'Client Name',
                        'value' => $record["client_name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::select('project_admin', [
                        'label' => 'Project Admin',
                        'value' => $record["project_admin"],
                        'type' => $isPrincipalContractor || $pageType == 'create' ? $pageType : 'view',
                        'list' => $isPrincipalContractor || $pageType == 'create' ? $projectAdmins : $projectAdmins->toArray() + [$record['project_admin'] => $record->admin->name ?? ''],
                        'display_value' => $record->admin->name ?? 'No Admin Selected',
                        'selector' => 1
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::select('review_timescale', [
                        'label' => 'Review Timescale (Overrides '.$company->reviewTimeScaleName().' from Company)',
                        'value' => $record->review_timescale ?? 0,
                        'type' => $pageType,
                        'list' => $timescales
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::checkbox('show_contact', [
                        'label' => 'Show Contact Information on '.$company->vtrams_name,
                        'value' => $record->show_contact ?? false,
                        'type' => $pageType
                    ]) }}
                    <p>This will show:
                        <ul>
                            <li>Email Address, Phone and Fax numbers for the Company</li>
                            <li>Email Address of the Project Admin</li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Principal Contractor</h2>
        <div class="columns">
            <div class="column is-4">
                <div class="field pc-check">
                    {{ EGForm::checkbox('principle_contractor', [
                        'label' => 'Principal Contractor',
                        'value' => $record->principle_contractor ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field principle-contractor-details">
                    {{ EGForm::text('principle_contractor_name', [
                        'label' => 'Principal Contractor Name',
                        'value' => $record["principle_contractor_name"],
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field principle-contractor-details">
                    {{ EGForm::text('principle_contractor_email', [
                        'label' => 'Principal Contractor Email',
                        'value' => $record["principle_contractor_email"],
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
        <div class="columns">
            @if(isset($isPrincipalContractor) && $isPrincipalContractor)
                <div class="column">
                    <div class="field">
                        @if ($pageType == 'view')
                            {{ EGForm::multicheckbox('contractors', [
                                'label' => 'Contractors',
                                'values' => $selectedContractors,
                                'list' => $otherCompanies,
                                'type' => $pageType,
                                'list-style' => 'multi-block',
                            ]) }}
                        @else
                            @php
                                $old = old('contractors');

                                if (count($old) > 0) {
                                    foreach ($old as $val) {
                                        $selectedContractors[$val] = true;
                                    }
                                }
                            @endphp
                            {{ VTForm::multiSelect('contractors[]', [
                                'label' => 'Contractors',
                                'value' => $selectedContractors,
                                'list' => $otherCompanies,
                                'type' => $pageType,
                            ]) }}
                        @endif
                    </div>
                </div>
            @endif
            @if($isPrincipalContractor || $isContractor)
                <div class="column">
                    <div class="field">
                        @if ($pageType == 'view')
                            {{ EGForm::multicheckbox('subcontractors', [
                                'label' => 'Subcontractors',
                                'values' => $selectedSubs,
                                'list' => $otherCompanies,
                                'type' => $pageType,
                                'list-style' => 'multi-block',
                            ]) }}
                        @else
                            @php
                                $old = old('subcontractors');

                                if (count($old) > 0) {
                                    foreach ($old as $val) {
                                        $selectedSubs[$val] = true;
                                    }
                                }
                            @endphp
                            {{ VTForm::multiSelect('subcontractors[]', [
                                'label' => 'Subcontractors',
                                'value' => $selectedSubs,
                                'list' => $otherCompanies,
                                'type' => $pageType,
                            ]) }}
                        @endif
                    </div>
                </div>
            @endif
            <div class="column">
                <div class="field">
                    @if ($pageType == 'view')
                        {{ EGForm::multicheckbox('', [
                            'label' => 'Associated Users',
                            'values' => $selectedUsers,
                            'list' => $allUsers,
                            'type' => $pageType,
                            'list-style' => 'multi-block',
                        ]) }}
                    @else
                        @php
                            $old = old('users');

                            if (count($old) > 0) {
                                foreach ($old as $val) {
                                    $selectedUsers[$val] = true;
                                }
                            }
                        @endphp
                        {{ VTForm::multiSelect('users[]', [
                            'label' => 'Associated Users',
                            'value' => $selectedUsers,
                            'list' => $allUsers,
                            'type' => $pageType,
                        ]) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if($pageType != 'view')
    <hr>
    <div class="columns">
        <div class="column is-10 is-offset-1">
            <div class="columns">
                @if(isset($isPrincipalContractor) && $isPrincipalContractor)
                    <div class="column">
                        <h2 class="sub-heading">Add a Contractor</h2>
                        <div class="field">
                            {{ EGForm::checkbox('add_contractor', [
                                'label' => 'Add a Contractor?',
                                'value' => false,
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('company_name_con', [
                                'label' => 'Company Name',
                                'value' => '',
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('short_name_con', [
                                'label' => 'Short Name',
                                'value' => '',
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('company_admin_email_con', [
                                'label' => "Admin User's Email",
                                'value' => "",
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('company_admin_name_con', [
                                'label' => "Admin User's Name",
                                'value' => "",
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('contact_name_con', [
                                'label' => 'Contact Name',
                                'value' => '',
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('email_con', [
                                'label' => 'Contact Email',
                                'value' => '',
                                'type' => $pageType
                            ]) }}
                        </div>
                        <div class="field">
                            {{ EGForm::text('phone_con', [
                                'label' => 'Phone Number',
                                'value' => '',
                                'type' => $pageType
                            ]) }}
                        </div>
                    </div>
                @endif
                @if($isPrincipalContractor || $isContractor)
                <div class="column">
                    <h2 class="sub-heading">Add a new Subcontractor</h2>
                    <div class="field">
                        {{ EGForm::checkbox('add_subcontractor', [
                            'label' => 'Add a Subcontractor?',
                            'value' => false,
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('company_name', [
                            'label' => 'Company Name',
                            'value' => '',
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('short_name', [
                            'label' => 'Short Name',
                            'value' => '',
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('company_admin_email', [
                            'label' => "Admin User's Email",
                            'value' => "",
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('company_admin_name', [
                            'label' => "Admin User's Name",
                            'value' => "",
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('contact_name', [
                            'label' => 'Contact Name',
                            'value' => '',
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('email', [
                            'label' => 'Contact Email',
                            'value' => '',
                            'type' => $pageType
                        ]) }}
                    </div>
                    <div class="field">
                        {{ EGForm::text('phone', [
                            'label' => 'Phone Number',
                            'value' => '',
                            'type' => $pageType
                        ]) }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <hr>
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
    @php
        $oldPC = old('principle_contractor');
    @endphp
    @if (($oldPC != "1" && $pageType == 'create') || (isset($record) && $record->principle_contractor != "1" && ($pageType == 'edit' || $pageType == 'view') && $oldPC != "1"))
        @push('styles')
            <style>
               .principle-contractor-details {
                    display: none;
                }
            </style>
        @endpush
    @endif
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
        $(document).on('DOMSubtreeModified', '.selectize-input', function () {
            this.scrollTop = this.scrollHeight;
        });
    </script>
    <script>
        $('.pc-check [id^=principle_contractor]').click(function() {
            var name = $(this).prev().val();
            if (name == "1") {
                $('.principle-contractor-details').hide();
                $('#principle_contractor_name').val('');
                $('#principle_contractor_email').val('');
            } else {
                $('.principle-contractor-details').show();
            }
        });
    </script>
@endpush
@include('modules.company.project.vtram.create-modal')
