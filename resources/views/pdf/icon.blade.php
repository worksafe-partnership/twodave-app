<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>

    <table class="icon-table main-table">
        @foreach (['MAIN' => 'icon_main_heading','SUB' => 'icon_sub_heading'] as $key => $heading)
            @php
                $icons = $methodology->icons->where('type', $key)->sortBy('list_order');
                $count = $icons->count();
            @endphp
            @if ($count > 0)
                <tr class="no-break">
                    <th colspan='5'>{!! $methodology->{$heading} !!}</th>
                </tr>
                @php
                    $mod = $count % 5;
                    if ($mod != 0) {
                        $remainder = 5 - ($count % 5);
                    } else {
                        $remainder = 0;
                    }
                    $sources = config('egc.icon_images');
                @endphp

                <tr class="no-break">
                @foreach($icons as $key => $icon)
                    @php
                        $image = $sources[$icon->image];
                    @endphp
                    <td style="width:20%;vertical-align:top;">
                        @if($image != "gfx/icons/no_image.png")
                            <img src="{{ public_path($image) }}" style="width: 70px;"></img>
                        @else
                            <span style="width: 70%"></span>
                        @endif
                        @if($icon->text != "null")
                          <p>{!!$icon->text!!}</p>
                        @endif
                    </td>
                    @if(($loop->index+1) % 5 == 0 && $loop->remaining > 0)
                </tr>
                <tr class="no-break">
                    @endif
                @endforeach
                @if ($remainder > 0)
                    @for($empties = 0; $empties < $remainder; $empties++)
                        <td></td>
                    @endfor
                @endif
                </tr>
            @endif
        @endforeach
    </table>
    <p>{!! $methodology->text_after !!}</p>
</div>
