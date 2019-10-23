<div class="modal" id="create_vtrams_modal">
        <div class="modal-background" style="background-color: rgba(10, 10, 10, 0.4)"></div>
        <div class="modal-card" style="width:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">Please select a Project</p>
            </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">
                            <input type="hidden" id="new-template-from" value="">
                            @php
                                if(!isset($path)) {
                                    $path = "create";
                                }
                            @endphp
                            {{ EGForm::select('project_id', [
                                'label' => 'Project',
                                'list' => $projects,
                                'selector' => true,
                                'type' => 'create',
                            ]) }}
                        </div>
                    </div>
                </section>
            <footer class="modal-card-foot">
                <button onclick="createVtrams()" class="button is-primary">Create {{ $record->company->vtrams_name }}</button>
                <button class="button" id="close_modal">Cancel</button>
            </footer>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(".create_vtrams_from_template").click(function(e){
            e.preventDefault();
            $('#create_vtrams_modal').addClass('is-active');
            if (this.id == 'createVtrams-template') {
                $('#new-template-from').val('{{ $record->id }}');
            }
        });

        $("#close_modal, .modal-background").click(function() {
            $(".modal").removeClass("is-active");
        });

        function createVtrams() {
            var projId = $('#project_id').val();
            if (projId.length > 0) {
                var tempId = $('#new-template-from').val();
                if (tempId.length > 0) {
                    window.location.href = '{{ $templatePath }}' + $('#project_id').val() + '/vtram/create?template=' + tempId; 
                } else {
                    window.location.href = '{{ $templatePath }}' + $('#project_id').val() + '/vtram/create'; 
                }
            } else {
                toastr.error('Please select a Project');
            }
        }
    </script>
@endpush
