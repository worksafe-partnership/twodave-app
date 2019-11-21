<div>
    @include('modules.company.project.vtram.ckeditor-key')
    @include('modules.company.project.vtram.comments')
</div>
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
<div class="extra-form-container">
    <h2 class="sub-heading">Extra Information</h2>
    <form method="POST" action="edit_extra" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <a download="Noise Vibration Assessment.xls" href="/Noise_Vibration_Assessment.xls" class="button">Download HAVS/Noise Assessment</a>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::checkbox('dynamic_risk', [
                        'label' => 'Dynamic Risk (Adds Dynamic Risk boxes to the Risk Assessment)',
                        'value' => $record['dynamic_risk'] ?? false,
                        'type' => $pageType
                    ]) }}
                </div>
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
        <div class="columns">
            <div class="column">
                <p class="control">
                    <button class="button is-primary submitbutton">Save</button>
                    <button class="button is-primary submitbutton" name="send_for_approval" value="1">Save and Submit for Approval</button>
                </p>
            </div>
        </div>
    </form>
</div>
@include('modules.company.project.vtram.script_style_for_both')
@include("egl::partials.ckeditor")
