@component('mail::message')
Olá {{ $user->c_logiusuar }},

Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.

@component('mail::button', ['url' => url('reset-senha/'.$user->remember_token)])
Redefinir sua senha
@endcomponent

Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
