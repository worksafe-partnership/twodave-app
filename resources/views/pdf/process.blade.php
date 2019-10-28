<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    @if ($methodology->tickbox_answer == 0)
        <p>Is this section considered relevent?</p>
        <div class="tickbox">
            Yes <img src="{{ public_path('gfx/unchecked_box.png') }}" width="20" style="margin-left:5px;margin-right:10px;"> 
            No <img src="{{ public_path('gfx/checked_box.png') }}" width="20" style="margin-left: 5px;">
        </div>
    @else
        @php
            $rows = $methodology->instructions;
            $hasImage = $rows->max('image');
        @endphp

        @if($rows->isNotEmpty())
            <table class="process-table">
                <tr class="no-break">
                    <th>No</th>
                    <th>Description</th>
                    @if($hasImage)
                        <th>Photo</th>
                    @endif
                </tr>
                @foreach($rows as $row)
                    @php
                        $imageSrc = null;
                        if (!is_null($row->image)) {
                            $imageSrc = EGFiles::download($row->image)->getFile()->getPathName() ?? null;
                        }
                    @endphp
                    @if($row->heading)
                        <tr class="no-break">
                            <td class="heading">{{ $row->label }}</td>
                            <td class="heading"
                                @if(is_null($row->image))
                                   colspan="2"
                                @endif
                            >{!!$row->description!!}</td>
                            @if(!is_null($imageSrc))
                                <td><img style="max-width: 125px" src="{{$imageSrc}}"></td>
                            @endif
                        </tr>
                    @else
                        <tr class="no-break">
                            <td>{{ $row->label }}</td>
                            <td
                            @if(is_null($row->image))
                                colspan="2"
                            @endif
                            >{!!$row->description!!}</td>
                            @if(!is_null($imageSrc))
                                <td><img style="max-width: 125px" src="{{$imageSrc}}"></td>
                            @endif
                        </tr>
                    @endif
                @endforeach
            </table>
        @endif
    @endif
</div>
