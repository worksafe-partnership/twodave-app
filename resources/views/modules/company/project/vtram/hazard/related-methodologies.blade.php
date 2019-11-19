<div class="columns">
    <div class="column is-10 is-offset-1" id="related_methodologies_div">
        {{ VTForm::multiSelect('related_methodologies[]', [
            'label' => 'Related Sections of Method Statement',
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
