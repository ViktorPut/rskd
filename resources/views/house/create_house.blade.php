<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
{{--https://www.tutcodex.com/laravel-collective-html-form-builder/--}}
{{--Вот ссылка, изучи как формы такие использовать--}}
{{--{{!!  Form::open()!!}--}}
{{--{{!! Form::close() !!}}--}}
<form method="POST" action="{{action([\App\Http\Controllers\HousesController::class, 'store'])}}" enctype="multipart/form-data" >

{{--    //Обязательно при POST/PUT/PATCH/DELETE всегда пиши токен csrf, не удаляй эту строку (%Витя%)--}}
    @csrf
{{--    //Так ты можешь указать метод, которым будешь пользоваться(%Витя%)--}}
    {{method_field('POST')}}
{{--    Когда будешь менять что-то, обрати внимние на имена Input, должны быть такими же, как сейчас--}}


Адрес: <br>

St: <input type="text" name="streetName"><br>
Dis: <input type="text" name="districtName"><br>
City: <input type="text" name="cityName"><br>
Country: <input type="text" name="countryName"><br>
Number: <input type="text" name="houseNumber"><br>
<br>
Этажность: <input type="text" name = "floors"> <br>
Кол-во комнат: <input type="text" name = "rooms"><br>
Площадь:   <input type="number" name = "space"><br>
Стоимость: <input type="number" name = "cost"><br>
Описание: <input type="textarea" name = "description"><br>
</p>
{{--    Временное решение, вообще надо делать, чтоб новый элемент массива с такими параметрами создавалась--}}
{{--    Обрати Внимание, на идексы. Это 2мерный массив и параметры должны быть именно так названы--}}
Параметры:
@for($i = 0; $i<10; $i++)
    <p>
        <input type="text" name="parameters[name][]">
        <input type="text" name="parameters[value][]">
    </p>
@endfor
    @if(\Illuminate\Support\Facades\Auth::user()->isAdmin())
    <select>
        @foreach(App\User::getManagerList() as $user)
            <option name="user" value="{{$user->id}}">{{$user->email}}</option>
        @endforeach
    </select>
    @endif
{{--    Для фоток, в принципе так и было примерно, хз что тут придумать еще--}}
<br>
<input type="file" name="photos[]" id="photos" multiple="multiple">
<button type="submit">Добавить</button>
</form>
</body>
</html>
