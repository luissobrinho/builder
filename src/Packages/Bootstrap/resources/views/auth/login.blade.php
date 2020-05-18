@extends('layouts.master')

@section('app-content')

    <div class="col-12 mt-5">
        <h1 class="text-center">{{ config('app.name') }}</h1>
    </div>
    <div class="form-small mt-0">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Please sign in</h5>

                <form method="POST" action="/login">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="password">Password</label>
                            <input class="form-control" type="password" name="password" id="password" placeholder="Password" id="password">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember" name="remember">
                                <label class="custom-control-label" for="remember">Remember Me</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <div class="btn-toolbar justify-content-between">
                                <button class="btn btn-primary" type="submit">Login</button>
                                <a class="btn btn-link" href="{{ url('password/reset') }}">Forgot Password</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <a class="btn raw100 btn-info" href="{{ url('register') }}">Register</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop

