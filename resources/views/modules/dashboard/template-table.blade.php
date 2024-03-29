<div class="columns">
    <div class="column dash-container">
        <p class="dash-heading">{{$data['heading']}}
            <button class="dash-button button is-primary is-small" id="show-hide-{{$key}}" data-table="table-{{$key}}">Show/Hide</button>
        </p>
        <div class="dash-table" id="table-{{$key}}" style="display:none">
            <table class="table" id="{{$data['table-id']}}" style="width:100%">
                <thead>
                <th>Company</th>
                <th>Name</th>
                <th>Reference</th>
                <th>Approved Date</th>
                <th>Review Due</th>
                <th>Revision Number</th>
                <th>Status</th>
                <th>Submitted By</th>
                <th>Submitted Date</th>
                <th>Approved By</th>
                <th>Resubmit By</th>
                <th>URL</th>
                </thead>
                <tbody>
                @foreach($data['data'] as $template)
                    <tr @if($template->trashed()) class="deleted" @endif >
                        <td>{{ $template->company->name }}</td>
                        <td>{{ $template->name }}</td>
                        <td>{{ $template->reference }}</td>
                        <td>{{ $template->approvedDateTimestamp() }}</td>
                        <td>{{ $template->reviewDueDateTimestamp() }}</td>
                        <td>{{ $template->revision_number }}</td>
                        <td>{{ $template->niceStatus() }}</td>
                        <td>{{ $template->submittedName() }}</td>
                        <td>{{ $template->submittedDateTimestamp() }}</td>
                        <td>{{ $template->approvedName() }}</td>
                        <td>{{ $template->resubmitByDateTimestamp() }}</td>
                        <td>{{ $template->url() }}</td>
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
                    "sEmptyTable": "No Templates found",
                },
                buttons: [],
                columns : [
                    { data: 'company_name', name: 'company_name' },
                    { data: 'template_name', name: 'template_name' },
                    { data: 'reference', name: 'reference' },
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
                    { data: 'review_due_date', name: 'review_due_date',
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
                    { data: 'revision_number', name: 'revision_number' },
                    { data: 'status', name: 'status' },
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
                    { data: 'approved_name', name: 'approved_name' },
                    { data: 'resubmit_date', name: 'resubmit_date',
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
                ]
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
