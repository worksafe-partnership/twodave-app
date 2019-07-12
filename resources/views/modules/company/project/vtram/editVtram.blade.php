<h2 class="sub-heading">Extra Information</h2>
<div class="columns">
    <div class="column is-12">
    </div>
</div>
<hr>
<h2 class="sub-heading">Hazards & Methodologies</h2>
<br>
<div class="columns">
    <div class="column is-6 box-container">
        <h2 class="sub-heading inline-block">Methodologies</h2>
        <a href="javascript:createMethodology()" class="button is-success is-pulled-right" title="Add Methodology">
            {{ icon('plus2') }}&nbsp;<span class="action-text is-hidden-touch">Add Methodology</span>
        </a>
        <hr>
        <div id="main-methodology-container">

        </div>
    </div>
    <div class="column is-6 box-container">
        <h2 class="sub-heading inline-block">Hazards</h2>
        <a href="javascript:createHazard()" class="button is-success is-pulled-right" title="Add Hazard">
            {{ icon('plus2') }}&nbsp;<span class="action-text is-hidden-touch">Add Hazard</span>
        </a>
        <hr>
        <div id="main-hazard-container">

        </div>
    </div>
</div>
<div class="view-templates">
    <div id="hazard-display-template">
        @include('modules.company.project.vtram.hazard.display')
        <div class="field is-grouped is-grouped-centered ">
            <p class="control">
                <button class="button is-primary submitbutton" onclick="submitHazardForm();">Save Hazard</button> 
            </p>
            <p class="control">
                <button class="button" onclick="cancelForm('hazard');">Cancel</button> 
            </p>
        </div>
    </div>
</div>
@push('styles')
    <style>
        .box-container {
            border: 2px solid #404040;
        }
        .inline-block {
            display: inline-block;
        }
        .view-templates {
            display: none;
        }
        .handms-icons {
            border: 1px solid #404040;
            padding: 3px;
        }
        .handms-icons svg {
            width: 1.5rem;
            height: 1.5rem;
        }
        .handms-actions {
            text-align: center;
            width: 140px;
            height: 30px;
        }
        .hazard-desc {
            max-width: 260px;
        }
    </style>
@endpush
@push('scripts')
        <script>
        // Both
        function cancelForm(type) {
            if (confirm("Any unsaved changes will be lost, are you sure?")) {
                if (type == 'hazard') {
                    listHazards();
                } else {
                    listMethodologies();
                }
            }
        }

        // Hazard Scripts
        var hazards = JSON.parse('{!! str_replace('\'', '\\\'', $hazards) !!}');
        listHazards();
        function createHazard() {
            $('#main-hazard-container').html($('#hazard-display-template').html());
        }

        function editHazard(id) {

        }

        function submitHazardForm() {
            var data = {
                _token: '{{ csrf_token() }}',
                description: $('#main-hazard-container #description').val(),
                control: $('#main-hazard-container #control').val(),
                risk: $('#main-hazard-container #risk').val(),
                risk_probability: $('#main-hazard-container #risk_probability').val(),
                risk_severity: $('#main-hazard-container #risk_severity').val(),
                r_risk: $('#main-hazard-container #r_risk').val(),
                r_risk_probability: $('#main-hazard-container #r_risk_probability').val(),
                r_risk_severity: $('#main-hazard-container #r_risk_severity').val(),
                list_order: hazards.length + 1,
                at_risk: $('#main-hazard-container #at_risk').val(),
                other_at_risk: $('#main-hazard-container #other_at_risk').val()
            };
            $.ajax({
                url: 'hazard/create',
                type: 'POST',
                data: data,
                success: function (result) {
                    // add to hazards list
                    hazards.push({
                        description: data.description,
                        control: data.control,
                        risk: data.risk,
                        risk_probability: data.risk_probability,
                        risk_severity: data.risk_severity,
                        r_risk: data.r_risk,
                        r_risk_probability: data.r_risk_probability,
                        r_risk_severity: data.r_risk_severity,
                        list_order: data.list_order,
                        at_risk: data.at_risk,
                        other_at_risk: data.other_at_risk
                    });
                    listHazards();
                },
                error: function (data) {
                    if (data.status == 422) {
                        var errors = '';
                        $.each(data.responseJSON.errors, function(key,val) {
                            toastr.error(val);
                        });
                    } else if (data.status == 401) {
                        toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                    } else {
                        toastr.error('An error has occured when saving the hazard');
                    }
                }
            });
        }

        function listHazards() {
            // Loop through hazards and build html
            var html = '<table>\
                            <tr>\
                                <th class="has-text-centered">No</th>\
                                <th class="hazard-desc">Hazard/Risk</th>\
                                <th class="has-text-centered">Initial<br>Risk</th>\
                                <th class="has-text-centered">Residual<br>Risk</th>\
                                <th></th>\
                            </tr>';
            for (let i = 0; i < hazards.length; i++) {
                html += '<tr>\
                            <td class="has-text-centered">' + hazards[i].list_order + '</td>\
                            <td class="hazard-desc">' + hazards[i].description + '</td>\
                            <td class="has-text-centered">' + hazards[i].risk + '</td>\
                            <td class="has-text-centered">' + hazards[i].r_risk + '</td>\
                            <td class="handms-actions">\
                                <a class="handms-icons" onclick="editHazard(' + hazards[i].id + ')">{{ icon('mode_edit') }}</a>\
                                <a class="handms-icons" onclick="deleteHazard(' + hazards[i].id + ')">{{ icon('delete') }}</a>\
                                <a class="handms-icons" onclick="moveHazardUp(' + hazards[i].id + ')">{{ icon('keyboard_arrow_up') }}</a>\
                                <a class="handms-icons" onclick="moveHazardDown(' + hazards[i].id + ')">{{ icon('keyboard_arrow_down') }}</a>\
                            </td>\
                        </tr>';
            }
            $('#main-hazard-container').html(html);                    
        }

        function deleteHazard(id) {

        }

        function moveHazardUp(id) {

        }

        function moveHazardDown(id) {

        }

        

        // Methodology Scripts
        var methodologies = JSON.parse('{!! $methodologies !!}');
        function createMethodology() {
            $('#main-methodology-container').html($('#methodology-display-template').html());
        }

        function editMethodology() {

        }

        function submitMethodologyForm() {
            $.ajax({
                url: '',
                type: 'POST',
                data: {

                },
                success: function (data) {
                    // Add to methodologies list then...
                    listMethodologies();
                },
                error: function (data) {
                    if (data.status == 422) {
                        var errors = '';
                        $.each(data.responseJSON.errors, function(key,val) {
                            toastr.error(val);
                        });
                    } else if (data.status == 401) {
                        toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                    } else {
                        toastr.error('An error has occured when saving the methodology');
                    }
                }
            });
        }

        function listMethodologies() {
            // Loop through methodologies and build html
            
        }

        function deleteMethodology() {

        }

        function moveMethodologyUp() {

        }

        function moveMethodologyDown() {

        }
    </script>
@endpush

This is example stuff below - to be removed when the files tasks are done
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
