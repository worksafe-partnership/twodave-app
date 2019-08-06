<div class="width-50">
    <h3>{{$methodology->list_order}}. {!!$methodology->title!!}</h3>
    <p>{!!$methodology->text_before!!}</p>
    @php
        $rows = $methodology->tableRows;
    @endphp

    @if($rows->isNotEmpty())
        @php
            $maxFilled = $rows->max('cols_filled');
        @endphp

        <table class="simple-table">
        @foreach($rows as $row)
            <tr>
                @if($row->col_1)
                    <th>{!!$row->col_1!!}</th>
                @endif
                @if($row->col_2)
                    <?php
                    $colspan = 1;
                    if ($row->cols_filled == 2 && $maxFilled > 2) {
                        $colspan = 1+($maxFilled-2);
                    }
                    ?>
                    <td colspan="{{$colspan}}">{!!$row->col_2!!}</td>
                @endif
                @if($row->col_3)
                    <?php
                    $colspan = 1;
                    if ($row->cols_filled == 3 && $maxFilled > 2) {
                        $colspan = 1+($maxFilled-2);
                    }
                    ?>
                    <td colspan="{{$colspan}}">{!!$row->col_3!!}</td>
                @endif
                @if($row->col_4)
                    <td>{!!$row->col_4!!}</td>
                @endif
            </tr>
        @endforeach
        </table>
    @endif
    <p>{!!$methodology->text_after!!}</p>
</div>
