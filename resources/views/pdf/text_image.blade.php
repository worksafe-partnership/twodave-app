<div class="width-50">
    <h3>{{$methodology->list_order}}. {{$methodology->title}}</h3>
    @if($methodology->text_before)
        @if($methodology->image_on == "RIGHT")
            <div class="text-image-wording">
                {{$methodology->text_before}}
            </div>
        @else
            <div>
                {{$methodology->text_before}}
            </div>
            <br>
        @endif
    @endif
    @php
        $imageSrc = null;
        if (!is_null($methodology->image)) {
            $imageSrc = EGFiles::download($methodology->image)->getFile()->getPathName() ?? null;
        }
    @endphp
    @if(!is_null($imageSrc))
        <div class="text-image-icon-div">
            <image src="{{$imageSrc}}" style="height: 100px; width: 100px;">
        </div>
    @endif
    @if($methodology->text_after)
        @if($methodology->image_on == "LEFT")
            <div class="text-image-wording">
                {{$methodology->text_after}}
            </div>
        @else
            <br>
            <div>
                {{$methodology->text_after}}
            </div>
        @endif
    @endif
</div>
