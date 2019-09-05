<html>
    <head>
        @include('pdf.styles')
    </head>
    <script>
        function twoColumns() {
            // get elements
            var parent = document.querySelector('.parent');
            var column = document.querySelector('.column');
            var content = document.querySelector('.content');

            // Set heights
            //var introHeight = document.querySelector('.introduction').offsetHeight;
            //document.querySelector('.company-names').innerHtml += introHeight;
//            var titleHeight = document.getElementById('title-block-text').scrollHeight;
            var introHeight = {{ $startingHeight }};// + titleHeight;
            var maxHeight = 1300;
            var smallHeight = (maxHeight - introHeight) + "px";
            var bigHeight = maxHeight + "px";

            column.style.height = smallHeight;
            // calculate height values of column and it's content
            var columnHeight = column.offsetHeight;
            var contentHeight = content.offsetHeight;

            // create an array of offset values
            var offsetValues = [];
            var counter = 0;
            for (var i = columnHeight; i < contentHeight; i+= columnHeight) {
                counter++;
                if (counter > 1) {
                    columnHeight = maxHeight;
                }
                offsetValues.push(i);
            }

            // create a new column for each offset value
            counter = 0;
            offsetValues.forEach(function(offsetValue, i) {
                counter++;
                // init clone and add classes
                var cloneColumn = document.createElement('div');
                var cloneContent = document.createElement('div');
                if (counter > 1) {
                    cloneColumn.classList.add('column');
                    cloneColumn.style.height = bigHeight;
                } else {
                    cloneColumn.classList.add('column');
                    cloneColumn.style.height = smallHeight;
                }
                cloneContent.classList.add('content');
                
                // populate the DOM
                cloneContent.innerHTML = content.innerHTML;
                cloneColumn.appendChild(cloneContent);
                parent.appendChild(cloneColumn); 
                
                // apply position and offset styles
                cloneContent.style.position = 'relative';
                cloneContent.style.top = '-' + offsetValue + 'px';
            });
        }
    </script>
    <body onload="twoColumns()">
        <div class="pdf-container">
            <div class="introduction">
                <div class="columns">
                    @if (isset($tableHeight))
                        <table class="top-table" style="height: {{ $tableHeight }}px">
                    @else
                        <table class="top-table">
                    @endif
                        <tr>
                            <th style="width:90px;">Prepared by: </th>
                            <td>{{ $entity->submitted->name ?? ''}}</td>
                            <th style="width:50px;">Date: </th>
                            <td style="width:50px;">{{ $entity->niceSubmittedDate() }}</td>
                        </tr>
                        <tr>
                            <th>Position: </th>
                            <td>{{ $entity->submitted->position ?? '' }}</td>
                            <th>Signed: </th>
                            <td>
                                @if ($submittedSig != null) 
                                    <img src="{{ $submittedSig }}" height="30px">
                                @endif
                            </td>
                        </tr>
                    </table>
                    @if (isset($tableHeight))
                        <table class="top-table table-right" style="height: {{ $tableHeight }}px">
                    @else
                        <table class="top-table table-right">
                    @endif
                        <tr>
                            <th style="width:90px;">Approved by: </th>
                            @if ($type == 'VTRAM')
                                <td>{{ $entity->approved->name ?? $entity->project->principle_contractor_name }}</td>
                            @else
                                <td>{{ $entity->approved->name ?? '' }}</td>
                            @endif
                            <th style="width:50px;">Date: </th>
                            <td style="width:50px;">{{ $entity->niceApprovedDate() }}</td>
                        </tr>
                        <tr>
                            <th>Position: </th>
                            <td>{{ $entity->approved->position ?? '' }}</td>
                            <th>Signed: </th>
                            <td>
                                @if ($approvedSig != null) 
                                    <img src="{{ $approvedSig }}" height="30px">
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <br>
                <div>
                    <div class="wide-50 company-names">
                        <p><b>Trade Contractor:</b> {{ $entity->company->name  ?? '' }}</p>
                        @if ($type == 'VTRAM')
                            @if ($entity->client_on_pdf)
                                <p><b>Client:</b> {{ $entity->project->client_name }}</p>
                            @endif
                            @if ($entity->pc_on_pdf)
                                <p><b>Principal Contractor:</b> {{ $entity->project->principle_contractor_name }}</p>
                            @endif
                            <p><b>Project:</b> {{ $entity->project->name }}</p>
                            <p><b>Project Ref:</b> {{ $entity->project->ref }}</p>
                            @if ($entity->project->show_contact)
                                <p><b>Trade Contractor Contact: </b><br>Email: {{ $entity->company->email }}<br>Phone: {{ $entity->company->phone }}<br>Fax: {{ $entity->company->fax }}</p>
                                <p><b>Project Admin Contact: </b> {{ $entity->project->admin->email }}</p>
                            @endif
                        @endif
                        @if ($entity->show_area)
                            <p><b>Areas:</b> {{ $entity->area }}</p>
                        @endif
                        <p><b>{{ $entity->company->vtrams_name }} Ref:</b> {{ $entity->reference }}</p>
                        @if ($entity->show_responsible_person)
                            <p><b>Responsible Person: </b>{{ $entity->responsible_person }}</p>
                        @endif
                    </div>
                    <div class="wide-50">
                        @if ($logo != null)
                            <img src="{{ $logo }}" class="logo" style="height: {{ $height }}px;max-width:100%;">
                        @endif
                    </div>
                </div>
                <br>
                <div id="title-block-text" class="pdf-heading" style="background-color: {{ $entity->company->secondary_colour }}">
                    <h1>{{ $entity->company->vtrams_name ?? '' }} {{ $type == 'VTRAM' ? $entity->number : '' }}</h1>
                    <p>{!! $titleBlockText !!}</p>
                </div>
            </div>
            <h2>Method Statement</h2>
            <div class="method-statements parent">
                <div class="column">
                    <div class="content">
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
                        @if(!is_null($entity->key_points))
                            <?php
                            $keyPointsLogo = public_path('/key_points_logo.png');
                            ?>
                            <br>
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
                        @endif
                    </div>
                </div>
            </div>
            <div class="page"></div>
            <div class="risk-assessment">
                <div>
                    <div>
                        <h2 style="padding-top:80px;">Risk Assessment</h2>
                    </div>
                    <div class="risk-chart">
                        @include('modules.company.project.vtram.hazard.risk-chart', ['hazardType' => 'risk'])
                    </div>
                    <div class="risk-chart-key">
                        @include('modules.company.project.vtram.hazard.risk-key')
                    </div>
                </div>
                <br>
                <div class="risk-assessment-container">
                    <table class="risk-assessment-table">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2" class="hazard-width">Hazard/Risk</th>
                            <th rowspan="2" class="who-risk-width">Who at Risk</th>
                            <th colspan="3">Initial Risk</th>
                            <th class="left control-width">Controls</th>
                            <th colspan="3">Residual Risk</th>
                        </tr>
                        <tr>
                            <th class="haz-small-width">P</th>
                            <th class="haz-small-width">S</th>
                            <th class="haz-small-width">R</th>
                            <th class="control-width"></th>
                            <th class="haz-small-width">P</th>
                            <th class="haz-small-width">S</th>
                            <th class="haz-small-width">R</th>
                        </tr>
                        @foreach ($entity->hazards->sortBy('list_order') as $hazard)
                            <tr>
                                <td>{{ $hazard->list_order }}</td>
                                <td class="hazard-width">{!! $hazard->description !!}</td>
                                <td class="who-risk-width">
                                @foreach (array_filter($hazard->at_risk, function ($v) {
                                    return $v == "1";   
                                }) as $key => $risk)
                                        @if ($loop->last) 
                                            @if ($key == 'O')
                                                {{ $hazard->other_at_risk }}
                                            @else
                                                {{ $whoIsRisk[$key] }}
                                            @endif
                                        @else
                                            @if ($key == 'O')
                                                {{ $hazard->other_at_risk }} \
                                            @else
                                                {{ $whoIsRisk[$key] }} \
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td class="haz-small-width">{{ $hazard->risk_probability }}</td>
                                <td class="haz-small-width">{{ $hazard->risk_severity }}</td>
                                <td class="{{ $hazard->riskClass('risk') }} haz-small-width">{{ $riskList[$hazard->risk] }}</td>
                                <td class="left control-width">{!! $hazard->control !!}</td>
                                <td class="haz-small-width">{{ $hazard->r_risk_probability }}</td>
                                <td class="haz-small-width">{{ $hazard->r_risk_severity }}</td>
                                <td class="{{ $hazard->riskClass('r_risk') }} haz-small-width">{{ $riskList[$hazard->r_risk] }}</td>
                            </tr>
                        @endforeach
                        @if ($entity->dynamic_risk)
                            <tr>
                                <td colspan="10" style="text-align: center">Dynamic Risks</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    </table>
                </div>
                <p>{!! $postRiskText !!}</p>
            </div>
        </div>
    </body>
</html>
