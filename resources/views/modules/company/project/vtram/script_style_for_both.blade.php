@push('styles')
    <style>
        textarea {
            height: 100%;
            width: 100%;
        }
        .center {
            text-align: center !important;
            margin-left: auto;
            margin-right: auto;
        }
        .methodology-list-table, .hazard-list-table, .risk-table {
            width: 100%;
        }
        .meth-type-selector {
            display: inline-block;
            margin-top: -21px;
            margin-right: 5px;
        }
        #comments-sidebar {
            border: 1px solid #404040;
            overflow-y: scroll;
            min-width: 300px;
            max-width: 400px;
            position: fixed;
            top: 0;
            right: 0;
            background-color: white;
            height: 100%;
            z-index: 999;
        }

        .inline-block {
            display: inline-block;
        }
        .handms-icons {
            border: 1px solid #404040;
            padding: 3px;
        }
        .handms-icons svg {
            width: 1.5rem;
            height: 1.5rem;
        }
        .handms-actions {
            text-align: center !important;
            width: 140px;
            height: 30px;
        }
        .hazard-desc {
            max-width: 260px;
        }
        #main-hazard-container, #main-methodology-container {
            position: relative;
        }
        #hazard-form-container, #methodology-form-container, #methodology-icon-form-container,
        #methodology-complex-table-form-container, #methodology-simple-table-form-container,
        #methodology-process-form-container, #methodology-text-form-container, #methodology-text-image-form-container {
            top: 0;
            width: 100%;
            display: none;
            z-index: 999;
            background-color: #FFF;
        }
        #hazard-list-container, #methodology-list-container {
            top: 0;
            border-top: none;
            width: 100%;
            background-color: #FFF;
        }
        .nopad {
            padding: 0px;
        }
        button[name="save_method_statement"], button[name="cancel_method_statement"], button[name="save_hazard"], button[name="cancel_hazard"] {
            display: none;
        }
        @media screen and (max-width:1090px) {
            .box-container {
                width: 100% !important;
            }
        }
    </style>
@endpush
@push('scripts')
    <script>
        // Both
        function cancelForm(type) {
            event.preventDefault();
            if (confirm("Any unsaved changes will be lost, are you sure?")) {
                if (type == 'hazard') {
                    $('#hazard-form-container').css('display', 'none');
                    $('#hazard-list-container').show();
                    $('#hazard-form-container #description').val('');
                    $('#hazard-form-container #other_at_risk').val('');
                    $('#hazard-form-container #control').val('');
                    @foreach ($whoList as $key => $who)
                        if ($('#hazard-form-container input[name="at_risk[{{ $key }}]"]').val() == "1") {
                            $('#main-hazard-container label[for="at_risk[{{ $key }}]"').click();
                        }
                    @endforeach
                    $('.r-risk-area .risk-rating').css('outline', 'none');
                    $('.risk-area .risk-rating').css('outline', 'none');
                    $('#related_methodologies_div .control select')[0].selectize.clear();
                    $('button[name="save_hazard"]').hide();
                    $('button[name="cancel_hazard"]').hide();
                } else {
                    $('#methodology-list-container').show();
                    $('[id^=methodology-][id$=-form-container]').css('display', 'none');
                    $('#meth_type').val('');
                    $('button[name="save_method_statement"]').hide();
                    $('button[name="cancel_method_statement"]').hide();
                }
            }
        }

        function bubbleSort(arr, field){
            var len = arr.length;

            for (var i = 0; i < len ; i++) {
                for (var j = 0 ; j < len - i - 1; j++) {
                    if (arr[j][field] > arr[j + 1][field]) {
                        // swap
                        var temp = arr[j];
                        arr[j] = arr[j + 1];
                        arr[j + 1] = temp;
                    }
                }
            }
            return arr;
        }
    </script>
@endpush
