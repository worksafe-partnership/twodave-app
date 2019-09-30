<div class="columns">
    <div class="column is-8 is-offset-2">
        <form method="POST" enctype="multipart/form-data" id="approval-form">
            {{ csrf_field() }}
            <div class="columns">

            </div>
            <div class="columns">
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::file('havs_noise_assessment', [
                            'label' => 'HAVS/Noise Assessment Document',
                            'value' => $entity["havs_noise_assessment"],
                            'type' => 'view',
                        ]) }}
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::file('coshh_assessment', [
                            'label' => 'COSHH Assessment Document',
                            'value' => $entity["coshh_assessment"],
                            'type' => 'view',
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-4">
                    <div class="field">
                        {{ EGForm::text('submitted_by', [
                            'label' => 'Submitted By',
                            'value' => $entity->submitted->name ?? '',
                            'type' => 'view',
                        ]) }}
                    </div>
                </div>
                <div class="column is-4">
                    <div class="field">
                        {{ EGForm::text('submitted_date', [
                            'label' => 'Submitted',
                            'value' => $entity->niceSubmittedDate(),
                            'type' => 'view',
                        ]) }}
                    </div>
                </div>
                <div class="column is-4">
                    <div class="field">
                        {{ EGForm::text('resubmit_by', [
                            'label' => 'Resubmit By',
                            'value' => $entity->niceResubmitByDate(),
                            'type' => 'view',
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-12">
                    <div class="field type-check">
                        {{ EGForm::radio('type', [
                            'label' => 'Approval Type',
                            'value' => $record['type'],
                            'list' => $approvalTypes,
                            'type' => 'create',
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-12">
                    <div class="field">
                        {{ EGForm::ckeditor('comment', [
                            'label' => 'Comment',
                            'value' => $record['comment'],
                            'type' => 'create',
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column is-6">
                    <div class="field">
                        {{ EGForm::file('review_document', [
                            'label' => 'Review Document',
                            'value' => $record["review_document"],
                            'type' => 'create',
                        ]) }}
                    </div>
                </div>
                <div class="column is-6">
                    <div class="field type-details">
                        {{ EGForm::date('resubmit_date', [
                            'label' => 'Resubmit Date',
                            'value' => $record['resubmit_date'],
                            'type' => 'create',
                        ]) }}
                    </div>
                </div>
            </div>
            <div class="bottom-bar">
                <div class="field is-grouped is-grouped-centered ">
                    <p class="control">
                        <button class="button is-primary" onclick="$('#approval-form').submit();">Submit Feedback</button>
                    </p>
                    <p class="control">
                        <a href="{{ $cancelPath }}" class="button">Cancel</a>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>
@push('styles')
    <style>
        @php
            $oldType = old('type');
        @endphp
        @if (($oldType == "A") || (isset($record) && $record->type == "A"))
            @push('styles')
                <style>
                   .type-details {
                        display: none;
                    }
                </style>
            @endpush
        @endif
    </style>
@endpush
@push('scripts')
    <script>
        $('.type-check [name^=type]').click(function() {
            var name = $(this).val();
            if (name == "A") {
                $('.type-details').hide();
            } else {
                $('.type-details').show();
            }
        });
    </script>
@endpush
@include("egl::partials.ckeditor")
