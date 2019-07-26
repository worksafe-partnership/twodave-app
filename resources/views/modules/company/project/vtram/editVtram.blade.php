<h2 class="sub-heading">Extra Information</h2>
<form method="POST" action="edit_extra" enctype="multipart/form-data">
    {{ csrf_field() }}
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
            <button class="button is-success is-primary" id="comments-button" type="button" style="float:right">Show/Hide Comments</button>
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
<hr>
<h2 class="sub-heading">Hazards & Methodologies</h2>
<br>
<div class="columns">
    <div class="column is-6 box-container">
        <div class="columns">
            <div class="column is-12">
                <h2 class="sub-heading inline-block">Methodologies</h2>
                <div class="is-pulled-right">
                    <div class="meth-type-selector">
                        {{ EGForm::select('meth_type', [
                            'label' => 'Select Type',
                            'list' => $methTypeList,
                            'value' => '',
                            'type' => 'create',
                            'selector' => true,
                        ]) }}
                    </div>
                    <a href="javascript:createMethodology()" class="button is-success is-pulled-right" title="Add Methodology">
                        {{ icon('plus2') }}&nbsp;<span class="action-text is-hidden-touch">Add Methodology</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12 nopad">
                <div id="main-methodology-container">
                    <div id="methodology-list-container">
                        <table class="methodology-list-table">
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th></th>
                            </tr>
                            @foreach ($methodologies as $meth)
                                <tr id="methodology-{{ $meth->id }}">
                                    <td class="has-text-centered methodology-order">{{ $meth->list_order }}</td>
                                    <td class="methodology-title">{{ $meth->title }}</td>
                                    <td class="methodology-category">{{ $methTypeList[$meth->category] }}</td>
                                    <td class="handms-actions">
                                        <a class="handms-icons" onclick="editMethodology({{ $meth->id }})">{{ icon('mode_edit') }}</a>
                                        <a class="handms-icons" onclick="deleteMethodology({{ $meth->id }})">{{ icon('delete') }}</a>
                                        <a class="handms-icons" onclick="moveMethodologyUp({{ $meth->id }})">{{ icon('keyboard_arrow_up') }}</a>
                                        <a class="handms-icons" onclick="moveMethodologyDown({{ $meth->id }})">{{ icon('keyboard_arrow_down') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div id="methodology-text-form-container">
                        @include('modules.company.project.methodology.text')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                    <div id="methodology-icon-form-container">
                        @include('modules.company.project.methodology.icon')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('ICON');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                    <div id="methodology-complex-table-form-container">
                        @include('modules.company.project.methodology.complex_table')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('COMPLEX_TABLE');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                    <div id="methodology-process-form-container">
                        @include('modules.company.project.methodology.process')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('PROCESS');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                    <div id="methodology-simple-table-form-container">
                        @include('modules.company.project.methodology.simple_table')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('SIMPLE_TABLE');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                    <div id="methodology-text-image-form-container">
                        @include('modules.company.project.methodology.text_image')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT_IMAGE');">Save Methodology</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('methodology');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-6 box-container">
        <div class="columns">
            <div class="column is-12">
                <h2 class="sub-heading inline-block">Hazards</h2>
                <a href="javascript:createHazard()" class="button is-success is-pulled-right" title="Add Hazard">
                    {{ icon('plus2') }}&nbsp;<span class="action-text is-hidden-touch">Add Hazard</span>
                </a>
            </div>
        </div>
        <div class="columns">
            <div class="column is-12 nopad">
                <div id="main-hazard-container">
                    <div id="hazard-list-container">
                        <table class="hazard-list-table">
                            <tr>
                                <th class="has-text-centered">No</th>
                                <th class="hazard-desc">Hazard/Risk</th>
                                <th class="has-text-centered">Initial<br>Risk</th>
                                <th class="has-text-centered">Residual<br>Risk</th>
                                <th></th>
                            </tr>
                            @foreach ($hazards as $hazard)
                                <tr id="hazard-{{ $hazard->id }}">
                                    <td class="has-text-centered hazard-order">{{ $hazard->list_order }}</td>
                                    <td class="hazard-desc">{{ $hazard->description }}</td>
                                    <td class="has-text-centered hazard-risk">{{ $riskList[$hazard->risk] ?? '' }}</td>
                                    <td class="has-text-centered hazard-r-risk">{{ $riskList[$hazard->r_risk] ?? '' }}</td>
                                    <td class="handms-actions">
                                        <a class="handms-icons" onclick="editHazard({{ $hazard->id }})">{{ icon('mode_edit') }}</a>
                                        <a class="handms-icons" onclick="deleteHazard({{ $hazard->id }})">{{ icon('delete') }}</a>
                                        <a class="handms-icons" onclick="moveHazardUp({{ $hazard->id }})">{{ icon('keyboard_arrow_up') }}</a>
                                        <a class="handms-icons" onclick="moveHazardDown({{ $hazard->id }})">{{ icon('keyboard_arrow_down') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div id="hazard-form-container">
                        @include('modules.company.project.vtram.hazard.display')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitHazardForm();">Save Hazard</button>
                            </p>
                            <p class="control">
                                <button class="button" onclick="cancelForm('hazard');">Cancel</button>
                            </p>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
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
        .meth-type-selector {
            display: inline-block;
            margin-top: -21px;
            margin-right: 5px;
        }
        #comments-sidebar {
            border: 1px solid #404040;
            overflow-y: scroll;
            min-width: 300px;
            max-width: 400px;
            position: fixed;
            top: 0;
            right: 0;
            background-color: white;
            height: 100%;
            z-index: 999;
        }

        .box-container {
        }
        .inline-block {
            display: inline-block;
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
        #main-hazard-container, #main-methodology-container {
            position: relative;
        }
        #hazard-form-container, #methodology-form-container, #methodology-icon-form-container,
        #methodology-complex-table-form-container, #methodology-simple-table-form-container,
        #methodology-process-form-container, #methodology-text-form-container, #methodology-text-image-form-container {
            position: absolute;
            top: 0;
            border: 2px solid #404040;
            border-top: none;
            width: 100%;
            display: none;
            z-index: 999;
            background-color: #FFF;
        }
        #hazard-list-container, #methodology-list-container {
            position: absolute;
            top: 0;
            border: 2px solid #404040;
            border-top: none;
            width: 100%;
            background-color: #FFF;
        }
        .nopad {
            padding: 0px;
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
                    $('#hazard-form-container').css('display', 'none');
                } else {
                    $('#methodology-list-container').show();
                    $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                    $('#meth_type').val('');
                }
            }
        }

        // Hazard Scripts
        var hazards = JSON.parse('{!! str_replace('\'', '\\\'', $hazards->toJson()) !!}');
        var riskLabels = JSON.parse('{!! json_encode($riskList) !!}');
        function createHazard() {
            $("#hazard-form-container .submitbutton").attr("onclick","submitHazardForm()");
            $('#hazard-form-container').css('display', 'inherit');
        }

        function editHazard(id) {
            let hazard = '';
            for (let i = 0; i < hazards.length; i++) {
                if (hazards[i]['id'] == id) {
                    hazard = hazards[i];
                    break;
                }
            }
            $('#hazard-form-container [name="description"]').val(hazard['description']);
            $('#hazard-form-container [name="at_risk"]').val(hazard['at_risk']);
            $('#hazard-form-container [name="other_at_risk"]').val(hazard['other_at_risk']);
            $('#hazard-form-container [name="risk"]').val(hazard['risk']);
            $('#hazard-form-container [name="risk_severity"]').val(hazard['risk_severity']);
            $('#hazard-form-container [name="risk_probability"]').val(hazard['risk_probability']);
            $('#hazard-form-container [name="control"]').val(hazard['control']);
            $('#hazard-form-container [name="r_risk"]').val(hazard['r_risk']);
            $('#hazard-form-container [name="r_risk_severity"]').val(hazard['r_risk_severity']);
            $('#hazard-form-container [name="r_risk_probability"]').val(hazard['r_risk_probability']);

            $("#hazard-form-container .submitbutton").attr("onclick","submitHazardForm("+id+","+hazard['list_order']+")");
            $('#hazard-form-container').css('display', 'inherit');
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
                other_at_risk: $('#main-hazard-container #other_at_risk').val(),
                entityType: '{{ $entityType }}'
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
                        // Add hazards to table
                        $('.hazard-list-table').append('<tr id="hazard-' + id + '">\
                                <td class="has-text-centered hazard-order">' + data.list_order + '</td>\
                                <td class="hazard-desc">' + data.description + '</td>\
                                <td class="has-text-centered hazard-risk">' + riskLabels[data.risk] + '</td>\
                                <td class="has-text-centered hazard-r-risk">' + riskLabels[data.r_risk] + '</td>\
                                <td class="handms-actions">\
                                    <a class="handms-icons" onclick="editHazard(' + id + ')">{{ icon('mode_edit') }}</a>\
                                    <a class="handms-icons" onclick="deleteHazard(' + id + ')">{{ icon('delete') }}</a>\
                                    <a class="handms-icons" onclick="moveHazardUp(' + id + ')">{{ icon('keyboard_arrow_up') }}</a>\
                                    <a class="handms-icons" onclick="moveHazardDown(' + id + ')">{{ icon('keyboard_arrow_down') }}</a>\
                                </td>\
                            </tr>');
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

                                // need to edit hazard table
                                $('tr#hazard-' + editId + ' .hazard-order').html(data.list_order);
                                $('tr#hazard-' + editId + ' .hazard-desc').html(data.description);
                                $('tr#hazard-' + editId + ' .hazard-risk').html(riskLabels[data.risk]);
                                $('tr#hazard-' + editId + ' .hazard-r-risk').html(riskLabels[data.r_risk]);
                                break;
                            }
                        }
                    }
                    $('#hazard-form-container').css('display', 'none');
                    $('#hazard-form-container #description').val('');
                    $('#hazard-form-container #control').val('');
                    $('#hazard-form-container #risk').val('');
                    $('#hazard-form-container #risk_probability').val('');
                    $('#hazard-form-container #risk_severity').val('');
                    $('#hazard-form-container #r_risk').val('');
                    $('#hazard-form-container #r_risk_probability').val('');
                    $('#hazard-form-container #r_risk_severity').val('');
                    $('#hazard-form-container #at_risk').val('');
                    $('#hazard-form-container #other_at_risk').val('');
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
                            let listOrder = 0;
                            for (let i = 0; i < hazards.length; i++) {
                                if (hazards[i]['id'] == id) {
                                    // remove
                                    $('tr#hazard-' + id).remove();
                                    listOrder = hazards[i]['list_order'];
                                    delete hazards[i];
                                } else if (listOrder != 0 && hazards[i]['list_order'] > listOrder) {
                                    // decrement order
                                    let newOrder = hazards[i]['list_order'] - 1;
                                    $('tr#hazard-' + hazards[i]['id'] + ' .hazard-order').html(newOrder);
                                    hazards[i]['list_order'] = newOrder;
                                }
                            }
                            hazards = hazards.filter(function (item) {
                                return item !== undefined;
                            });
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
                        for (let i = 0; i < hazards.length; i++) {
                            if (slug == 'move_down' && hazards[i]['id'] == id) {
                                let firstOrder = hazards[i]['list_order'];
                                let lastOrder = hazards[i + 1]['list_order'];
                                $('tr#hazard-' + hazards[i + 1]['id'] + ' .hazard-order').html(firstOrder);
                                $('tr#hazard-' + hazards[i]['id'] + ' .hazard-order').html(lastOrder);
                                hazards[i + 1]['list_order'] = firstOrder;
                                hazards[i]['list_order'] = lastOrder;
                                $('tr#hazard-' + hazards[i + 1]['id']).after($('tr#hazard-' + hazards[i]['id']));
                            } else if (slug == 'move_up' && hazards[i]['id'] == id) {
                                let firstOrder = hazards[i]['list_order'];
                                let lastOrder = hazards[i - 1]['list_order'];
                                $('tr#hazard-' + hazards[i - 1]['id'] + ' .hazard-order').html(firstOrder);
                                $('tr#hazard-' + hazards[i]['id'] + ' .hazard-order').html(lastOrder);
                                hazards[i - 1]['list_order'] = firstOrder;
                                hazards[i]['list_order'] = lastOrder;
                                $('tr#hazard-' + hazards[i]['id']).after($('tr#hazard-' + hazards[i - 1]['id']));
                            }
                        }
                        hazards = bubbleSort(hazards, 'list_order');
                        toastr.success('Hazard was moved');
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

        function bubbleSort(arr, field){
            var len = arr.length;

            for (var i = 0; i < len ; i++) {
                for (var j = 0 ; j < len - i - 1; j++) {
                    if (arr[j][field] > arr[j + 1][field]) {
                        // swap
                        var temp = arr[j];
                        arr[j] = arr[j + 1];
                        arr[j + 1] = temp;
                    }
                }
            }
            return arr;
        }

        // Methodology Scripts
        var methodologies = JSON.parse('{!! str_replace('\'', '\\\'', $methodologies->toJson()) !!}');
        var company = JSON.parse('{!! str_replace('\'', '\\\'', $company->toJson()) !!}');
        var methTypeList = JSON.parse('{!! json_encode($methTypeList) !!}');
        var processes = JSON.parse('{!! json_encode($processes) !!}');
        var tableRows = JSON.parse('{!! json_encode($tableRows) !!}');
        var icons = JSON.parse('{!! json_encode($icons) !!}');

        function createMethodology() {
            let type = $('#meth_type').val();
            if (type == '') {
                toastr.error('Please select a Methodology Type');
            } else {
                let title = '';
                let content = '';
                let container = 'methodology-text-form-container';
                let cat = "TEXT";
                switch (type) {
                    case 'TASK_DESC':
                        title = 'Task Description';
                        content = company.task_description;
                        break;
                    case 'PLANT_EQUIP':
                        title = 'Plant & Equipment';
                        content = company.plant_and_equipment;
                        break;
                    case 'DISP_WASTE':
                        title = 'Disposing of Waste';
                        content = company.disposing_of_waste;
                        break;
                    case 'FIRST_AID':
                        title = 'First Aid';
                        content = company.first_aid;
                        break;
                    case 'NOISE':
                        title = 'Noise';
                        content = company.noise;
                        break;
                    case 'WORK_HIGH':
                        title = 'Working at Height';
                        content = company.working_at_height;
                        break;
                    case 'MAN_HANDLE':
                        title = 'Manual Handling';
                        content = company.manual_handling;
                        break;
                    case 'ACC_REPORT':
                        title = 'Accident Reporting';
                        content = company.accident_reporting;
                        break;
                    case 'TEXT_IMAGE':
                        container = 'methodology-text-image-form-container';
                        cat = 'TEXT_IMAGE';
                        break;
                    case 'SIMPLE_TABLE':
                        container = 'methodology-simple-table-form-container';
                        cat = 'SIMPLE_TABLE';
                        $('#simple-table tbody tr').remove();
                        $('#simple-table').attr('data-next_row', 1);
                        break;
                    case 'COMPLEX_TABLE':
                        container = 'methodology-complex-table-form-container';
                        cat = 'COMPLEX_TABLE';
                        $('#complex-table tbody tr').remove();
                        $('#complex-table').attr('data-next_row', 1);
                        break;
                    case 'PROCESS':
                        container = 'methodology-process-form-container';
                        cat = 'PROCESS';
                        $('#process-table tbody tr').remove();
                        $('#process-table').attr('data-next_row', 1);
                        break;
                    case 'ICON':
                        container = 'methodology-icon-form-container';
                        cat = 'ICON';
                        break;
                }
                $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                $('#methodology-list-container').hide();
                $('#' + container + ' #title').val(title);
                if ($('#' + container + ' #content')) {
                    $('#' + container + ' #content').val(content);
                }

                if ($('#' + container + ' #text_before').length) {
                    $('#' + container + ' #text_before').val('');
                }

                if ($('#' + container + ' #text_after').length) {
                    $('#' + container + ' #text_after').val('');
                }

                if ($('#' + container + ' input[name=image_on]').length) {
                    $('#' + container + ' input[name=image_on]')[0].checked = false;
                    $('#' + container + ' input[name=image_on]')[1].checked = false;
                }

                $('#' + container).css('display', 'inherit');
                $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+cat+"')");
            }
        }

        function editMethodology(id) {
            let methodology = methodologies.filter(methodologies => methodologies.id === id)
            if (methodology.length) {
                methodology = methodology[0];
                let title = methodology.title;
                let content = '';
                let container = 'methodology-text-form-container';
                switch (methodology.category) {
                    case 'TEXT':
                        content = methodology.text_before;
                        break;
                    case 'TEXT_IMAGE':
                        container = 'methodology-text-image-form-container';
                        var image_on = methodology.image_on;
                        var before = methodology.text_before;
                        var after = methodology.text_after;
                        break;
                    case 'SIMPLE_TABLE':
                        container = 'methodology-simple-table-form-container';
                        if (tableRows[methodology.id] !== 'undefined') {
                            let rows = tableRows[methodology.id];
                            $('#simple-table tbody').html('');
                            $.each(rows, function(key, row) {
                                let newRow = "<tr data-row='"+key+"'>";
                                    newRow += "<th><input type='text' name='row_"+key+"_col_1' value='"+row.col_1+"'></input></th>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_2' value='"+row.col_2+"'></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_3' value='"+row.col_3+"'></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_4' value='"+row.col_4+"'></input></td>";
                                newRow += "</tr>";
                                $('#simple-table tbody').append(newRow);
                                $('#simple-table').attr('data-next_row', row.id+1);
                            });
                        }
                        var before = methodology.text_before;
                        var after = methodology.text_after;
                        break;
                    case 'COMPLEX_TABLE':
                        container = 'methodology-complex-table-form-container';
                        if (tableRows[methodology.id] !== 'undefined') {
                            let rows = tableRows[methodology.id];
                            $('#complex-table tbody').html('');
                            $.each(rows, function(key, row) {
                                let newRow = "<tr data-row='"+key+"'>";
                                    newRow += "<th><input type='text' name='row_"+key+"_col_1' value='"+row.col_1+"'></input></th>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_2' value='"+row.col_2+"'></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_3' value='"+row.col_3+"'></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_col_4' value='"+row.col_4+"'></input></td>";
                                newRow += "</tr>";
                                $('#complex-table tbody').append(newRow);
                                $('#complex-table').attr('data-next_row', row.id+1);
                            });
                        }
                        var before = methodology.text_before;
                        var after = methodology.text_after;
                        break;
                    case 'PROCESS':
                        container = 'methodology-process-form-container';
                        if (processes[methodology.id] !== 'undefined') {
                            let rows = processes[methodology.id];
                            $('#process-table tbody tr').remove();
                            $.each(rows, function(key, row) {
                                console.log(row);
                                let checked = '';
                                if (row.heading == 1) {
                                    checked = 'checked';
                                }
                                let newRow = "<tr data-row='"+key+"'>";
                                    newRow += "<td><input type='checkbox' name='row_"+key+"_heading' "+checked+"></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_label' value='"+row.label+"'></input></td>";
                                    newRow += "<td><input type='text' name='row_"+key+"_description' value='"+row.description+"'></input></td>";
                                newRow += "</tr>"
                                $('#process-table tbody').append(newRow);
                                $('#process-table').attr('data-next_row', row.id+1);
                            });
                        }
                        break;
                    case 'ICON':
                        container = 'methodology-icon-form-container';
                        var before = methodology.text_before;
                        var after = methodology.text_after;
                        break;
                }
                $('[id^=methodology-][id$=-form-container]').css('display', 'none');

                $('#' + container + ' #title').val(title);

                if ($('#' + container + ' #content')) {
                    $('#' + container + ' #content').val(content);
                }


                // text + image, simple table, complex table, icon
                if ($('#' + container + ' #text_before')) {
                    $('#' + container + ' #text_before').val(before);
                }

                // text + image, simple table, complex table, icon
                if ($('#' + container + ' #text_after')) {
                    $('#' + container + ' #text_after').val(after);
                }

                // text + image
                if ($('#' + container + ' input[name=image_on]')) {
                    if (image_on == "LEFT") {
                        $('input:radio[name=image_on]')[0].checked = true;
                    } else if (image_on == "RIGHT") {
                        $('input:radio[name=image_on]')[1].checked = true;
                    }
                }

                $('#' + container).css('display', 'inherit');
                $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+methodology.category+"',"+id+","+methodology.list_order+")");
            }
        }

        function submitMethodologyForm(category, editId=null, listOrder=null) {
            var form_data = new FormData();

            form_data.append('_token', '{{ csrf_token() }}');
            form_data.append('entityType', '{{ $entityType }}');
            if(listOrder === null) {
                listOrder = methodologies.length +1; // create only.
            }
            form_data.append('list_order', listOrder);
            form_data.append('category', category);

            switch (category) {
                case 'TEXT':
                    form_data.append('title', $('#methodology-text-form-container #title').val());
                    form_data.append('text_before', $('#methodology-text-form-container #content').val());
                    break;
                case 'TEXT_IMAGE':
                form_data.append('title', $('#methodology-text-image-form-container #title').val());
                    if ($('#methodology-text-image-form-container #image').prop('files')[0] !== undefined){
                        form_data.append('image', $('#methodology-text-image-form-container #image').prop('files')[0]);
                    }
                    let checked = $('input[name=image_on]:checked').val();
                    if (checked && checked !== "undefined") {
                        form_data.append('image_on', checked);
                    }
                    form_data.append('text_before', $('#methodology-text-image-form-container #text_before').val());
                    form_data.append('text_after', $('#methodology-text-image-form-container #text_after').val());
                    break;
                case 'SIMPLE_TABLE':
                    form_data.append('title', $('#methodology-simple-table-form-container #title').val());

                    // get all inputs within the $('#simple-table') element and attach them?
                    let simple_inputs = $('#simple-table input[name^=row_]');
                    $.each(simple_inputs, function(key, input) {
                        form_data.append(input.name, input.value);
                    })

                    form_data.append('text_before', $('#methodology-simple-table-form-container #text_before').val());
                    form_data.append('text_after', $('#methodology-simple-table-form-container #text_after').val());
                    break;
                case 'COMPLEX_TABLE':
                    form_data.append('title', $('#methodology-complex-table-form-container #title').val());

                    // get all inputs within the $('#simple-table') element and attach them?
                    let complex_inputs = $('#complex-table input[name^=row_]');
                    $.each(complex_inputs, function(key, input) {
                        form_data.append(input.name, input.value);
                    })

                    form_data.append('text_before', $('#methodology-complex-table-form-container #text_before').val());
                    form_data.append('text_after', $('#methodology-complex-table-form-container #text_after').val());
                    break;

            }

            let url = 'methodology/create';
            if (editId) {
                url = 'methodology/'+editId+'/edit';
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: form_data,
                dataType    : 'text',
                cache       : false,
                contentType : false,
                processData : false,
                success: function (id) {
                    if (!editId) {// create
                        // adjust when we've got more types going in.
                        methodologies.push({
                            id: parseInt(id),
                            title: form_data.get('title'),
                            text_before: form_data.get('text_before'),
                            list_order: form_data.get('list_order'),
                            category: category,
                            entity: '{{$entityType}}',
                            image: form_data.get('image'),
                            image_on: form_data.get('image_on'),
                            text_after: form_data.get('text_after')
                        });
                        $('.methodology-list-table').append('<tr id="methodology-' + id + '">\
                                <td class="has-text-centered methodology-order">' + form_data.get('list_order')+ '</td>\
                                <td class="methodology-title">' + form_data.get('title') + '</td>\
                                <td class="methodology-category">' +  methTypeList[category] + '</td>\
                                <td class="handms-actions">\
                                    <a class="handms-icons" onclick="editMethodology('+ id +')">{{ icon('mode_edit') }}</a>\
                                    <a class="handms-icons" onclick="deleteMethodology('+id+')">{{ icon('delete') }}</a>\
                                    <a class="handms-icons" onclick="moveMethodologyUp('+id+')">{{ icon('keyboard_arrow_up') }}</a>\
                                    <a class="handms-icons" onclick="moveMethodologyDown('+id+')">{{ icon('keyboard_arrow_down') }}</a>\
                                </td>\
                            </tr>');
                    } else { // edit
                        for (let i = 0; i < methodologies.length; i++) {
                            if (methodologies[i]['id'] === editId) {
                                methodologies[i]['title'] = form_data.get('title'),
                                methodologies[i]['text_before'] = form_data.get('text_before'),
                                methodologies[i]['list_order'] = form_data.get('list_order'),
                                methodologies[i]['category'] = category,
                                methodologies[i]['entity'] = form_data.get('entity'),
                                methodologies[i]['image'] = form_data.get('image'),
                                methodologies[i]['image_on'] = form_data.get('image_on'),
                                methodologies[i]['text_after'] = form_data.get('text_after'),

                                // need to edit methodology table
                                $('tr#methodology-' + editId + ' .methodology-order').html(form_data.get('list_order'));
                                $('tr#methodology-' + editId + ' .methodology-title').html(form_data.get('title'));
                                $('tr#methodology-' + editId + ' .methodology-category').html(methTypeList[category]);
                                break;
                            }
                        }
                    }
                    $('#methodology-list-container').show();
                    $('[id^=methodology-][id$=-form-container]').css('display', 'none');
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

        function deleteMethodology(id) {
            if (confirm("Are you sure you want delete this methodology?")) {
                var data = {
                    _token: '{{ csrf_token() }}',
                    methodology_id: id,
                };
                $.ajax({
                    url: '/methodology/'+id+'/delete_methodology',
                    type: 'POST',
                    data: data,
                    success: function (response) {
                        if (response != "disallow") {
                            let listOrder = 0;
                            for (let i = 0; i < methodologies.length; i++) {
                                if (methodologies[i]['id'] == id) {
                                    // remove
                                    $('tr#methodology-' + id).remove();
                                    listOrder = methodologies[i]['list_order'];
                                    delete methodologies[i];
                                } else if (listOrder != 0 && methodologies[i]['list_order'] > listOrder) {
                                    // decrement order
                                    let newOrder = methodologies[i]['list_order'] - 1;
                                    $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(newOrder);
                                    methodologies[i]['list_order'] = newOrder;
                                }
                            }
                            methodologies = methodologies.filter(function (item) {
                                return item !== undefined;
                            });
                            toastr.success('Methodology was deleted');
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
                            toastr.error('An error has occured when deleting the methodology');
                        }
                    }
                });
            }
        }

        function moveMethodologyUp(id) {
            moveMethodology(id, "move_up")
        }

        function moveMethodologyDown(id) {
            moveMethodology(id, "move_down")
        }

        function moveMethodology(id, slug) {
            var data = {
                _token: '{{ csrf_token() }}',
                methodology_id: id,
            };
            $.ajax({
                url: '/methodology/'+id+'/'+slug,
                type: 'POST',
                data: data,
                success: function (response) {
                    if (response != "disallow") {
                        for (let i = 0; i < methodologies.length; i++) {
                            if (slug == 'move_down' && methodologies[i]['id'] == id) {
                                let firstOrder = methodologies[i]['list_order'];
                                let lastOrder = methodologies[i + 1]['list_order'];
                                $('tr#methodology-' + methodologies[i + 1]['id'] + ' .methodology-order').html(firstOrder);
                                $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(lastOrder);
                                methodologies[i + 1]['list_order'] = firstOrder;
                                methodologies[i]['list_order'] = lastOrder;
                                $('tr#methodology-' + methodologies[i + 1]['id']).after($('tr#methodology-' + methodologies[i]['id']));
                            } else if (slug == 'move_up' && methodologies[i]['id'] == id) {
                                let firstOrder = methodologies[i]['list_order'];
                                let lastOrder = methodologies[i - 1]['list_order'];
                                $('tr#methodology-' + methodologies[i - 1]['id'] + ' .methodology-order').html(firstOrder);
                                $('tr#methodology-' + methodologies[i]['id'] + ' .methodology-order').html(lastOrder);
                                methodologies[i - 1]['list_order'] = firstOrder;
                                methodologies[i]['list_order'] = lastOrder;
                                $('tr#methodology-' + methodologies[i]['id']).after($('tr#methodology-' + methodologies[i - 1]['id']));
                            }
                        }
                        methodologies = bubbleSort(methodologies, 'list_order');
                        toastr.success('Methodology was moved');
                    } else {
                        toastr.error('An error has occured when moving the methodology');
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
                        toastr.error('An error has occured when moving the methodology');
                    }
                }
            });
        }


    </script>
@endpush
