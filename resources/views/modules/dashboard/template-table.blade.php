<div class="columns">
    <div class="column dash-container">
        <p class="dash-heading">{{$data['heading']}}
            <button class="dash-button button is-primary is-small" id="show-hide-{{$key}}" data-table="table-{{$key}}">Show/Hide</button>
        </p>
        <div class="dash-table" id="table-{{$key}}" style="display:none">
            <table class="table" id="{{$data['table-id']}}" style="width:100%">
                <thead>
                <th>Company	Name</th>
                <th>Reference</th>
                <th>Approved Date</th>
                <th>Review Due</th>
                <th>Revision Number</th>
                <th>Status</th>
                <th>Submitted By</th>
                <th>Submitted Date</th>
                <th>Approved By</th>
                <th>Resubmit By</th>
                </thead>
                <tbody>
                @foreach($data['data'] as $template)
                    <tr @if($template->trashed()) class="deleted" @endif >
                        <td>{{ $template->company->name }}</td>
                        <td>{{ $template->reference }}</td>
                        <td>{{ $template->niceApprovedDate() }}</td>
                        <td>{{ $template->niceReviewDueDate() }}</td>
                        <td>{{ $template->revision_number }}</td>
                        <td>{{ $template->niceStatus() }}</td>
                        <td>{{ $template->submittedName() }}</td>
                        <td>{{ $template->niceSubmittedDate() }}</td>
                        <td>{{ $template->approvedName() }}</td>
                        <td>{{ $template->niceResubmitByDate() }}</td>
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
                    $("ul[data-dtr-index=" + row.index() + "] li").last().after("<li><a href='" + row.data().url + "' class='button is-success'>View VTRAMS</a></li>")
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
