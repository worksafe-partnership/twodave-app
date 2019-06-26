<div class="columns">
    <div class="column is-12">
		{{ EGForm::ckEditor('description', [
            'label' => 'Description',
            'value' => $record["description"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('entity', [
            'label' => 'entity',
            'value' => $record["entity"],
            'type' => $pageType
        ]) }}

        {{ EGForm::number('entity_id', [
            'label' => 'entity_id',
            'value' => $record["entity_id"],
            'type' => $pageType
        ]) }}

        {{ EGForm::ckeditor('control', [
            'label' => 'control',
            'value' => $record["control"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('risk', [
            'label' => 'Risk',
            'value' => $record["risk"],
            'type' => $pageType,
            'list' => $riskList,
            'display_value' => $riskList[$record->risk] ?? '',
            'selector' => 1
        ]) }}

        {{ EGForm::select('r_risk', [
            'label' => 'Reduced Risk',
            'value' => $record["r_risk"],
            'type' => $pageType,
            'list' => $riskList,
            'display_value' => $riskList[$record->r_risk] ?? '',
            'selector' => 1
        ]) }}

        {{ EGForm::number('list_order', [
            'label' => 'list_order',
            'value' => $record["list_order"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('at_risk', [
            'label' => 'Who is at Risk',
            'value' => $record["at_risk"],
            'type' => $pageType,
            'list' => $whoList,
            'display_value' => $whoList[$record->at_risk'] ?? '',
            'selector' => 1
        ]) }}

        {{ EGForm::text('other_at_risk', [
            'label' => 'Please Specify',
            'value' => $record["other_at_risk"],
            'type' => $pageType
        ]) }}


	</div>
</div>