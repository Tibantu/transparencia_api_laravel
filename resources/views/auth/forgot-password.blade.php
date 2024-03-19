<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>

  <h3>Reset passwor</h3>
  <form action="{{ route('password.email') }}" method="POST">
    @csrf
    <input type="text" name="email" placeholder="your email"/>
    <button type="button">Envia me um link</button>
  </form>


</body>
</html>
