<td data-table="{{$table}}">
    <td>
    <div class="image-box">
        <image src="/gfx/icons/no_image.png"></image>
    </div>
    <div class="field">
        {{ EGForm::select('icon_list_', [
            'label' => 'Select Icon',
            'value' => '',
            'type' => $pageType,
            'list' => config('egc.icons')
        ]) }}
    </div>
    <div class="field">
        {{ EGForm::text('wording', [
            'label' => 'Wording',
            'value' => '',
            'type' => $pageType,
        ]) }}
    </div>
    <div class="field">
        <a class="handms-icons" onclick="">{{ icon('tick') }}</a>
        <a class="handms-icons" onclick="">{{ icon('keyboard_arrow_left') }}</a>
        <a class="handms-icons" onclick="">{{ icon('keyboard_arrow_right') }}</a>
        <a class="handms-icons" onclick="">{{ icon('delete') }}</a>
    </div>
</td>
