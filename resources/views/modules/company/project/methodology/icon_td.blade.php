<td data-table="{{$table}}" data-order="{{$order}}" class="is-4>
    <div class="field">
        <div class="image-box">
            <image class="logo-img" src="/gfx/icons/no_image.png"></image>
        </div>

        {{ EGForm::select('icon_list_', [
            'label' => 'Select Icon',
            'value' => '',
            'type' => $pageType,
            'classes' => ['td_icon_list'],
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
        <a class="handms-icons" onclick="moveIconLeft()">{{ icon('keyboard_arrow_left') }}</a>
        <a class="handms-icons" onclick="moveIconRight()">{{ icon('keyboard_arrow_right') }}</a>
        <a class="handms-icons" onclick="deleteIcon()">{{ icon('delete') }}</a>
    </div>
</td>
