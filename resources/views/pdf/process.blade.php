<div class="width-50">
    <h3>{{$methodology->list_order}}. {{$methodology->title}}</h3>
    @php
        $rows = $methodology->instructions;
        $hasImage = $rows->max('image');
    @endphp

    @if($rows->isNotEmpty())
        <table class="process-table">
            <tr>
                <th>No</th>
                <th>Description</th>
                @if($hasImage)
                    <th>Photo</th>
                @endif
            </tr>
            @foreach($rows as $row)
                @if($row->heading)
                    <tr>
                        <td class="heading">{{$row->label}}</td>
                        <td class="heading"
                            @if($hasImage)
                               colspan="2"
                            @endif
                        >{{$row->description}}</td>
                    </tr>
                @else
                    <tr>
                        <td>{{$row->label}}</td>
                        <td
                        @if(!$row->image)
                            colspan="2"
                        @endif
                        >{{$row->description}}</td>

                        <?php
                        $imageSrc = null;
                        if (!is_null($row->image)) {
                            $imageSrc = EGFiles::download($row->image)->getFile()->getPathName() ?? null;
                        }
                        ?>

                        @if(!is_null($imageSrc))
                            <td><img style="height: 200px; width: 200px" src="{{$imageSrc}}"></td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </table>
    @endif
</div>
