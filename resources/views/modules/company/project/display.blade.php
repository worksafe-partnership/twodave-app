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
                        'type' => $pageType,
                        'list' => $projectAdmins,
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
                        'label' => 'Show Contact Information on VTRAMS',
                        'value' => $record->show_contact ?? false,
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
    <div class="column is-8 is-offset-2">
        <h2 class="sub-heading">Users</h2>
        <div class="columns">
            <div class="column is-12">
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
    @if (($oldPC != "1" && $pageType == 'create') || (isset($record) && $record->principle_contractor != "1"))
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
