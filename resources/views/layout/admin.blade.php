@extends('layout.master')
@section('content')
    @include('layout.flash_message')
    <main id="app">
        <div class="row">
            @include('admin.layout.nav')
            @yield('admin_content')
        </div>
        <post-update></post-update>
        <report-generated
                user-id="{{ getUserId() }}"
                accepted-url="{{route('admin.reports.index')}}"
        ></report-generated>
    </main>
@endsection
