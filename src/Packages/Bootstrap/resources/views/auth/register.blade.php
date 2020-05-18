@extends('layouts.master')

@section('app-content')
    <div class="col-12 mt-5">
        <h1 class="text-center">{{ config('app.name') }}</h1>
    </div>
    <div class="form-small mt-0">

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Register</h5>

                <form method="POST" action="{{ url('register') }}">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="name">Name</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" placeholder="Email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label> for="password_confirmation" Confirm Password</label>
                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="Password Confirmation">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <div class="btn-toolbar justify-content-between">
                                <button class="btn btn-primary" type="submit">Register</button>
                                <a class="btn btn-link" href="{{ url('login') }}">Login</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop
