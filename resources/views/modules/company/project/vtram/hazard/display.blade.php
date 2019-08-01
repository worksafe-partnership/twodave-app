<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Risk Key</h2>
        <div class="field">
            @include('modules.company.project.vtram.hazard.risk-key')
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Details</h2>
		<div class="field">
            {{ EGForm::ckEditor('description', [
                'label' => 'Hazard/Risk',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::select('at_risk', [
                        'label' => 'Who is at Risk',
                        'value' => $record["at_risk"],
                        'type' => $pageType,
                        'list' => $whoList,
                        'display_value' => $whoList[$record['at_risk']] ?? '',
                        'selector' => 1
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::text('other_at_risk', [
                        'label' => 'Please Specify',
                        'value' => $record["other_at_risk"],
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
        <h2 class="sub-heading">Risk</h2>
        <div class="columns">
            <div class="column is-12">
                <div class="field">
                    @include('modules.company.project.vtram.hazard.risk-chart', ['hazardType' => 'risk'])
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::number('risk_severity', [
                        'label' => 'Risk Severity',
                        'type' => 'view',
                        'value' => $record['risk_severity'],
                        'attributes' => [
                            'step' => 1,
                            'max' => 4,
                            'min' => 1,
                        ]
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::number('risk_probability', [
                        'label' => 'Risk Probability',
                        'type' => 'view',
                        'value' => $record['risk_probability'],
                        'attributes' => [
                            'step' => 1,
                            'max' => 4,
                            'min' => 1,
                        ]
                    ]) }}
                    {{ EGForm::hidden('risk', [
                        'type' => 'create',
                        'value' => $record["risk"],
                    ]) }}
                </div>
            </div>
        </div>

        <div class="field">
            {{ EGForm::ckeditor('control', [
                'label' => 'Control',
                'value' => $record["control"],
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
<hr>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <h2 class="sub-heading">Residual Risk</h2>
        <div class="columns">
            <div class="column is-12">
                <div class="field">
                    @include('modules.company.project.vtram.hazard.risk-chart', ['hazardType' => 'r_risk'])
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::number('r_risk_severity', [
                        'label' => 'Residual Risk Severity',
                        'type' => 'view',
                        'value' => $record['r_risk_severity'],
                        'attributes' => [
                            'step' => 1,
                            'max' => 4,
                            'min' => 1,
                        ]
                    ]) }}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::number('r_risk_probability', [
                        'label' => 'Residual Risk Probability',
                        'type' => 'view',
                        'value' => $record['r_risk_probability'],
                        'attributes' => [
                            'step' => 1,
                            'max' => 4,
                            'min' => 1,
                        ]
                    ]) }}
                    {{ EGForm::hidden('r_risk', [
                        'type' => 'create',
                        'value' => $record["r_risk"],
                    ]) }}
                </div>
            </div>
        </div>
	</div>
</div>
<!-- @include('modules.company.project.vtram.hazard.related-methodologies') -->
<hr>

@push('styles')
    <style>
        .green {
            background-color: #48ef31;
        }
        .yellow {
            background-color: #ffff66;
        }
        .orange {
            background-color: #e09706;
        }
        .red {
            background-color: #ff0000;
        }
        .center {
            text-align: center;
        }
        .padding-21 {
            padding-top: 21px;
        }
        .risk-table th, .risk-table td, .risk-key th, .risk-key td {
            border: 1px solid black;
        }
        .risk-rating {
            cursor: pointer;
        }
        .small-pad {
            padding-left: 5px;
            padding-right: 5px;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).on('click', '.risk-rating', function () {
            var type = $(this).data('type');

            $('#' + type + '_probability').val($(this).data('prob'));
            $('#' + type + '_severity').val($(this).data('severity'));
            $('#' + type).val($(this).data('value'));
        });
    </script>
@endpush
