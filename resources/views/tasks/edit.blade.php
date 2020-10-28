@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Изменение задачи
        </h3>

        @include('layout.errors')

        <form method="POST" action="/tasks/{{ $task->id }}">

            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="inputTitle">Название задачи</label>
                <input type="text" class="form-control" id="inputTitle" placeholder="Введите название задачи"
                       name= "title"
                       value="{{ old('title', $task->title) }}">

            </div>
            <div class="form-group">
                <label for="inputBody">Описание задачи</label>
                <textarea name="body" class="form-control" id="inputBody" cols="30" rows="10">{{ old('body', $task->body) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>
    </div>
@endsection
