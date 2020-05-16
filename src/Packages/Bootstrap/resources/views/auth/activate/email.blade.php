@extends('layouts.master')

@section('app-content')
    <div class="col-md-12">
        <div class="d-flex justify-content-center align-items-center" style="height: calc(100vh - 110px)">
            <div class="col-md-4 d-block">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title">Activate</h1>
                        <p>Please check your email to activate your account.</p>

                        <a class="btn btn-primary" href="{{ url('activate/send-token') }}">Request new Token</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

