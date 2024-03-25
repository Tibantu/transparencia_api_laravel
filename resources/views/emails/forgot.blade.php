@component('mail::massage')
Olá {{ $user->c_logiusuar }}

@component('mail::button', ['url' => $request->url('admin/reset/'.$user->remenber_token)])
Rest your Password
@endcomponent
Obrigado><br>
<p>{{ $body }}</p>
@endcomponent
