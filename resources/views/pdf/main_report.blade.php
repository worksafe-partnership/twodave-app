<html>
    <head>
        @include('pdf.styles')

    </head>
    <body>
        <div class="pdf-container">
            <div class="introduction">
                <div class="columns">
                    <table class="top-table">
                        <tr>
                            <th class="wide-100">Prepared by: </th>
                            <td class="wide-100">{{ $entity->submitted->name ?? '' }}</td>
                            <th>Date: </th>
                            <td class="wide-100">{{ $entity->niceSubmittedDate() }}</td>
                        </tr>
                        <tr>
                            <th>Position: </th>
                            <td>{{ $entity->submitted->position ?? '' }}</td>
                            <th>Signed: </th>
                            <td></td>
                        </tr>
                    </table>
                    <table class="top-table table-right">
                        <tr>
                            <th class="wide-100">Approved by: </th>
                            @if ($type == 'VTRAM')
                                <td class="wide-100">{{ $entity->approved->name ?? $entity->project->principle_contractor_name }}</td>
                            @else
                                <td class="wide-100">{{ $entity->approved->name ?? '' }}</td>
                            @endif
                            <th>Date: </th>
                            <td class="wide-100">{{ $entity->niceApprovedDate() }}</td>
                        </tr>
                        <tr>
                            <th>Position: </th>
                            <td>{{ $entity->approved->position ?? '' }}</td>
                            <th>Signed: </th>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <div class="wide-50">
                        <p><b>Company:</b> {{ $entity->company->name }}</p>
                        @if ($type == 'VTRAM')
                            <p><b>{{ $entity->name_on_pdf }}:</b> {{ $entity->name_on_pdf == 'Client' ? $entity->project->client_name : $entity->project->principle_contractor_name }}</p>
                            <p><b>Project:</b> {{ $entity->project->name }}</p>
                        @endif
                    </div>
                    <div class="wide-50">
                        @if ($logo != null)
                            <img src="{{ $logo }}" class="logo">
                        @endif
                    </div>
                </div>
                <div class="pdf-heading">
                    <h1>{{ $entity->company->vtrams_name }}</h1>
                    <p>{!! $entity->main_description !!}</p>
                </div>
            </div>
            <div class="method-statements">
                <h2>Method Statement</h2>
                @foreach($entity->methodologies->sortBy('list_order') as $meth)
                    @switch($meth->category)
                        @case('TEXT')
                            @include('pdf.text', ['methodology' => $meth])
                            @break
                        @case('TEXT_IMAGE')
                            @include('pdf.text_image', ['methodology' => $meth])
                            @break
                        @case('SIMPLE_TABLE')
                            @include('pdf.simple_table', ['methodology' => $meth])
                            @break
                        @case('COMPLEX_TABLE')
                            @include('pdf.complex_table', ['methodology' => $meth])
                            @break
                        @case('PROCESS')
                            @include('pdf.process', ['methodology' => $meth])
                            @break
                        @case('ICON')
                            @include('pdf.icon', ['methodology' => $meth])
                            @break
                    @endswitch
                @endforeach

                <?php
                $keyPointsLogo = public_path('/key_points_logo.png');
                ?>
                <div class="key-points width-50">
                    <div class="kp-heading-row">
                        <div class="kp-image-div">
                            <img src="{{$keyPointsLogo}}" style="height: 50px; width: 50px;">
                        </div>
                        <div class="kp-heading-div">
                            <h2 style="margin-top: 8px"> Key Points </h2>
                        </div>
                    </div>
                    <div class="kp-content">
                        {!!$entity->key_points!!}
                    </div>
                </div>

            </div>
            <div class="page"></div>
            <div class="risk-assessment">
                <h3>Risk Assessment</h3>
            </div>
        </div>
    </body>
</html>
