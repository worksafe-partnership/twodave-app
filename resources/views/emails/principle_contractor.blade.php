<p>Dear {{ $project->principle_contractor_name }},<p>
<p>One or more {{ $project->company->vtrams_name }} are available for review, please click on the link below to access the {{ $project->company->vtrams_name }}</p>
<a href="{{ $link }}">Access my {{ $project->company->vtrams_name }}</a>
<p>Some email clients have trouble with links. If you're experiencing any issues please copy the following into a web browser:</p>
<p>{{ $link }}</p>
<p>Kind regards</p>
<p>The Worksafe Partnership</p>
