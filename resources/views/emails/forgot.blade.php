@component('mail::message')
Hello {{ $user->name }},

<p>We understand it happens. </p>

@component('mail::button', ['url' => url('reset/'. $user->remember_token)])
    Reset your password
@endcomponent

<p>Incase you have issues recovering your pasword, please contact us. </p>

Thanks, <br>
   {{ config('app.name') }} 
@endcomponent