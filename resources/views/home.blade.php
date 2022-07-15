@extends('layouts.app')

@section('content')
    <div class="board__wrapper">
        <main class="board__container">
            <board :columns-data="{{$columns}}"></board>
        </main>
    </div>
   <fab></fab>
@endsection
