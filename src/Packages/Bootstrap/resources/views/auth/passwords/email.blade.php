@extends('layouts.master')

@section('app-content')

    <div class="form-small">

        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Forgot Password</h2>

                <form method="POST" action="{{ url('password/email') }}">
                    {!! csrf_field() !!}
                    @include('partials.errors')
                    @include('partials.status')

                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <label for="email">Email</label>
                            <input class="form-control" type="email" name="email" id="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <button class="btn btn-primary btn-block" type="submit">Send Password Reset Link</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 raw-margin-top-24">
                            <a class="btn btn-link" href="{{ url('login') }}">Wait I remember!</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

@stop
