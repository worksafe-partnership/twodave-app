<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Details</p>
        <div class="field">
            {{ EGForm::radio('tickbox_answer', [
                'label' => 'Is this section considered relevant? (if no is selected, then the content will not show on the PDF)',
                'value' => '',
                'list' => [
                    '1' => 'Yes',
                    '0' => 'No'
                ],
                'type' => $pageType
            ]) }}
        </div>
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
        <div class="field">
            {{ EGForm::ckeditor('text_content', [
                'label' => 'Content',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::checkbox('text_page_break', [
                'label' => 'New Page After',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
