@foreach($tables as $key => $data)
    @include('modules.dashboard.table')
@endforeach
@push ('styles')
    <style>
        .dash-table tr {
            cursor: pointer;
        }
    </style>
@endpush
