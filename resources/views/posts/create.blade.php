@extends('layout.app')

@section('title', 'Создание статьи')

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Создание статьи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route('posts.store') }}">

            @csrf

            @include('posts.createOrUpdate', [
                'post' => new \App\Models\Post(),
                'btnText' => 'Создать',
            ])
        </form>
    </div>
@endsection
