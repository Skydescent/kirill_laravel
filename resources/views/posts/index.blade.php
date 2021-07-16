@extends('layout.app')

@section('title', 'Главная')

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Список статей
        </h3>
        @foreach($posts as $post)
            @include('posts.item', ['user' => $user])
        @endforeach

        {{$posts->links()}}

    </div>
@endsection
