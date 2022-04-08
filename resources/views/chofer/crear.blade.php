@extends('layouts.app')
@if (!Auth::user())
Usted no tiene permiso para visualizar esta página. 
@else
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
@section('content')
        <div><h1> Cargar un nuevo chofer </h1></div>
        <form method="POST" action="{{route('chofer.creado')}}">
            @csrf
            <label for="">Nombre <br>
                <input type="text" name="nombre" required minlength="2" maxlength="10" value = "{{old('name',request('nombre'))}}" >
            </label><br>
            <label for="">Apellido</label><br>
                <input type="text" name="apellido" value = "{{old('name',request('apellido'))}}"/><br>
                {!! $errors->first('precio','<small>:message</small></br>')!!}
            </label><br>
            <label for="">DNI</label><br>
                <input type="number" name="dni" value =  "{{old('name',request('dni'))}}"/><br>
                {!! $errors->first('stock','<small>:message</small></br>')!!}
            </label><br>
            <label for="">Email <br>
                <input type="email" name="email" required minlength="1" maxlength="20" value = "{{old('name',request('email'))}}">
            </label><br>
            <label for="">Password <br>
                <input type="password" name="password" required minlength="1" maxlength="10" value = "{{old('name',request('password'))}}" >
            </label><br>
            <br>
            <button class="btn btn-primary ml-2" type="submit">Enviar</button>
            <button type="button" class="btn btn-outline-primary"><a href="{{route('chofer.index')}}"> Atras </a></button>

        </form>
        <p>{{$data}}</p>

    @endsection
</body>
</html>

@endif