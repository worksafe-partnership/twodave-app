{{ EGForm::hidden("id", [
    "value" => $record['id'],
    "type" => $pageType
])}}
<div class="columns">
    <div class="column is-8 is-offset-2">
        <div class="columns">
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::email("email", [
                        "label" => "Email",
                        "value"  => $record['email'],
                        "type" => $pageType
                    ]) }}
                </div>
            </div>

            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text("name", [
                        "label" => "Name",
                        "value"  => $record['name'],
                        "type" => $pageType
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text("position", [
                        "label" => "Position",
                        "value"  => $record['position'],
                        "type" => $pageType
                    ]) }}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::password("password", [
                        "label" => "Password",
                        "attributes" =>[
                            "placeholder" => "Password Set",
                            "autocomplete" => "new-password",
                        ],
                        "type" => $pageType
                    ]) }}
                </div>
            </div>
            @if($pageType != 'view')
                <div class="column is-6">
                    <div class="field">
                    {{ EGForm::password("password_confirmation", [
                        "label" => "Verify Password",
                        "attributes" =>[
                            "placeholder" => "Password Set"
                        ],
                        "type" => $pageType
                    ]) }}
                    </div>
                </div>
            @endif
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file("signature", [
                        "label" => "Signature Image",
                        "value"  => $record['signature'],
                        "type" => $pageType,
                        "show_image" => 1,
                    ]) }}
                </div>
            </div>
        </div>
        @if (isset($userPage) && $userPage)
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column is-8 is-offset-2">
                <h2 class="sub-heading">Roles</h2>
                <div class="columns">
                    @if (strpos($identifierPath, "company") === false && Auth::user()->company_id == null)
                        <div class="column is-6">
                            <div class="field company-field">
                                {{ EGForm::autocomplete('company_id', [
                                    "label" => "Company",
                                    "type" => $pageType,
                                    "list" => $companies,
                                    "value" => $record['company_id'] ?? '',
                                    "selector" => true,
                                    "display_value" => $record->company->name ?? '',
                                ]) }}
                            </div>
                        </div>
                    @endif
                    <div class="column is-6">
                        <div class="field roles">
                            {{ EGForm::multiCheckbox("roles", [
                                "type"  => $pageType,
                                "label" => "Roles",
                                "list"  => $roles,
                                "values"  => $currentRoles,
                                "classes" => ['roles'],
                                "list-style" => "multi-block"
                            ]) }}
                        </div>
                    </div>
                </div>
        @endif
    </div>
</div>
@if (isset($userPage) && $userPage)
    @php
        $oldRoles = old('roles');
    @endphp
        @if ((isset($currentRoles[1]) && ($oldRoles == null && $oldRoles[1] == null))
            || (isset($currentRoles[2]) && ($oldRoles == null && $oldRoles[2] == null)))
        @push('styles')
            <style>
               .company-field {
                    display: none;
                }
            </style>
        @endpush
    @endif
    @push('scripts')
        <script>
            $('.roles [id^=role]').click(function() {
                $('.roles input[type=checkbox]').prop('checked', false);
                $('.roles input[type=hidden]').val(0);
                $(this).prop('checked', true);
                $(this).prev().val(1);
                var name = $(this).prev()[0].name;
                if (name == 'roles[1]' || name == 'roles[2]') {
                    $('.company-field').hide();
                } else {
                    $('.company-field').show();
                }
            });
        </script>
    @endpush
@endif
