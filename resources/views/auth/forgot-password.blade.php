<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://unpkg.com/spectre.css/dist/spectre.min.css" rel="stylesheet">
    <!-- Inclua outros estilos ou CSS personalizado aqui -->
    <style>
        body {
            background-color: #1e1e1e; /* Cor de fundo escura */
            color: #fff; /* Cor do texto */
            font-family: Arial, sans-serif;
        }

        .login-container {
            margin-top: 50px;
        }

        .login-form {
            background-color: #2b2b2b; /* Cor de fundo do formulário */
            padding: 20px;
            border-radius: 5px;
        }

        .forgot-password {
            margin-top: 10px;
            text-align: center;
        }

        .forgot-password a {
            color: #fff; /* Cor dos links */
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .form-group {
            position: relative;
            margin-bottom: 20px;
        }

        .form-group .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            left: 10px;
            color: #9e9e9e;
        }

        .form-input {
            padding-left: 30px;
        }
    </style>
</head>
<body>
    <div class="container grid-xl login-container">
        <div class="columns">
            <div class="column col-4 col-mx-auto">
                <form method="POST" action="{{ route('postlogin_view_reset') }}" class="login-form">
                    @csrf

                    <div class="form-group">
                        <span class="icon icon-24" data-spectre-icon="mail"></span>
                        <label class="form-label" for="email">Email:</label>
                        <input class="form-input" type="email" id="email" name="email" required autofocus>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary btn-block" type="submit">Send me link</button>
                    </div>
                </form>
                <div class="login">
                    <a href="{{ route('login_view') }}">to do login?</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
