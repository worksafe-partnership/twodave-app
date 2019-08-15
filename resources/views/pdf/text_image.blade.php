<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    @php
        $imageSrc = null;
        if (!is_null($methodology->image)) {
            $imageSrc = EGFiles::download($methodology->image)->getFile()->getPathName() ?? null;
        }
    @endphp
    @if($methodology->text_before)
        @if($methodology->image_on == "BEFOR")
            @if(!is_null($imageSrc))
                <div class="text-image-icon-div">
                    <img src="{{$imageSrc}}" style="width: 100px;">
                </div>
            @endif
            <div class="text-image-wording">
                {!!$methodology->text_before!!}
            </div>
        @else
            <div>
                {!!$methodology->text_before!!}
            </div>
            <br>
        @endif
    @endif
    @if($methodology->text_after)
        @if($methodology->image_on == "AFTER")
            @if(!is_null($imageSrc))
                <div class="text-image-icon-div">
                    <img src="{{$imageSrc}}" style="width: 100px;">
                </div>
            @endif
            <div class="text-image-wording">
                {!!$methodology->text_after!!}
            </div>
        @else
            <br>
            <div>
                {!!$methodology->text_after!!}
            </div>
        @endif
    @endif
</div>
