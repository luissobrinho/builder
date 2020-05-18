@extends('dashboard')

@section('pageTitle') Teams: Create @stop

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('teams.store') }}">
                        {!! csrf_field() !!}

                        @form_maker_table("teams", ['name' => 'string'])

                        <div class="raw-margin-top-24">
                            <a class="btn btn-secondary pull-left" href="{{ url('teams') }}">Cancel</a>
                            <button class="btn btn-primary pull-right" type="submit">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
