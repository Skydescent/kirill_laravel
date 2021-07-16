@extends('layout.app')

@section('app_content')
<div class="col-md-8 blog-main">
    <h3 class="pb-4 mb-4 font-italic border-bottom">
        {{ $task->title }}
        @can('update', $task)
            <a href="{{route('tasks.edit', ['task' => $task])}}">Изменить</a>
        @endcan
    </h3>

    {{ $task->body }}

    @if($task->steps->isNotEmpty())
        <ul class="list-group">
            @foreach($task->steps as $step)
                <li class="list-group-item">
                    <form
                            method="POST"
                            action="{{ route('step.' . ($step->completed ? 'incomplete' : 'complete'), ['step' => $step, 'task' => $task]) }}"
                    >
                        @if ($step->completed)
                            @method('DELETE')
                        @endif
                        @csrf
                        <div class="form-check">
                            <label class="form-check-label {{ $step->completed ? 'completed' : '' }}">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="completed"
                                    onclick="this.form.submit()"
                                    {{ $step->completed ? 'checked' : '' }}
                                >
                                {{$step->description}}
                            </label>
                        </div>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/tasks/{{ $task->id }}/steps" class="card card-body">
        @csrf
        <div class="form-group">
            <input
                type="text" class="form-control"
                placeholder="Шаг"
                value="{{ old('description') }}"
                name="description"
            >
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>

    @include('layout.errors')

    <hr>
    <a href="{{route('tasks.index')}}">Вернуться к списку</a>

</div>
@endsection
