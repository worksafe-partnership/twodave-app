@component('mail::message')
Hello {{$user->name}},
<br>

<p>You have been invited to access the Worksafe Partnership VTRAMS System. Click the button below to set up your password and finalise your account.</p>
@component('mail::button', [
    'url' => url('/password/reset/'.$token)
])
Set Your Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
