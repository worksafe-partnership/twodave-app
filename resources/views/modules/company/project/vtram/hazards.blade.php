<div class="columns">
    <div class="column is-12">
        <h2 class="sub-heading inline-block">Risk Assessment</h2>
        <a href="javascript:createHazard()" class="button is-success is-pulled-right" title="Add Hazard/Risk">
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
                            <td class="hazard-desc">{!! $hazard->description !!}</td>
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
                <br>
            </div>
        </div>
    </div>
</div>
@push ('scripts')
<script>
    $('.risk-area .risk-rating').on('click', function () {
        $('.risk-area .risk-rating').css('outline', 'none');
        $(this).css('outline', '3px solid blue');
    });
    $('.r-risk-area .risk-rating').on('click', function () {
        $('.r-risk-area .risk-rating').css('outline', 'none');
        $(this).css('outline', '3px solid blue');
    });

    var hazards = JSON.parse('{!! str_replace("'", '&apos;', addslashes($hazards->toJson())) !!}');
    window.hazardMethodologies = JSON.parse('{!! str_replace("'", '&apos;', addslashes(json_encode($hazard_methodologies))) !!}');

    var riskLabels = JSON.parse('{!! json_encode($riskList) !!}');
    function createHazard() {
        $(".submit-hazard-form").attr("onclick","submitHazardForm()");
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

        $('.r-risk-area .risk-rating').css('outline', 'none');
        $('.risk-area .risk-rating').css('outline', 'none');
        $('#related_methodologies_div .control select')[0].selectize.clear();
        $('.submit-hazard-form').attr("disabled", false);
        $('button[name="save_hazard"]').show();
        $('button[name="cancel_hazard"]').show();
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
        if (window.hazardMethodologies[hazard['id']] !== "undefined") {
            var selectize = $('#related_methodologies_div .control select')[0].selectize;
            selectize.setValue(window.hazardMethodologies[hazard['id']]);
        }

        $(".submit-hazard-form").attr("onclick","submitHazardForm("+id+","+hazard['list_order']+")");
        $('#hazard-list-container').hide();
        $('.r-risk-area .risk-rating').css('outline', 'none');
        $('.risk-area .risk-rating').css('outline', 'none');
        $('.risk-area td[data-prob=' + hazard['risk_probability'] + '][data-severity=' + hazard['risk_severity'] + ']').css('outline', '3px solid blue');
        $('.r-risk-area td[data-prob=' + hazard['r_risk_probability'] + '][data-severity=' + hazard['r_risk_severity'] + ']').css('outline', '3px solid blue');
        $('#hazard-form-container').css('display', 'inherit');
        $('.submit-hazard-form').attr("disabled", false);
        $('button[name="save_hazard"]').show();
        $('button[name="cancel_hazard"]').show();
    }

    function submitHazardForm(editId=null, listOrder=null) {

        event.preventDefault();
        $('.submit-hazard-form').attr("disabled", true);

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
                delete window.hazardMethodologies[id];
                window.hazardMethodologies[id] = [];
                $.each(selectedMethodologies, function(key, value) {
                    window.hazardMethodologies[id].push(parseInt(value));
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
                $('.r-risk-area .risk-rating').css('outline', 'none');
                $('.risk-area .risk-rating').css('outline', 'none');
                $('#hazard-list-container').show();
                $('button[name="save_hazard"]').hide();
                $('button[name="cancel_hazard"]').hide();
            },
            error: function (data) {
                $('.submit-hazard-form').attr("disabled", false);
                if (data.status == 422) {
                    var errorList = JSON.parse(data.responseText);
                    $.each(errorList.errors, function(key,val) {
                        toastr.error(val);
                    });
                } else if (data.status == 401) {
                    toastr.error('Your sesson has expired, please refresh the page and login to proceed');
                } else {
                    toastr.error('An error has occured when saving the Risk Assessment');
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
                        toastr.success('Risk Assessment was deleted');
                    } else {
                        toastr.error('An error has occured when deleting the Risk Assessment');
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
                        toastr.error('An error has occured when deleting the Risk Assessment');
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
                    toastr.success('Risk Assessment was moved');
                } else {
                    toastr.error('An error has occured when moving the Risk Assessment');
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
                    toastr.error('An error has occured when moving the Risk Assessment');
                }
            }
        });
    }

    /*if (typeof bubbleSort != 'function') {
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
    }*/
</script>
@endpush
