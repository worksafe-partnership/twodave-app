<div class="width-50">
    <h3>{{$methodology->list_order}}. {{$methodology->title}}</h3>
    <p>{{$methodology->text_before}}</p>
    @php
        $rows = $methodology->tableRows;
    @endphp

    @if($rows->isNotEmpty())
        @php
            $maxFilled = $rows->max('cols_filled');
        @endphp

       <table class="complex-table">
        @foreach($rows as $row)
            <tr>
                @if($loop->first)
                    <?php $type = "th"; ?>
                @else
                    <?php $type = "td"; ?>
                @endif

                @if($row->col_1)
                    <{{$type}}>{{$row->col_1}}</{{$type}}>
                @endif
                @if($row->col_2)
                    <?php
                    $colspan = 1;
                    if ($row->cols_filled == 2 && $maxFilled > 2) {
                        $colspan = 1+($maxFilled-2);
                    }
                    ?>
                    <{{$type}} colspan="$colspan">{{$row->col_2}}</{{$type}}>
                @endif
                @if($row->col_3)
                    <?php
                    $colspan = 1;
                    if ($row->cols_filled == 3 && $maxFilled > 2) {
                        $colspan = 1+($maxFilled-2);
                    }
                    ?>
                    <{{$type}} colspan="$colspan">{{$row->col_3}}</{{$type}}>
                @endif
                @if($row->col_4)
                    <{{$type}}>{{$row->col_4}}</{{$type}}>
                @endif
            </tr>
        @endforeach
        </table>
    @endif

    <p>{{$methodology->text_after}}</p>

</div>
