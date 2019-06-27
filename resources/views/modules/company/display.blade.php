<div class="columns">
    <div class="column is-12">
		{{ EGForm::text('name', [
            'label' => 'Name',
            'value' => $record["name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('review_timescale', [
            'label' => 'Review Timescale',
            'value' => $record->review_timescale ?? 0,
            'type' => $pageType,
            'list' => config('egc.review_timescales')
        ]) }}

        {{ EGForm::text('vtrams_name', [
            'label' => 'VTRAMs Name',
            'value' => $record->vtrams_name ?? 'Task RAM',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('email', [
            'label' => 'Contact Email',
            'value' => $record["email"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('phone', [
            'label' => 'Phone Number',
            'value' => $record["phone"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('fax', [
            'label' => 'Fax Number',
            'value' => $record["fax"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('low_risk_character', [
            'label' => 'Low Risk Label',
            'value' => $record->low_risk_character ?? 'L',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('med_risk_character', [
            'label' => 'Medium Risk Label',
            'value' => $record->med_risk_character ?? 'M',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('high_risk_character', [
            'label' => 'High Risk Label',
            'value' => $record->high_risk_character ?? 'H',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('no_risk_character', [
            'label' => 'No Risk Label',
            'value' => $record->no_risk_character ?? '#',
            'type' => $pageType
        ]) }}

        {{ VTForm::colour('primary_colour', [
            'label' => 'Primary Colour',
            'value' => $record->primary_colour ?? '#000000',
            'type' => $pageType
        ]) }}

        {{ VTForm::colour('secondary_colour', [
            'label' => 'Secondary Colour',
            'value' => $record->secondary_colour ?? '#000000',
            'type' => $pageType
        ]) }}

        {{ EGForm::checkbox('light_text', [
            'label' => 'Light Text',
            'value' => $record->light_text ?? false,
            'type' => $pageType
        ]) }}

        {{ EGForm::text('accept_label', [
            'label' => 'Accept Label',
            'value' => $record->accept_label ?? 'Accept',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('amend_label', [
            'label' => 'Amend Label',
            'value' => $record->amend_label ?? 'Amend',
            'type' => $pageType
        ]) }}

        {{ EGForm::text('reject_label', [
            'label' => 'Reject Label',
            'value' => $record->reject_label ?? 'Reject',
            'type' => $pageType
        ]) }}

        {{ EGForm::file('logo', [
            'label' => 'Logo',
            'value' => $record["logo"],
            'type' => $pageType
        ]) }}


	</div>
</div>
