<div class="width-50">
    <h3>{{$methodology->list_order}}. {{$methodology->title}}</h3>
    @php
        $rows = $methodology->instructions;
    @endphp

    @if($rows->isNotEmpty())
        <table class="process-table">
            <tr>
                <th>No</th>
                <th>Description</th>
            </tr>
            @foreach($rows as $row)
                @if($row->heading)
                    <tr>
                        <td class="heading">{{$row->label}}</td>
                        <td class="heading">{{$row->description}}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{$row->label}}</td>
                        <td>{{$row->description}}</td>
                    </tr>
                @endif
            @endforeach
        </table>
    @endif
</div>
