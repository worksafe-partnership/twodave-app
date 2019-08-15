<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    @php
        $imageSrc = null;
        if (!is_null($methodology->image)) {
            $imageSrc = EGFiles::download($methodology->image)->getFile()->getPathName() ?? null;
        }
    @endphp
    <div class="image_text_block">
        @if($methodology->image_on == "BEFOR")
            <div class="text-image-icon-div" style="float:left; padding-right: 5px">
                <img src="{{$imageSrc}}" style="width: 100px;">
            </div>
            {!!$methodology->text_before!!}
        @else
            {!!$methodology->text_after!!}
            <div class="text-image-icon-div" style="float:left; bottom: 0; padding-right: 5px">
                <img src="{{$imageSrc}}" style="width: 100px;">
            </div>
        @endif
    </div>
</div>
