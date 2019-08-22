<div class="columns">
    <div class="column is-10 is-offset-1" id="related_methodologies_div">
        {{ VTForm::multiSelect('related_methodologies[]', [
            'label' => 'Related Methodologies',
            'value' => '',
            'list' => $methodologies->pluck('title', 'id'),
            'type' => 'edit',
        ]) }}
    </div>
</div>

@push('styles')
    <style>
        .selectize-control {
            min-width:100% !important;
        }
        .selectize-input {
            max-height: 100px;
            overflow-y: scroll;
        }
        .select:not(.is-multiple)::after {
            right: 1.7em !important;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $('.selectize-multiple').selectize({
            onInitialize: function() {
                $(this.$control).css('height', '38px');
                $(this.$control).css('z-index', 'initial');
            },
            onFocus: function() {
                $(this.$control).css('height', 'initial');
                $(this.$control).css('z-index', '999');
            },
            onBlur: function() {
                $(this.$control).css('height', '38px');
                $(this.$control).css('z-index', 'initial');
            },
        });
        $(document).on('DOMSubtreeModified', '.selectize-input', function () {
            this.scrollTop = this.scrollHeight;
        });
    </script>
@endpush
