<div class="modal" id="create_modal">
        <div class="modal-background" style="background-color: rgba(10, 10, 10, 0.4)"></div>
        <div class="modal-card" style="width:500px">
            <header class="modal-card-head">
                <p class="modal-card-title">Would you like to use a Template?</p>
            </header>
                <section class="modal-card-body">
                    <div class="columns">
                        <div class="column is-12">
                            @php
                                if(!isset($path)) {
                                    $path = "create";
                                }
                            @endphp
                            <a class="button is-success" href="{{$path}}">Create New VTRAM (No Template)</a><br><br>
                            @if (isset($company) && $company->allow_file_uploads)
                                <a class="button is-success" href="{{$path}}?file_upload=1">Create New VTRAM with File Upload</a><br><br>
                            @endif

                            @if(isset($templates) && $templates->isNotEmpty())
                                @foreach($templates as $key => $name)
                                    <a class="button is-success" href="{{$path}}?template={{$key}}" style="margin: 1px">{{$name}}</a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
            <footer class="modal-card-foot">
                <button class="button close_modal">Cancel</button>
            </footer>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        $(".create_vtram").click(function(e){
            e.preventDefault();
            $('#create_modal').addClass('is-active');
        });

        $("#close_modal").click(function() {
            event.preventDefault();
            $(".modal").removeClass("is-active");
        });

        $(".close_modal").click(function() {
            $(".modal").removeClass("is-active");
        });

        $(".modal-background").click(function() {
            $(".modal").removeClass("is-active");
        })

    </script>
@endpush
