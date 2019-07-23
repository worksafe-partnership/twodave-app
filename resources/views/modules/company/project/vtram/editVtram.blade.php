<h2 class="sub-heading">Extra Information</h2>
<div class="columns">
    <div class="column is-3">
        <a download="Noise Vibration Assessment.xls" href="/Noise_Vibration_Assessment.xls" class="button">Download Noise Vibration Assessment</a>
    </div>
    <div class="column is-2">
        <div class="field">
            {{ EGForm::file('coshh_assessment', [
                'label' => 'COSHH Assessment Document',
                'value' => $record["coshh_assessment"],
                'type' => $pageType
            ]) }}
        </div>
    </div>
    <div class="column is-2">
        <div class="field">
            {{ EGForm::file('havs_noise_assessment', [
                'label' => 'HAVS/Noise Assessment Document',
                'value' => $record["havs_noise_assessment"],
                'type' => $pageType
            ]) }}
        </div>
    </div>
    <div class="column is-4">
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
    <div class="column">
        <div class="field">
            {{ EGForm::ckeditor('key_points', [
                'label' => 'Key Points',
                'value' => $record["key_points"],
                'type' => 'edit'
            ]) }}
        </div>
    </div>
    <div class="column is-2" id="comments-button-div">
        <button class="button is-success is-primary" id="comments-button" style="float:right">Show/Hide Comments</button>
    </div>
</div>
<div class="columns">
    <div class="column">
        <p class="control">
            <button class="button is-primary submitbutton">Save</button>
            <button class="button is-primary submitbutton" name="approvalButton">Save and Submit for Approval</button>
        </p>
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

<div id="comments-sidebar" class="column hidden">
    <p class="sub-heading">Comments
        <button class="button is-success is-small" style="float:right" id="close-comments">Close</button>
    </p>

    @if(isset($comments) && $comments->isNotEmpty())
        @foreach($comments as $comment)
            <p>{{$comment->comment}}</i></p>
            <p><i>{{$comment->completedByName()}} {{$comment->created_at->format('d/m/Y')}}</i></p>
            <hr>
        @endforeach
    @else
        <p>No Comments</p>
    @endif
</div>

@push('styles')
    <style>
        #comments-sidebar {
            border: 1px solid #404040;
            overflow-y: scroll;
            width: 400px;
            position: absolute;
            top: 0;
            right: 0;
            background-color: white;
            height: 100%;
        }

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

        $('#comments-button').on('click', function() {
            $('#comments-sidebar').removeClass('hidden');
            $('#comments-button-div').addClass('hidden');
        })

        $('#close-comments').on('click', function() {
          $('#comments-sidebar').addClass('hidden');
          $('#comments-button-div').removeClass('hidden');
        });

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
            let hazard = '';
            for (let i = 0; i < hazards.length; i++) {
                if (hazards[i]['id'] == id) {
                    hazard = hazards[i];
                    break;
                }
            }
            $('#main-hazard-container').html($('#hazard-display-template').html());
            $('#main-hazard-container [name="description"]').val(hazard['description']);
            $('#main-hazard-container [name="at_risk"]').val(hazard['at_risk']);
            $('#main-hazard-container [name="other_at_risk"]').val(hazard['other_at_risk']);
            $('#main-hazard-container [name="risk_severity"]').val(hazard['risk_severity']);
            $('#main-hazard-container [name="risk_probability"]').val(hazard['risk_probability']);
            $('#main-hazard-container [name="control"]').val(hazard['control']);
            $('#main-hazard-container [name="r_risk_severity"]').val(hazard['r_risk_severity']);
            $('#main-hazard-container [name="r_risk_probability"]').val(hazard['r_risk_probability']);

            $("#main-hazard-container .submitbutton").attr("onclick","submitHazardForm("+id+","+hazard['list_order']+")");
        }

        function submitHazardForm(editId=null, listOrder=null) {

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

            let url = 'hazard/create';
            if (editId) {
                url = 'hazard/'+editId+'/edit';
                data['list_order'] = listOrder;
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function (id) {
                    if (!editId) {
                        // add to hazards list
                        hazards.push({
                            id: id,
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
                    } else {
                        for (let i = 0; i < hazards.length; i++) {
                            if (hazards[i]['id'] == editId) {
                                hazards[i]['description'] = data.description,
                                hazards[i]['control'] = data.control,
                                hazards[i]['risk'] = data.risk,
                                hazards[i]['risk_probability'] = data.risk_probability,
                                hazards[i]['risk_severity'] = data.risk_severity,
                                hazards[i]['r_risk'] = data.r_risk,
                                hazards[i]['r_risk_probability'] = data.r_risk_probability,
                                hazards[i]['r_risk_severity'] = data.r_risk_severity,
                                hazards[i]['list_order'] = data.list_order,
                                hazards[i]['at_risk'] = data.at_risk,
                                hazards[i]['other_at_risk'] = data.other_at_risk
                                break;
                            }
                        }
                    }
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
                html += '<tr id="hazard-'+hazards[i].id+'">\
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
            if (confirm("Are you sure you want delete this hazard?")) {
                var data = {
                    _token: '{{ csrf_token() }}',
                    hazard_id: id,
                };
                $.ajax({
                    url: '/hazard/'+id+'/delete_hazard',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response != "disallow") {
                            hazards = response;
                            listHazards();
                            toastr.success('Hazard was deleted');
                        } else {
                            toastr.error('An error has occured when deleting the hazard');
                        }
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
                            toastr.error('An error has occured when deleting the hazard');
                        }
                    }
                });
            }
        }

        function moveHazardUp(id) {
            moveHazard(id, "move_up")
        }

        function moveHazardDown(id) {
            moveHazard(id, "move_down")
        }

        function moveHazard(id, slug) {
            var data = {
                _token: '{{ csrf_token() }}',
                hazard_id: id,
            };
            $.ajax({
                url: '/hazard/'+id+'/'+slug,
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response != "disallow") {
                        hazards = response;
                        listHazards();
                        toastr.success('Hazard was deleted');
                    } else {
                        toastr.error('An error has occured when moving the hazard');
                    }
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
                        toastr.error('An error has occured when moving the hazard');
                    }
                }
            });
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
