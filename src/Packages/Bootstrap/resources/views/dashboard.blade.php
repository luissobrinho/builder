@extends('layouts.master')

@section('app-content')

    <nav id="sidebar" class="bg-light sidebar">
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                @include('dashboard.panel')
            </ul>
        </div>
    </nav>

    <main class="ml-sm-auto pt-3 px-4 main" style="overflow-x: auto; height: calc(100vh - 111px);">
        <div class="mb-4">
            @yield('content')
        </div>
    </main>

@stop
