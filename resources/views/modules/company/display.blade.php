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
                    {{ EGForm::select('review_timescale', [
                        'label' => 'Review Timescale',
                        'value' => $record->review_timescale ?? 0,
                        'type' => $pageType,
                        'list' => config('egc.review_timescales')
                    ]) }}
                </div>
            </div>
            <div class="column is-4">
                <div class="field">
                    {{ EGForm::text('vtrams_name', [
                        'label' => 'VTRAMs Name',
                        'value' => $record->vtrams_name ?? 'Task RAM',
                        'type' => $pageType
                    ]) }}
                </div>
            </div>
        </div>

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
                        'value' => $record->primary_colour ?? '#000000',
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
