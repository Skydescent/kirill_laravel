@extends('layout.app')

@section('title', $post->title)

@section('app_content')
<div class="col-md-8 blog-main">
    <h3 class="mb-2 font-italic">
        {{ $post->title }}
    </h3>
    @can('update', $post)
        <h5>
            <a href="{{ route('posts.edit', ['post' => $post]) }}" class="badge badge-primary">Изменить</a>
        </h5>
    @endcan
    <p class="blog-post-meta">{{$post->created_at}} </p>

    {{ $post->body }}

    <hr>
    <a href="{{route('posts.index')}}">Вернуться на главную</a>
    @auth
        @include('comments.create', ['action' => route('post.comments.store', ['post' => $post])])
    @endauth

    @include('comments.index', ['model' => $post])
    <hr>
    @forelse($post->history as $item)
        <p>
            Пользоватьель: {{$item->name}} -- {{$item->pivot->created_at->diffForHumans()}}
            - обновил поля:
            @foreach(json_decode($item->pivot->changed_fields) as $field)
                {{$field}},
            @endforeach
        </p>
    @empty
        <p>Нет истории изменений</p>
    @endforelse
</div>
@endsection
