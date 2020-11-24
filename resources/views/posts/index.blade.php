@extends('layout.master')

@section('title', 'Главная')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Список статей
        </h3>
        @foreach($posts as $post)
            @include('posts.item')
        @endforeach

        <nav class="blog-pagination">
            <a class="btn btn-outline-primary" href="#">Старше</a>
            <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Новее</a>
        </nav>

    </div>
@endsection
