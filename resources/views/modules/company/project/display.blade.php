<div class="columns">
    <div class="column is-12">
		{{ EGForm::text('name', [
            'label' => 'Name',
            'value' => $record["name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('ref', [
            'label' => 'Reference',
            'value' => $record["ref"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('company_id', [
            'label' => 'Company',
            'value' => $record["company_id"],
            'type' => $pageType,
            'list' => $companies,
            'display_value' => $record->company->name ?? 'No Company Selected'
        ]) }}

        {{ EGForm::select('project_admin', [
            'label' => 'Project Admin',
            'value' => $record["project_admin"],
            'type' => $pageType,
            'list' => $projectAdmins,
            'display_value' => $record->admin->name ?? 'No Admin Selected'
        ]) }}

        {{ EGForm::checkbox('principle_contractor', [
            'label' => 'Principle Contractor',
            'value' => $record->principle_contractor ?? false,
            'type' => $pageType
        ]) }}

        {{ EGForm::text('principle_contractor_name', [
            'label' => 'Principle Contractor Name',
            'value' => $record["principle_contractor_name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('principle_contractor_email', [
            'label' => 'Principle Contractor Email',
            'value' => $record["principle_contractor_email"],
            'type' => $pageType
        ]) }}

        {{ EGForm::text('client_name', [
            'label' => 'Client Name',
            'value' => $record["client_name"],
            'type' => $pageType
        ]) }}

        {{ EGForm::select('review_timescale', [
            'label' => 'Review Timescale (Overrides Company)',
            'value' => $record->review_timescale ?? 0,
            'type' => $pageType,
            'list' => config('egc.review_timescales')
        ]) }}

        {{ EGForm::checkbox('show_contact', [
            'label' => 'Show Contact Information on VTRAMs',
            'value' => $record->show_contact ?? false,
            'type' => $pageType
        ]) }}


	</div>
</div>