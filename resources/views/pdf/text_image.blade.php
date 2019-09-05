<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    @php
        $imageSrc = null;
        if (!is_null($methodology->image) && VTFiles::exists($methodology->image)) {
            $imageSrc = EGFiles::download($methodology->image)->getFile()->getPathName() ?? null;
        }
    @endphp
    <div class="image_text_block">
        @if($methodology->image_on == "BEFOR")
            <div>
                <div class="text-image-div inline"><img src="{{$imageSrc}}" width="100"></div>
                <div style="width:340px" class="inline">{!!$methodology->text_before!!}</div>
            </div>
            <br>
            <div>{!!$methodology->text_after!!}</div>
        @else
            <div>{!!$methodology->text_before!!}</div>
            <br>
            <div>
                <div class="text-image-div inline"><img src="{{$imageSrc}}" width="100"></div>
                <div style="width:340px" class="inline">{!!$methodology->text_after!!}</div>
            </div>
        @endif
    </div>
</div>
