<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
    @foreach($house->photos as $photo)
        <br>

        <img src="{{asset($photo->path)}}"><br>
        {!! link_to_action('PhotosController@destroy', 'delete', [$photo]) !!}
        <form method="POST" action="{{url('photos/'.$house->id.'/create') }}"  enctype="multipart/form-data" >
            @csrf
            <input type="file" name="photos[]" id="photos" multiple="multiple">
            {{method_field('POST')}}
            <button type="submit">Add</button>
        </form>
    @endforeach
</body>
</html>
