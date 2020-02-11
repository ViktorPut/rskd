<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<form method="POST" action="{{action([\App\Http\Controllers\UsersController::class, 'store'])}}" enctype="multipart/form-data" >
    @csrf
    {{method_field('POST')}}

    name: <input type="text" name="name"><br>
    email: <input type="text" name="email"><br>
    pass: <input type="text" name="newPassword"><br>
    info: <input type="text" name="userInfo"><br>
    phone: <input type="text" name="phone"><br>

    <input type="file" name="photo" id="photo" >
    <button type="submit">Добавить</button>
</form>
</body>
</html>
