@extends('layouts.master')

@section('app-content')

    <div class="row">
        <div class="col-md-12 form-small">
            <div class="card">
                <div class="card-body">

                    <h1 class="card-title">Password Reset</h1>

                    <form method="POST" action="{{ url('password/reset') }}">
                        {!! csrf_field() !!}
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row">
                            <div class="col-md-12 raw-margin-top-24">
                                <label for="email">Email</label>
                                <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 raw-margin-top-24">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password" id="password">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 raw-margin-top-24">
                                <label for="password_confirmation">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 raw-margin-top-24">
                                <button class="btn btn-primary" type="submit">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
