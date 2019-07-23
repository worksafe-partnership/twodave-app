<div class="columns">
    <div class="column">
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
    <div class="column">
        <div class="field">
            {{ EGForm::ckeditor('content', [
                'label' => 'Content',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
