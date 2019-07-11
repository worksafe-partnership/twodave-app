Editing hazards and methodologies will go here

        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{-- EGForm::file('havs_noise_assessment', [
                        'label' => 'HAVS/Noise Assessment Document',
                        'value' => $record["havs_noise_assessment"],
                        'type' => $pageType
                    ]) --}}
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{-- EGForm::file('coshh_assessment', [
                        'label' => 'COSHH Assessment Document',
                        'value' => $record["coshh_assessment"],
                        'type' => $pageType
                    ]) --}}
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    <a download="HAVS Calculator.xls" href="/havs.xls" class="button">Download HAVS Calculator</a>
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    <a download="Noise Calculator.xlsx" href="/noise.xlsx" class="button">Download Noise Calculator</a>
                </div>
            </div>
        </div>

        <div class="field">
            {{-- EGForm::checkbox('dynamic_risk', [
                'label' => 'Dynamic Risk (Adds Dynamic Risk boxes to the Risk Assessment)',
                'value' => $record->dynamic_risk ?? false,
                'type' => $pageType
            ]) --}}
        </div>
