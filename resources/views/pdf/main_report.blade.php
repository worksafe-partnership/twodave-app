<html>
    <head>
        @include('pdf.styles')
    </head>
    <body>
        <div class="pdf-container">
            <div class="introduction">
                <div class="columns">
                    <div class="wide-50">
                        <table class="top-table">
                            <tr>
                                <th>Prepared by</th>
                                <td>{{ $entity->submitted->name }}</td>
                                <th>Date</th>
                                <td>{{ $entity->niceSubmittedDate() }}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td>{{ $entity->submitted->position }}</td>
                                <th>Signed</th>
                                <td></td>
                            </tr>
                        </table>
                    </div>    
                    <div class="wide-50">
                        <table class="top-table">
                            <tr>
                                <th>Approved by</th>
                                <td>{{ $entity->approved->name ?? $entity->project->principle_contractor_name }}</td>
                                <th>Date</th>
                                <td>{{ $entity->niceApprovedDate() }}</td>
                            </tr>
                            <tr>
                                <th>Position</th>
                                <td>{{ $entity->approved->position ?? '' }}</td>
                                <th>Signed</th>
                                <td></td>
                            </tr>
                        </table>
                    </div>    
                </div>
                <div>
                    <div class="wide-50">
                        <p><b>Company:</b> {{ $entity->company->name }}</p>
                        <p><b>{{ $entity->name_on_pdf }}:</b> {{ $entity->name_on_pdf == 'Client' ? $entity->project->client_name : $entity->project->principle_contractor_name }}</p>
                        <p><b>Project:</b> {{ $entity->project->name }}</p>
                    </div>    
                    <div class="wide-50">
                        <img src="">
                    </div>    
                </div>
                <div class="pdf-heading">
                    <h1>{{ $entity->company->vtrams_name }}</h1>
                    <p>{!! $entity->main_description !!}</p>
                </div>
            </div>
            <div class="method-statements">

            </div>
            <div class="risk-assessment">

            </div>
        </div>
    </body>
</html>
