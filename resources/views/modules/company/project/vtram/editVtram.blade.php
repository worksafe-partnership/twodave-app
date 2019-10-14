<div>
    @include('modules.company.project.vtram.ckeditor-key')
    <div style="height: 65px;text-align:right;">
        <button class="button is-success is-primary" id="comments-button">View Comments ( {{ $comments->count() }} )</button>
    </div>
</div>
<div class="columns is-multiline">
    <div class="column is-6 box-container">
        <div class="columns">
            <div class="column is-12">
                <h2 class="sub-heading inline-block">Method Statement</h2>
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
                    <a title="Add Method Statement" href="javascript:createMethodology()" class="button is-success is-pulled-right">
                        {{ icon('plus2') }}<span class="action-text is-hidden-touch"></span>
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
                                        <a title="Edit" class="handms-icons" onclick="editMethodology({{ $meth->id }})">{{ icon('mode_edit') }}</a>
                                        <a title="Delete" class="handms-icons" onclick="deleteMethodology({{ $meth->id }})">{{ icon('delete') }}</a>
                                        <a title="Move Up" class="handms-icons" onclick="moveMethodologyUp({{ $meth->id }})">{{ icon('keyboard_arrow_up') }}</a>
                                        <a title="Move Down" class="handms-icons" onclick="moveMethodologyDown({{ $meth->id }})">{{ icon('keyboard_arrow_down') }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div id="methodology-text-form-container">
                        @include('modules.company.project.methodology.text')
                        <div class="field is-grouped is-grouped-centered">
                            <p class="control">
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT');">Save Method Statement</button>
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
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('ICON');">Save Method Statement</button>
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
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('COMPLEX_TABLE');">Save Method Statement</button>
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
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('PROCESS');">Save Method Statement</button>
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
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('SIMPLE_TABLE');">Save Method Statement</button>
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
                                <button class="button is-primary submitbutton" onclick="submitMethodologyForm('TEXT_IMAGE');">Save Method Statement</button>
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
        <hr>
    </div>
    <div class="column is-6 box-container">
        <div class="columns">
            <div class="column is-12">
                <h2 class="sub-heading inline-block">Risk Assessment</h2>
                <a href="javascript:createHazard()" class="button is-success is-pulled-right" title="Add Risk Assessment">
                    {{ icon('plus2') }}<span class="action-text is-hidden-touch"></span>
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
                                        <a title="Edit" class="handms-icons" onclick="editHazard({{ $hazard->id }})">{{ icon('mode_edit') }}</a>
                                        <a title="Delete" class="handms-icons" onclick="deleteHazard({{ $hazard->id }})">{{ icon('delete') }}</a>
                                        <a title="Move Up" class="handms-icons" onclick="moveHazardUp({{ $hazard->id }})">{{ icon('keyboard_arrow_up') }}</a>
                                        <a title="Move Down" class="handms-icons" onclick="moveHazardDown({{ $hazard->id }})">{{ icon('keyboard_arrow_down') }}</a>
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
        <hr>
    </div>
</div>
<div id="comments-sidebar" class="column hidden">
    <p class="sub-heading">Comments
        <button class="button is-success is-small" style="float:right" id="close-comments">Close</button>
    </p>

    @if(isset($comments) && $comments->isNotEmpty())
        @foreach($comments as $comment)
            <p>{!!$comment->comment!!}</i></p>
            <p><i>{{$comment->completedByName()}} {{$comment->created_at->format('d/m/Y')}}</i></p>
            <hr>
        @endforeach
    @else
        <p>No Comments</p>
    @endif
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

@push('styles')
    <style>
        textarea {
            height: 100%;
            width: 100%;
        }
        .center {
            text-align: center !important;
            margin-left: auto;
            margin-right: auto;
        }
        .methodology-list-table, .hazard-list-table, .risk-table {
            width: 100%;
        }
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
            text-align: center !important;
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
            top: 0;
            width: 100%;
            display: none;
            z-index: 999;
            background-color: #FFF;
        }
        #hazard-list-container, #methodology-list-container {
            top: 0;
            border-top: none;
            width: 100%;
            background-color: #FFF;
        }
        .nopad {
            padding: 0px;
        }
        @media screen and (max-width:1090px) {
            .box-container {
                width: 100% !important;
            }
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

        $('.risk-area .risk-rating').on('click', function () {
            $('.risk-area .risk-rating').css('outline', 'none');
            $(this).css('outline', '3px solid blue');
        });
        $('.r-risk-area .risk-rating').on('click', function () {
            $('.r-risk-area .risk-rating').css('outline', 'none');
            $(this).css('outline', '3px solid blue');
        });

        // Both
        function cancelForm(type) {
            if (confirm("Any unsaved changes will be lost, are you sure?")) {
                if (type == 'hazard') {
                    $('#hazard-form-container').css('display', 'none');
                    $('#hazard-list-container').show();
                    $('#hazard-form-container #description').val('');
                    $('#hazard-form-container #other_at_risk').val('');
                    $('#hazard-form-container #control').val('');
                    @foreach ($whoList as $key => $who)
                        if ($('#hazard-form-container input[name="at_risk[{{ $key }}]"]').val() == "1") {
                            $('#main-hazard-container label[for="at_risk[{{ $key }}]"').click();
                        }
                    @endforeach
                    $('#related_methodologies_div .control select')[0].selectize.clear();
                } else {
                    $('#methodology-list-container').show();
                    $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                    $('#meth_type').val('');
                }
            }
        }

        // Hazard Scripts
        var hazards = JSON.parse('{!! str_replace("'", '&apos;', addslashes($hazards->toJson())) !!}');
        var hazardMethodologies = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($hazard_methodologies))) !!}');

        var riskLabels = JSON.parse('{!! json_encode($riskList) !!}');
        function createHazard() {
            $("#hazard-form-container .submitbutton").attr("onclick","submitHazardForm()");
            $('.risk-rating').css('border', '1px solid #404040');
            $('#hazard-form-container').css('display', 'inherit');
            $('#hazard-list-container').hide();

            CKEDITOR.instances.description.setData('');
            $('#other_at_risk').val('');
            CKEDITOR.instances.control.setData('');

            @foreach ($whoList as $key => $who)
                if ($('#hazard-form-container input[name="at_risk[{{ $key }}]"]').val() == "1") {
                    $('#main-hazard-container label[for="at_risk[{{ $key }}]"').click();
                }
            @endforeach

            $('#related_methodologies_div .control select')[0].selectize.clear();
            $('#main-hazard-container .submitbutton').attr("disabled", false);
        }

        function editHazard(id) {
            let hazard = '';
            for (let i = 0; i < hazards.length; i++) {
                if (hazards[i]['id'] == id) {
                    hazard = hazards[i];
                    break;
                }
            }
            CKEDITOR.instances.description.setData(hazard['description']);
            $('#hazard-form-container [name="at_risk"]').val(hazard['at_risk']);
            @foreach ($whoList as $key => $who)
                if (hazard['at_risk'].{{ $key }} == "1") {
                    $('#main-hazard-container label[for="at_risk[{{ $key }}]"').click();
                }
            @endforeach
            $('#hazard-form-container [name="other_at_risk"]').val(hazard['other_at_risk']);
            $('#hazard-form-container [name="risk"]').val(hazard['risk']);
            $('#hazard-form-container [name="risk_severity"]').val(hazard['risk_severity']);
            $('#hazard-form-container [name="risk_probability"]').val(hazard['risk_probability']);
            CKEDITOR.instances.control.setData(hazard['control']);
            $('#hazard-form-container [name="r_risk"]').val(hazard['r_risk']);
            $('#hazard-form-container [name="r_risk_severity"]').val(hazard['r_risk_severity']);
            $('#hazard-form-container [name="r_risk_probability"]').val(hazard['r_risk_probability']);

            // add all methodologies relevant to the hazard as selected to the selectize.
            if (hazardMethodologies[hazard['id']] !== "undefined") {
                var selectize = $('#related_methodologies_div .control select')[0].selectize;
                selectize.setValue(hazardMethodologies[hazard['id']]);
            }

            $("#hazard-form-container .submitbutton").attr("onclick","submitHazardForm("+id+","+hazard['list_order']+")");
            $('#hazard-list-container').hide();
            $('.risk-rating').css('border', '1px solid #404040');
            $('.risk-area td[data-prob=' + hazard['risk_probability'] + '][data-severity=' + hazard['risk_severity'] + ']').css('border', '3px solid blue');
            $('.r-risk-area td[data-prob=' + hazard['r_risk_probability'] + '][data-severity=' + hazard['r_risk_severity'] + ']').css('border', '3px solid blue');
            $('#hazard-form-container').css('display', 'inherit');
            $('#main-hazard-container .submitbutton').attr("disabled", false);
        }

        function submitHazardForm(editId=null, listOrder=null) {

            $('#main-hazard-container .submitbutton').attr("disabled", true);

            var selectedMethodologies = [];
            $.each($('#related_methodologies_div .item'), function(key, meth) {
                selectedMethodologies.push($(meth).attr('data-value'));
            });

            var data = {
                _token: '{{ csrf_token() }}',
                description: CKEDITOR.instances.description.getData(),
                control: CKEDITOR.instances.control.getData(),
                risk: $('#main-hazard-container #risk').val(),
                risk_probability: $('#main-hazard-container #risk_probability').val(),
                risk_severity: $('#main-hazard-container #risk_severity').val(),
                r_risk: $('#main-hazard-container #r_risk').val(),
                r_risk_probability: $('#main-hazard-container #r_risk_probability').val(),
                r_risk_severity: $('#main-hazard-container #r_risk_severity').val(),
                list_order: hazards.length + 1,
                at_risk: {
                    @foreach ($whoList as $key => $who)
                    {{ $key }}: $('#main-hazard-container [name="at_risk[{{ $key }}]"').val(),
                    @endforeach
                },
                other_at_risk: $('#main-hazard-container #other_at_risk').val(),
                entityType: '{{ $entityType }}',
                selectedMethodologies: selectedMethodologies
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
                            at_risk: {
                                @foreach ($whoList as $key => $who)
                                    {{ $key }}: data.at_risk.{{ $key }},
                                @endforeach
                            },
                            other_at_risk: data.other_at_risk
                        });
                        // Add hazards to table
                        $('.hazard-list-table').append('<tr id="hazard-' + id + '">\
                                <td class="has-text-centered hazard-order">' + data.list_order + '</td>\
                                <td class="hazard-desc">' + data.description + '</td>\
                                <td class="has-text-centered hazard-risk">' + riskLabels[data.risk] + '</td>\
                                <td class="has-text-centered hazard-r-risk">' + riskLabels[data.r_risk] + '</td>\
                                <td class="handms-actions">\
                                    <a title="Edit" class="handms-icons" onclick="editHazard(' + id + ')">{{ icon('mode_edit') }}</a>\
                                    <a title="Delete" class="handms-icons" onclick="deleteHazard(' + id + ')">{{ icon('delete') }}</a>\
                                    <a title="Move Up" class="handms-icons" onclick="moveHazardUp(' + id + ')">{{ icon('keyboard_arrow_up') }}</a>\
                                    <a title="Move Down" class="handms-icons" onclick="moveHazardDown(' + id + ')">{{ icon('keyboard_arrow_down') }}</a>\
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
                                hazards[i]['at_risk'] = {
                                    @foreach ($whoList as $key => $who)
                                        {{ $key }}: data.at_risk.{{ $key }},
                                        // These are one and blank not one and null
                                    @endforeach
                                },
                                hazards[i]['r_risk_probability'] = data.r_risk_probability,
                                hazards[i]['r_risk_severity'] = data.r_risk_severity,
                                hazards[i]['list_order'] = data.list_order,
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

                    // wipe out and update the related methodologies in local storage (saved in back end above ajax)
                    delete hazardMethodologies[id];
                    hazardMethodologies[id] = [];
                    $.each(selectedMethodologies, function(key, value) {
                        hazardMethodologies[id].push(parseInt(value));
                    });

                    // remove all previously selected for next view
                    $('#related_methodologies_div .control select')[0].selectize.clear();

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
                    @foreach ($whoList as $key => $who)
                        if ($('#hazard-form-container input[name="at_risk[{{ $key }}]"]').val() == "1") {
                            $('#main-hazard-container label[for="at_risk[{{ $key }}]"').click();
                        }
                    @endforeach
                    $('#hazard-list-container').show();
                },
                error: function (data) {
                    $('#main-hazard-container .submitbutton').attr("disabled", false);
                    if (data.status == 422) {
                        var errorList = JSON.parse(data.responseText);
                        $.each(errorList.errors, function(key,val) {
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
        var methodologies = JSON.parse('{!! str_replace("'", '&apos;',addslashes($methodologies->toJson())) !!}');
        var company = JSON.parse('{!! str_replace("'", '&apos;', addslashes($company->toJson())) !!}');
        var methTypeList = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($methTypeList))) !!}');
        var processes = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($processes))) !!}');
        var tableRows = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($tableRows))) !!}');
        var icons = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($icons))) !!}');
        var deletedImages = [];

        function createMethodology() {
            let type = $('#meth_type').val();
            deletedImages = [];
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
                        CKEDITOR.instances.text_content.setData(company.task_description);
                        break;
                    case 'PLANT_EQUIP':
                        title = 'Plant & Equipment';
                        CKEDITOR.instances.text_content.setData(company.plant_and_equipment);
                        break;
                    case 'DISP_WASTE':
                        title = 'Disposing of Waste';
                        CKEDITOR.instances.text_content.setData(company.disposing_of_waste);
                        break;
                    case 'NOISE':
                        title = 'Noise';
                        CKEDITOR.instances.text_content.setData(company.noise);
                        break;
                    case 'WORK_HIGH':
                        title = 'Working at Height';
                        CKEDITOR.instances.text_content.setData(company.working_at_height);
                        break;
                    case 'MAN_HANDLE':
                        title = 'Manual Handling';
                        CKEDITOR.instances.text_content.setData(company.manual_handling);
                        break;
                    case 'ACC_REPORT':
                        title = 'Accident Reporting';
                        CKEDITOR.instances.text_content.setData(company.accident_reporting);
                        break;
                    case 'FIRST_AID':
                        container = 'methodology-text-image-form-container';
                        cat = 'TEXT_IMAGE';
                        title = 'First Aid';
                        $('#'+container+' #image').val('');
                        $('.ti_image').html('');
                        CKEDITOR.instances.image_text_before.setData(company.first_aid);
                        CKEDITOR.instances.image_text_after.setData('');
                        break;
                    case 'TEXT_IMAGE':
                        container = 'methodology-text-image-form-container';
                        cat = 'TEXT_IMAGE';
                        $('#'+container+' #image').val('');
                        $('.ti_image').html('');
                        CKEDITOR.instances.image_text_before.setData('');
                        CKEDITOR.instances.image_text_after.setData('');
                        break;
                    case 'SIMPLE_TABLE':
                        container = 'methodology-simple-table-form-container';
                        cat = 'SIMPLE_TABLE';
                        $('#simple-table tbody tr').remove();
                        $('#simple-table').attr('data-next_row', 0);
                        CKEDITOR.instances.simple_text_before.setData('');
                        CKEDITOR.instances.simple_text_after.setData('');
                        break;
                    case 'COMPLEX_TABLE':
                        container = 'methodology-complex-table-form-container';
                        cat = 'COMPLEX_TABLE';
                        $('#complex-table tbody tr').remove();
                        $('#complex-table').attr('data-next_row', 0);
                        CKEDITOR.instances.complex_text_before.setData('');
                        CKEDITOR.instances.complex_text_after.setData('');
                        break;
                    case 'PROCESS':
                        container = 'methodology-process-form-container';
                        cat = 'PROCESS';
                        $('#process-table tbody tr').remove();
                        $('#process-table').attr('data-next_row', 0);
                        $('#is_heading').prop('checked', false);
                        $('#new_label').val('');
                        $('#new_description').val('');
                        $('#' + container + ' #image_id').val('');
                        break;
                    case 'ICON':
                        container = 'methodology-icon-form-container';
                        $('#top-body td').remove();
                        $('#bottom-body td').remove();
                        $('#icon_main_heading').val('');
                        $('#icon_sub_heading').val('');
                        $('#' + container + ' input[name=type]')[0].checked = false;
                        $('#' + container + ' input[name=type]')[1].checked = false;
                        $('#icon_list').prop('selectedIndex',0);
                        $('#image_preview').attr('src', '/gfx/icons/no_image.png');
                        $('#words').val('');
                        CKEDITOR.instances.icon_text_after.setData('');
                        cat = 'ICON';
                        break;
                }
                $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                $('#methodology-list-container').hide();
                $('#' + container + ' #title').val(title);
                $('#' + container + ' input[name="tickbox_answer"][value="1"]').attr('checked', true);

                if ($('#' + container + ' input[name=image_on]').length) {
                    $('#' + container + ' input[name=image_on]')[0].checked = false;
                    $('#' + container + ' input[name=image_on]')[1].checked = false;
                }

                $('#' + container).css('display', 'inherit');
                $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+cat+"')");
                $('#main-methodology-container .submitbutton').attr("disabled", false);
            }
        }

        function editMethodology(id) {
            let methodology = methodologies.filter(methodologies => methodologies.id === id)
            if (methodology.length) {
                methodology = methodology[0];
                let title = methodology.title.replace('&apos;', "'");
                let content = '';
                let container = 'methodology-text-form-container';
                deletedImages = [];
                switch (methodology.category) {
                    case 'TEXT':
                        CKEDITOR.instances.text_content.setData(methodology.text_before);
                        break;
                    case 'TEXT_IMAGE':
                        container = 'methodology-text-image-form-container';
                        var image_on = methodology.image_on;
                        CKEDITOR.instances.image_text_before.setData(methodology.text_before);
                        CKEDITOR.instances.image_text_after.setData(methodology.text_after);
                        $('#image').val('');
                        $('.ti_image').html('<img src="/image/'+methodology.image+'">');
                        break;
                    case 'SIMPLE_TABLE':
                        container = 'methodology-simple-table-form-container';
                        if (tableRows[methodology.id] !== 'undefined') {
                            let rows = tableRows[methodology.id];
                            $('#simple-table tbody').html('');
                            if (rows !== undefined) {
                                $.each(rows, function(key, row) {
                                    let newRow = "<tr class='columns' data-row='"+key+"'>";

                                        newRow += "<th class='column'><input type='text' name='row_"+key+"__col_1' value='";
                                        if (row.col_1 != null) {
                                            newRow += row.col_1+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_2' value='";
                                        if (row.col_2 != null) {
                                            newRow += row.col_2+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_3' value='";
                                        if (row.col_3 != null) {
                                            newRow += row.col_3+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_4' value='";
                                        if (row.col_4 != null) {
                                            newRow += row.col_4+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_5' value='";
                                        if (row.col_5 != null) {
                                            newRow += row.col_5+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column is-1'><a class='handms-icons delete_icon' onclick='deleteSimpleRow("+parseInt(key)+")'><svg class='eg-delete'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/eg-icons.svg#eg-delete'></use></svg></a>\
                                        </td>";
                                        newRow += "</tr>";
                                    $('#simple-table tbody').append(newRow);
                                });
                                $('#simple-table').attr('data-next_row', Object.keys(rows).length);
                            }
                        }
                        CKEDITOR.instances.simple_text_before.setData(methodology.text_before);
                        CKEDITOR.instances.simple_text_after.setData(methodology.text_after);
                        break;
                    case 'COMPLEX_TABLE':
                        container = 'methodology-complex-table-form-container';
                        if (tableRows[methodology.id] !== 'undefined') {
                            let rows = tableRows[methodology.id];
                            $('#complex-table tbody').html('');
                            if (rows !== undefined) {
                                $.each(rows, function(key, row) {
                                    let newRow = "<tr class='columns' data-row='"+key+"'>";

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_1' value='";
                                        if (row.col_1 != null) {
                                            newRow += row.col_1+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_2' value='";
                                        if (row.col_2 != null) {
                                            newRow += row.col_2+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_3' value='";
                                        if (row.col_3 != null) {
                                            newRow += row.col_3+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_4' value='";
                                        if (row.col_4 != null) {
                                            newRow += row.col_4+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }

                                        newRow += "<td class='column'><input type='text' name='row_"+key+"__col_5' value='";
                                        if (row.col_5 != null) {
                                            newRow += row.col_5+"'></input></td>";
                                        } else {
                                            newRow += "'></input></td>";
                                        }


                                    newRow += "<td class='column is-1'><a class='handms-icons delete_icon' onclick='deleteComplexRow("+parseInt(key)+")'><svg class='eg-delete'><use xmlns:xlink='http://www.w3.org/1999/xlink' xlink:href='/eg-icons.svg#eg-delete'></use></svg></a></td>";
                                    newRow += "</tr>";
                                    $('#complex-table tbody').append(newRow);
                                    $('#complex-table').attr('data-next_row', Object.keys(rows).length);
                                });
                            }
                        }
                        CKEDITOR.instances.complex_text_before.setData(methodology.text_before);
                        CKEDITOR.instances.complex_text_after.setData(methodology.text_after);
                        break;
                    case 'PROCESS':
                        container = 'methodology-process-form-container';
                        if (processes[methodology.id] !== 'undefined') {
                            let rows = processes[methodology.id];
                            $('#process-table tbody tr').remove();
                            if (rows !== 'undefined') {
                                $.each(rows, function(key, row) {
                                    let checked = '';
                                    if (row.heading == 1) {
                                        checked = 'checked';
                                    }
                                    let label = '';
                                    if (row.label) {
                                        label = row.label;
                                    }
                                    let description = '';
                                    if (row.description) {
                                        description = row.description;
                                    }

                                    let newRow = "<tr class='columns' data-row='"+key+"' style='margin:0'>";
                                        newRow += "<td class='column is-2'><input type='checkbox' name='row_"+key+"__heading' "+checked+"></input></td>";
                                        newRow += "<td class='column is-1'><input  type='text' name='row_"+key+"__label' value='"+label+"'></input></td>";
                                        newRow += "<td class='column is-3'><textarea name='row_"+key+"__description'>"+description+"</textarea></td>";
                                        if (row.image) {
                                            newRow += "<td class='column is-3 image-cell'><img class='process-image' src='/image/"+row.image+"' data-image_id='"+row.image+"' data-process_row='row_"+key+"__image'</td>";
                                        } else {
                                            newRow += "<td class='column is-3 image-cell'>No Image</td>";
                                        }
                                        newRow += '<td class="column is-3 handms-actions" style="height:150px">\
                                                    <a title="Delete" class="handms-icons delete_process" onclick="deleteProcess('+key+')">{{ icon("delete") }}</a>\
                                                    <a title="Move Up" class="handms-icons move_process_up" onclick="moveProcessUp('+key+')">{{ icon("keyboard_arrow_up") }}</a>\
                                                    <a title="Move Down" class="handms-icons move_process_down" onclick="moveProcessDown('+key+')">{{ icon("keyboard_arrow_down") }}</a>\
                                                    <div class="field image_picker">\
                                                        <input type="hidden" name="file" value="">\
                                                        <input type="hidden" name="file" id="file" value=""><div class="control">\
                                                        <input type="file" name="image_id" class="form-control  input " id="edit_image_'+key+'" value="">\
                                                    </div>\
                                                    <button class="button is-primary is-small image-button edit-image" onclick="editProcessImage('+key+')">Update Image</button>';

                                        if (row.image) {
                                            newRow +='<button class="button is-primary is-small image-button delete-image" onclick="deleteProcessImage('+key+')">Remove Image</button>';
                                        }
                                        newRow += '</td>';
                                    newRow += "</tr>"
                                    $('#process-table tbody').append(newRow);
                                    $('#process-table').attr('data-next_row', Object.keys(rows).length);
                                });
                            }
                            $('#is_heading').prop('checked', false);
                            $('#new_label').val('');
                            $('#' + container + ' #image_id').val('');
                            $('#new_description').val('');
                        }
                        break;
                    case 'ICON':
                        container = 'methodology-icon-form-container';
                        if (icons[methodology.id] !== 'undefined') {
                            let tables = icons[methodology.id];
                            $('#top-body td').remove();
                            $('#bottom-body td').remove();
                            if (tables !== 'undefined') {
                                $.each(tables, function(table, tds) {
                                    if (table == "MAIN") {
                                        var tr = $('#top-body tr');
                                        var location = 'top';
                                    } else {
                                        var tr = $('#bottom-body tr');
                                        var location = 'bottom';
                                    }

                                    for (let index = 0; index < Object.keys(tds).length; index++) {
                                        tr.append(renderTableData(tr, index, location, tds[index]));

                                        // correct selection of select field after rendering
                                        let selectName = 'icon_list_'+location+"_"+index;
                                        $('[name='+selectName+']').val(tds[index]['image']);
                                    }
                                })
                                $('#icon_main_heading').val(methodology.icon_main_heading);
                                $('#icon_sub_heading').val(methodology.icon_sub_heading);
                                $('#' + container + ' input[name=type]')[0].checked = false;
                                $('#' + container + ' input[name=type]')[1].checked = false;
                                $('#icon_list').prop('selectedIndex',0);
                                $('#image_preview').attr('src', '/gfx/icons/no_image.png');
                                $('#words').val('');
                            }
                        }
                        CKEDITOR.instances.icon_text_after.setData(methodology.text_after);
                        break;
                }
                $('#methodology-list-container').hide();
                $('[id^=methodology-][id$=-form-container]').css('display', 'none');

                $('#' + container + ' #title').val(title);

                // text + image
                if ($('#' + container + ' input[name=image_on]')) {
                    if (image_on == "BEFOR") {
                        $('input:radio[name=image_on]')[0].checked = true;
                    } else if (image_on == "AFTER") {
                        $('input:radio[name=image_on]')[1].checked = true;
                    }
                }

                $('#' + container + ' input[name="tickbox_answer"][value="' + methodology.tickbox_answer + '"]').prop('checked', true);

                $('#' + container).css('display', 'inherit');
                $('#' + container + ' .submitbutton').attr("onclick","submitMethodologyForm('"+methodology.category+"',"+id+","+methodology.list_order+")");
                $('#main-methodology-container .submitbutton').attr("disabled", false);
            }
        }

        function submitMethodologyForm(category, editId=null, listOrder=null) {

            // stop double click submits
            $('#main-methodology-container .submitbutton').attr("disabled", true);

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
                    form_data.append('tickbox_answer', $('#methodology-text-form-container [name="tickbox_answer"]:checked').val());
                    form_data.append('text_before', CKEDITOR.instances.text_content.getData());
                    break;
                case 'TEXT_IMAGE':
                    form_data.append('tickbox_answer', $('#methodology-text-image-form-container [name="tickbox_answer"]:checked').val());
                    form_data.append('title', $('#methodology-text-image-form-container #title').val());
                    if ($('#methodology-text-image-form-container #image').prop('files')[0] !== undefined){
                        form_data.append('image', $('#methodology-text-image-form-container #image').prop('files')[0]);
                    }

                    let imageCheck = $('.ti_image img');
                    if (imageCheck.length > 0) {
                        let image = $(imageCheck[0]).attr('src');
                        form_data.append('image_check', image);
                    }

                    let checked = $('input[name=image_on]:checked').val();
                    if (checked && checked !== "undefined") {
                        form_data.append('image_on', checked);
                    }
                    form_data.append('text_before', CKEDITOR.instances.image_text_before.getData());
                    form_data.append('text_after', CKEDITOR.instances.image_text_after.getData());
                    break;
                case 'SIMPLE_TABLE':
                    form_data.append('title', $('#methodology-simple-table-form-container #title').val());
                    form_data.append('tickbox_answer', $('#methodology-simple-table-form-container [name="tickbox_answer"]:checked').val());

                    // get all inputs within the $('#simple-table') element and attach them?
                    let simple_inputs = $('#simple-table input[name^=row_]');
                    $.each(simple_inputs, function(key, input) {
                        form_data.append(input.name, input.value);
                    })

                    form_data.append('text_before', CKEDITOR.instances.simple_text_before.getData());
                    form_data.append('text_after', CKEDITOR.instances.simple_text_after.getData());
                    break;
                case 'COMPLEX_TABLE':
                    form_data.append('title', $('#methodology-complex-table-form-container #title').val());
                    form_data.append('tickbox_answer', $('#methodology-complex-table-form-container [name="tickbox_answer"]:checked').val());

                    let complex_inputs = $('#complex-table input[name^=row_]');
                    $.each(complex_inputs, function(key, input) {
                        form_data.append(input.name, input.value);
                    })

                    form_data.append('text_before', CKEDITOR.instances.complex_text_before.getData());
                    form_data.append('text_after', CKEDITOR.instances.complex_text_after.getData());
                    break;
                case 'PROCESS':
                    form_data.append('title', $('#methodology-process-form-container #title').val());
                    form_data.append('tickbox_answer', $('#methodology-process-form-container [name="tickbox_answer"]:checked').val());
                    form_data.append('replacedImages', deletedImages);

                    let process_lines = $('#process-table input[name^=row_], #process-table textarea[name^=row_]');
                    $.each(process_lines, function(key, input) {
                        if (input.type == "text" || input.type == "textarea") {
                            form_data.append(input.name, input.value);
                        } else {
                            form_data.append(input.name, input.checked)
                        }
                    });

                    let images = $('#process-table img');
                    $.each(images, function(key, image) {
                        var name = $(images[key]).attr('data-process_row');
                        var value = $(images[key]).attr('data-image_id');
                        form_data.append(name, value);
                    })
                    break;
                case 'ICON':
                    form_data.append('title', $('#methodology-icon-form-container #title').val());
                    form_data.append('tickbox_answer', $('#methodology-icon-form-container [name="tickbox_answer"]:checked').val());
                    form_data.append('text_after', CKEDITOR.instances.icon_text_after.getData());

                    form_data.append('icon_main_heading', $('#methodology-icon-form-container #icon_main_heading').val());
                    form_data.append('icon_sub_heading', $('#methodology-icon-form-container #icon_sub_heading').val());

                    $.each($('#methodology-icon-form-container table td input'), function(key, input) {
                        form_data.append(input.name, input.value)
                    });

                    $.each($('#methodology-icon-form-container table td select'), function(key, select) {
                        form_data.append(select.name, select.value);
                    });
                    break;
            }

            let url = 'methodology/create';
            if (editId) {
                url = 'methodology/'+editId+'/edit';
            }

            var selectize = $('#related_methodologies_div .control select')[0].selectize;

            $.ajax({
                url: url,
                type: 'POST',
                data: form_data,
                dataType    : 'text',
                cache       : false,
                contentType : false,
                processData : false,
                success: function (data) {

                    if (category != "TEXT_IMAGE") {
                        var id = data;
                        var image = form_data.get('image');
                    } else {
                        data = JSON.parse(data);
                        var id = data.id;
                        var image = data.image;
                    }

                    if (!editId) {// create
                        methodologies.push({
                            id: parseInt(id),
                            title: form_data.get('title'),
                            text_before: form_data.get('text_before'),
                            list_order: form_data.get('list_order'),
                            category: category,
                            entity: '{{$entityType}}',
                            image: image,
                            image_on: form_data.get('image_on'),
                            text_after: form_data.get('text_after'),
                            icon_main_heading: form_data.get('icon_main_heading'),
                            icon_sub_heading: form_data.get('icon_sub_heading'),
                            tickbox_answer: form_data.get('tickbox_answer')
                        });
                        $('.methodology-list-table').append('<tr id="methodology-' + id + '">\
                                <td class="has-text-centered methodology-order">' + form_data.get('list_order')+ '</td>\
                                <td class="methodology-title">' + form_data.get('title') + '</td>\
                                <td class="methodology-category">' +  methTypeList[category] + '</td>\
                                <td class="handms-actions">\
                                    <a title="Edit" class="handms-icons" onclick="editMethodology('+ id +')">{{ icon('mode_edit') }}</a>\
                                    <a title="Delete" class="handms-icons" onclick="deleteMethodology('+id+')">{{ icon('delete') }}</a>\
                                    <a title="Move Up" class="handms-icons" onclick="moveMethodologyUp('+id+')">{{ icon('keyboard_arrow_up') }}</a>\
                                    <a title="Move Down" class="handms-icons" onclick="moveMethodologyDown('+id+')">{{ icon('keyboard_arrow_down') }}</a>\
                                </td>\
                            </tr>');

                        // add option to the methodology list within hazard form
                        selectize.addOption({value: id, text:form_data.get('title')});

                    } else { // edit
                        for (let i = 0; i < methodologies.length; i++) {
                            if (methodologies[i]['id'] === editId) {
                                methodologies[i]['title'] = form_data.get('title');
                                var textBefore = form_data.get('text_before');
                                if (textBefore != null) {
                                    textBefore = textBefore.replace("'", '&apos;');
                                }
                                methodologies[i]['text_before'] = textBefore;
                                methodologies[i]['list_order'] = form_data.get('list_order');
                                methodologies[i]['category'] = category;
                                methodologies[i]['entity'] = form_data.get('entity');
                                methodologies[i]['image'] = image;
                                methodologies[i]['image_on'] = form_data.get('image_on');
                                var textAfter = form_data.get('text_after');
                                if (textAfter != null) {
                                    textAfter = textAfter.replace("'", '&apos;');
                                }
                                methodologies[i]['text_after'] = textAfter;
                                var iconMain = form_data.get('icon_main_heading');
                                if (iconMain != null) {
                                    iconMain = iconMain.replace("'", '&apos;');
                                }
                                methodologies[i]['icon_main_heading'] = iconMain;
                                var iconSub = form_data.get('icon_sub_heading');
                                if (iconSub != null) {
                                    iconSub = iconSub.replace("'", '&apos;');
                                }
                                methodologies[i]['icon_sub_heading'] = iconSub;
                                methodologies[i]['tickbox_answer'] = form_data.get('tickbox_answer');

                                // need to edit methodology table
                                $('tr#methodology-' + editId + ' .methodology-order').html(form_data.get('list_order'));
                                var methTitle = form_data.get('title');
                                if (methTitle != null) {
                                    methTitle = methTitle.replace("'", '&apos;');
                                }
                                $('tr#methodology-' + editId + ' .methodology-title').html(methTitle);
                                $('tr#methodology-' + editId + ' .methodology-category').html(methTypeList[category]);
                                break;
                            }
                        }

                        // edit methodology name within methodolgy list with hazard form.
                        let data = {value: id, text:form_data.get('title')};
                        selectize.updateOption(id, data);
                    }

                    // write them to the local array to display when you hit Edit.
                    switch (category) {
                        case 'SIMPLE_TABLE':
                            delete tableRows[id];
                            let simple_rows = $('#simple-table tr');
                            tableRows[id] = [];
                            $.each(simple_rows, function(key, row) {
                                let inputs = $(row).find("input[name^=row_]");
                                let finalInputs = [];
                                for(let i = 0; i <= 4; i++) {
                                    var tempCol = inputs[i].value;
                                    if (tempCol != null) {
                                        tempCol = tempCol.replace("'", '&apos;');
                                    }
                                    finalInputs[i] = tempCol;
                                }
                                tableRows[id][key] = {
                                    col_1: finalInputs[0],
                                    col_2: finalInputs[1],
                                    col_3: finalInputs[2],
                                    col_4: finalInputs[3],
                                    col_5: finalInputs[4]
                                };
                                $(row).remove();
                            });
                            break;
                        case "COMPLEX_TABLE":
                            delete tableRows[id];
                            let complex_rows = $('#complex-table tr');
                            tableRows[id] = [];
                            $.each(complex_rows, function(key, row) {
                                let inputs = $(row).find("input[name^=row_]");
                                let finalInputs = [];
                                for(let i = 0; i <= 4; i++) {
                                    var tempCol = inputs[i].value;
                                    if (tempCol != null) {
                                        tempCol = tempCol.replace("'", '&apos;');
                                    }
                                    finalInputs[i] = tempCol;
                                }
                                tableRows[id][key] = {
                                    col_1: finalInputs[0],
                                    col_2: finalInputs[1],
                                    col_3: finalInputs[2],
                                    col_4: finalInputs[3],
                                    col_5: finalInputs[4]
                                };
                                $(row).remove();
                            });
                            break;
                        case "PROCESS":
                            delete processes[id];
                            let processRows = $('#process-table tbody tr');
                            processes[id] = [];
                            $.each(processRows, function(key, row) {
                                let inputs = $(row).find("input[name^=row_], textarea[name^=row]");
                                let checked = 0;

                                if (inputs[0].checked) {
                                    checked = 1;
                                }

                                let image_id = null;
                                let image = $(row).find("img");
                                if (image !== 'undefined') {
                                    image_id = $(image[0]).attr('data-image_id');
                                }

                                var label = inputs[1].value;
                                if (label != null) {
                                    label = label.replace("'", '&apos;');
                                }
                                var description = inputs[2].value;
                                if (description != null) {
                                    description = description.replace("'", '&apos;');
                                }

                                processes[id][key] = {
                                    heading: checked,
                                    label: label,
                                    description: description,
                                    image: image_id
                                }
                                $(row).remove();
                            });
                            break;
                        case "ICON":
                            delete icons[id];

                            let top = [];
                            let topData = $('#top-body td');
                            $.each(topData, function(key, cell) {
                                var td = $(cell);
                                var description = td.find('.wording').val();
                                if (description != null) {
                                    description = description.replace("'", '&apos;');
                                }
                                top[key] = {
                                    id: key,
                                    text: description,
                                    image: td.find('.td_icon_list').val(),
                                    list_order: key
                                }
                            })

                            let bottom = [];
                            let bottomData = $('#bottom-body td');
                            
                            $.each(bottomData, function(key, cell) {
                                var td = $(cell);
                                var description = td.find('.wording').val();
                                if (description != null) {
                                    description = description.replace("'", '&apos;');
                                }
                                bottom[key] = {
                                    id: key,
                                    text: description,
                                    image: td.find('.td_icon_list').val(),
                                    list_order: key
                                }
                            })

                            icons[id] = { MAIN: top, SUB: bottom };
                            break;
                        default: // text, all others not listed above
                            break;
                    }

                    $('#methodology-list-container').show();
                    $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                    $('#meth_type').val('');
                },
                error: function (data) {
                    $('#main-methodology-container .submitbutton').attr("disabled", false);
                    if (data.status == 422) {
                        var errorList = JSON.parse(data.responseText);
                        $.each(errorList.errors, function(key,val) {
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

                            // unset it in the hazards form's methodology field
                            var selectize = $('#related_methodologies_div .control select')[0].selectize;
                            selectize.removeOption(id);

                            // loop through each of the hazard methodology's data array and wipe out any instances of the deleted key.
                            $.each(hazardMethodologies, function (key, methodologyArray) {
                                hazardMethodologies[key] = methodologyArray.filter(function(methodologyKey) {
                                    return methodologyKey != id;
                                })
                            })

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
@include("egl::partials.ckeditor")
