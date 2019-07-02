<div class="columns">
    <div class="column dash-container">
        <p class="dash-heading">{{$data['heading']}}
            <button style="float: right" class="button is-primary is-small" id="show-hide-{{$key}}" data-table="table-{{$key}}">Show/Hide</button>
        </p>
        <div class="dash-table" id="table-{{$key}}">
            <table class="table" id="{{$data['table-id']}}" style="width:100%">
                <thead>
                    <th>VTRAM Name</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Submitted By</th>
                    <th>Submitted On</th>
                    <th>Approval Date</th>
                    <th>Approved By</th>
                    <th>Next Review Date</th>
                </thead>
                <tbody>
                    @foreach($data['data'] as $vtram)
                    <tr>
                        <td>{{ $vtram->name }}</td>
                        <td>{{ $vtram->niceStatus() }}</td>
                        <td>{{ $vtram->createdName() }}</td>
                        <td>{{ $vtram->submittedName() }}</td>
                        <td>{{ $vtram->submittedDateTimestamp() }}</td>
                        <td>{{ $vtram->approvedDateTimestamp() }}</td>
                        <td>{{ $vtram->approvedName() }}</td>
                        <td>{{ $vtram->nextReviewDateTimestamp() }}</td>
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
            $("#{{$data['table-id']}}").DataTable({
                dom: 'Bfrtlip',
                responsive: true,
                oLanguage: {
                    "sLengthMenu": "Show _MENU_ records",
                    "sSearch": "Search:",
                },
                buttons: [],
                columns : [
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
                                    return '';
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
                ],
                "oLanguage": {
                    "sEmptyTable": "No Vtrams found"
                }

            });

            $('#show-hide-{{$key}}').click(function() {
                let table = "#"+$(this).data('table');
                if ($(table).is(':visible')) {
                    $(table).hide();
                    $(table).parent().css("border-bottom", "none");
                } else {
                    $(table).show();
                    $(table).parent().css("border-bottom", "5px solid #203878");
                }
            })
        });
    </script>
@endpush

@push('styles')
    <style>
        .dash-container {
            border: 5px solid #203878;
            padding: 0;
            margin-bottom: 5px;
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
    </style>
@endpush
