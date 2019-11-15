@php
    $show = false;
    if (!$a3) {
        $show = true;
    } else {
        if ($count % 2 == 1) {
            $show = true;
        }
    }
@endphp

@if($show)
    <div class="page-footer">
        @if ($company->show_document_ref_on_pdf)
            <div class="footer-ref">
                Doc Ref: {{$entity->reference}}
            </div>
        @endif
        @if ($company->show_message_on_pdf)
            <div class="footer-message">
                {!!$company->message!!}
            </div>
        @endif
            <div class="footer-ref">
                Doc No: {{$entity->revision_number}}
            </div>
        @if ($company->show_revision_no_on_pdf && !is_null($entity->revision_number))
        @endif
    </div>
@endif

