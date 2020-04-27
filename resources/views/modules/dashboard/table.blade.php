<div class="columns">
    <div class="column dash-container">
        <p class="dash-heading">{{$data['heading']}}
            <button class="dash-button button is-primary is-small" id="show-hide-{{$key}}" data-table="table-{{$key}}">Show/Hide</button>
        </p>
        <div class="dash-table" id="table-{{$key}}" style="display:none">
            <table class="table" id="{{$data['table-id']}}" style="width:100%">
                <thead>
                    @php
                        $vtrams = $data['data'][0] ?? null;
                    @endphp
                    <th>Company</th>
                    <th>Project</th>
                    <th>{{ $vtrams->company->vtrams_name ?? 'VTRAMS' }} Name</th>
                    <th>{{ $vtrams->company->vtrams_name ?? 'VTRAMS' }} Number</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Submitted By</th>
                    <th>Submitted On</th>
                    <th>Approval Date</th>
                    <th>Approved By</th>
                    <th>Next Review Date</th>
                    <th>URL</th>
                </thead>
                <tbody>
                    @foreach($data['data'] as $vtram)
                        <tr>
                            <td>{{ $vtram->companyName() }}</td>
                            <td>{{ $vtram->project->name }}</td>
                            <td>{{ $vtram->name }}</td>
                            <td>{{ $vtram->number }}</td>
                            <td>{{ $vtram->niceStatus() }}</td>
                            <td>{{ $vtram->createdName() }}</td>
                            <td>{{ $vtram->submittedName() }}</td>
                            <td>{{ $vtram->submittedDateTimestamp() }}</td>
                            <td>{{ $vtram->approvedDateTimestamp() }}</td>
                            <td>{{ $vtram->approvedName() }}</td>
                            <td class="{{$vtram->dTClass($nowCarbon, $twoWeeksCarbon)}}">{{ $vtram->nextReviewDateTimestamp() }}</td>
                            @if(is_null($companyId))
                                <td>{{ $vtram->adminUrl() }}</td>
                            @else
                                <td>{{ $vtram->url() }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function(){
            var table = $("#{{$data['table-id']}}").DataTable({
                dom: 'Bfrtlip',
                responsive: true,
                oLanguage: {
                    "sLengthMenu": "Show _MENU_ records",
                    "sSearch": "Search:",
                },
                buttons: [],
                columns : [
                    { data: 'company_name', name: 'company_name' },
                    { data: 'number', name: 'number' },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status' },
                    { data: 'created_name', name: 'created_name' },
                    { data: 'submitted_name', name: 'submitted_name' },
                    { data: 'submitted_date', name: 'submitted_date',
                        render: function ( data, type, row ) {
                            if ( (type === 'display' || type === 'filter') && typeof(data) != "undefined") {
                                if (data != '' && data != null) {
                                    var dateFormat = "DD/MM/YYYY";
                                    return (moment(data * 1000).format(dateFormat));
                                } else {
                                    return data;
                                }
                            }
                            return data;
                        },
                    },
                    { data: 'approved_date', name: 'approved_date',
                        render: function ( data, type, row ) {
                            if ( (type === 'display' || type === 'filter') && typeof(data) != "undefined") {
                                if (data != '' && data != null) {
                                    var dateFormat = "DD/MM/YYYY";
                                    return (moment(data * 1000).format(dateFormat));
                                } else {
                                    return '';
                                }
                            }
                            return data;
                        },
                    },
                    { data: 'approved_name', name: 'approved_name' },
                    { data: 'next_review_date', name: 'next_review_date',
                        render: function ( data, type, row ) {
                            if ( (type === 'display' || type === 'filter') && typeof(data) != "undefined") {
                                if (data != '' && data != null) {
                                    var dateFormat = "DD/MM/YYYY";
                                    return (moment(data * 1000).format(dateFormat));
                                } else {
                                    return '';
                                }
                            }
                            return data;
                        },
                    },
                    { data: 'url', name: 'url', 'visible': '', 'searchable': false}
                ],
                "oLanguage": {
                    "sEmptyTable": "No Vtrams found"
                }

            });

            $("#{{$data['table-id']}}").on('click', 'tr', function(e) {
                var row = table.row(this);
                if (!row.responsive.hasHidden()) {
                    if (e.currentTarget.classList.contains("multiselect")) {
                        var checkbox = $(e.currentTarget).find(".b-checkbox input[type=checkbox]");
                        if (checkbox.is(":checked")) {
                            checkbox.removeAttr("checked");
                        } else {
                            checkbox.attr("checked", "checked");
                        }
                    } else {
                        e.preventDefault();
                        if (row.data()) {
                            document.location.href = row.data().url;
                        }
                    }
                }
            } );
            table.on('responsive-display', function (e, datatable, row, showHide, update){
                if (showHide) {
                    e.preventDefault();
                    $("ul[data-dtr-index=" + row.index() + "] li").last().after("<li><a href='" + row.data().url + "' class='button is-success'>View VTRAM</a></li>")
                }
            });
        });
        $('#show-hide-{{$key}}').click(function() {
            let tablediv = "#"+$(this).data('table');
            if ($(tablediv).is(':visible')) {
                $(tablediv).hide();
                $(tablediv).parent().css("border-bottom", "none");
            } else {
                $(tablediv).show();
                table.responsive.recalc();
                $(tablediv).parent().css("border-bottom", "5px solid #203878");
            }
        })
    </script>
@endpush

@push('styles')
    <style>
        .dash-container {
            border-top: 5px solid #203878;
            border-left: 5px solid #203878;
            border-right: 5px solid #203878;
            padding: 0;
            margin-bottom: 5px;
            overflow: scroll;
            scrollbar-width: none;
        }
        ::-webkit-scrollbar {
            display: none;
        }

        .dash-heading {
            color: white;
            font-size: 1.25rem;
            height: 2.2rem;
            background-color: #203878;
            font-weight: bold;
            padding-bottom: 5px;
            text-align: center;
        }

        .dash-table {
            margin: 5px;
        }
        .dash-button {
            float: right;
            border: 1px solid #FFF;
        }
    </style>
@endpush
