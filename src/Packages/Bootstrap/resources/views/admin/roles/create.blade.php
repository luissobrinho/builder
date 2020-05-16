@extends('dashboard')

@section('pageTitle') Roles: Create @stop

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('admin/roles') }}">
                        {!! csrf_field() !!}

                        <div>
                            @input_maker_label('Name')
                            @input_maker_create('name', ['type' => 'string'])
                        </div>

                        <div class="raw-margin-top-24">
                            @input_maker_label('Label')
                            @input_maker_create('label', ['type' => 'string'])
                        </div>

                        <div class="raw-margin-top-24">
                            <h3>Permissions</h3>
                            @foreach(config('permissions', []) as $permission => $name)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{$name}}" name="permissions[{{ $permission }}]">
                                    <label class="custom-control-label" for="{{$name}}">{{$name}}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="raw-margin-top-24">
                            <div class="btn-toolbar justify-content-between">
                                <button class="btn btn-primary" type="submit">Create</button>
                                <a class="btn btn-secondary" href="{{ url('admin/roles') }}">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
