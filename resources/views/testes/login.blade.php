<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <h3>Login</h3>
  <form action="{{ route('password.request') }}">

    <input type="text" name="login" placeholder="your login name"/>
    <input type="text" name="email" placeholder="your email"/>
    <button type="button">login</button>
    <a href="{{ route('password.request') }}">recuperar senha</a>
  </form>


</body>
</html>
