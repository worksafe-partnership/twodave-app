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
            @if ($row->cols_filled > 0)
                <tr class="no-break">
                    @for ($i = 1; $i <= $maxFilled; $i++)
                        @php
                            $colspan = 1;
                            if ($row->cols_filled <= $i && $maxFilled > $i) {
                                $colspan += $maxFilled - $i;
                            }
                        @endphp
                        @if (($i <= $row->cols_filled && $i > 1) || $i <= 2)
                            @if ($i == 1)
                                <th>
                            @else
                                <td colspan="{{ $colspan }}">
                            @endif
                            {{ $row->{'col_'.$i} }}
                            @if ($i == 1)
                                </th>
                            @else
                                </td>
                            @endif
                        @endif
                    @endfor
                </tr>
            @endif
        @endforeach
        </table>
    @endif
    <p>{!!$methodology->text_after!!}</p>
</div>
