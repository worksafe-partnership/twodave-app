<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Details</p>
        <div class="field">
            {{ EGForm::text('title', [
                'label' => 'Title',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::file('image', [
                        'label' => 'Image',
                        'value' => $record["image"],
                        'type' => $pageType,
                        'show_image' => true,
                    ]) }}
                </div>
                <div class="ti_image">
                </div>
            </div>
            <div class="column is-6">
                <div class="field">
                    {{ EGForm::radio('image_on', [
                        'label' => 'Text next to image',
                        'list' => ['BEFOR' => 'Before Text', 'AFTER' => 'After Text'],
                        'value' => '',
                        'type' => $pageType,
                    ]) }}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::ckeditor('image_text_before', [
                'label' => 'Before Text',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::ckeditor('image_text_after', [
                'label' => 'After Text',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

@push('styles')
    <style>
        .radio-inline {
            padding-right: 15px;
        }
    </style>
@endpush
