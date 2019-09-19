<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    @if ($methodology->tickbox_answer == 0)
        <p>Is this section considered relevent?</p>
        <div class="tickbox">
            Yes <img src="{{ public_path('gfx/unchecked_box.png') }}" width="20" style="margin-left:5px;margin-right:10px;"> 
            No <img src="{{ public_path('gfx/checked_box.png') }}" width="20" style="margin-left: 5px;">
        </div>
    @else
        {!!$methodology->text_before!!}
    @endif
</div>
