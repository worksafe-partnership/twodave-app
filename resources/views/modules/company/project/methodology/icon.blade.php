<div class="columns">
    <div class="column is-10 is-offset-1">
        <p class="sub-heading">Details</p>
        <div class="field">
            {{ EGForm::text('main_text', [
                'label' => 'Main Text',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>
<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="field">
            {{ EGForm::text('sub_text', [
                'label' => 'Sub Text',
                'value' => '',
                'type' => $pageType
            ]) }}
        </div>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        first table
        <table class="table is-bordered">
            <thead>
                <th colspan="5" id="top_heading">Mandatory</th>
            </thead>
            <tbody>
                <tr>
                    @include('modules.company.project.methodology.icon_td', ['table' => 'top', 'row' => 1, 'col' => 1])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'top', 'row' => 1, 'col' => 2])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'top', 'row' => 1, 'col' => 3])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'top', 'row' => 1, 'col' => 4])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'top', 'row' => 1, 'col' => 5])
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        second table
        <table class="table is-bordered">
            <thead>
                <th colspan="5" id="sub_heading">Additional - to be worn as identified in Method of Work</th>
            </thead>
            <tbody>
                <tr>
                    @include('modules.company.project.methodology.icon_td', ['table' => 'bottom', 'row' => 1, 'col' => 1])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'bottom', 'row' => 1, 'col' => 2])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'bottom', 'row' => 1, 'col' => 3])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'bottom', 'row' => 1, 'col' => 4])
                    @include('modules.company.project.methodology.icon_td', ['table' => 'bottom', 'row' => 1, 'col' => 5])
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="columns">
    <div class="column is-10 is-offset-1">
        <div class="columns">
            <div class="column">
                <p class="sub-heading">Add New Icon</p>
                {{ EGForm::radio('type', [
                    'label' => 'Type',
                    'list' => config('egc.icon_types'),
                    'type' => $pageType,
                ]) }}
            </div>
        </div>
        <div class="columns">
            <div class="column is-4">
                {{ EGForm::select('icon_list', [
                    'label' => 'Select Icon',
                    'value' => '',
                    'type' => $pageType,
                    'list' => config('egc.icons'),
                ]) }}
            </div>
            <div class="column is-2">
                <div>
                    <image id="image_preview" src="/gfx/icons/no_image.png" class="image-box">

                    </img>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                {{ EGForm::text('words', [
                    'label' => 'Wording',
                    'value' => '',
                    'type' => $pageType,
                ]) }}
            </div>
        </div>
    </div>
</div>

<script>
    let images = JSON.parse('{!! str_replace('\'', '\\\'', $iconImages) !!}');
    function getImageSrc(key) {
        if (images[key]) {
            return images[key];
        } else {
            return '';
        }
    }

    $('#icon_list').on('change', function() {
        let src = getImageSrc( $(this).val() );
        if (src != "") {
            $('#image_preview').attr("src", "/"+src);
        }
    })

    $('#main_text').on('change keyup', function() {
        $('#top_heading').html($(this).val());
    })

    $('#sub_text').on('change keyup', function() {
        $('#sub_heading').html($(this).val());
    })
</script>

@push('styles')
    <style>
        .radio-inline {
            padding-right: 15px;
        }

        .image-box {
            height: 100px;
            width: 100px;
            border: 1px solid black;

        }
    </style>

    <style>



    </style>


@endpush
