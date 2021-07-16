@extends('layout.app')

@section('app_content')
    <div class="col-md-8 blog-main">
    <h3 class="pb-4 mb-4 font-italic border-bottom">
        Список задач
    </h3>
        @foreach($tasks as $task)
            @include('tasks.item')
        @endforeach

</div>
@endsection
