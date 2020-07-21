@foreach($tables as $key => $data)
    @if(isset($data['table-id']) and $data['table-id'] === 'templates')
        @include('modules.dashboard.template-table')
    @else
        @include('modules.dashboard.table')
    @endif
@endforeach
@push ('styles')
    <style>
        .dash-table tr {
            cursor: pointer;
        }
    </style>
@endpush
