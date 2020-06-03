@extends('dashboard')

@section('pageTitle') Roles: Edit @stop

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ url('admin/roles/' . $role->id) }}">
                        <input name="_method" type="hidden" value="PATCH">
                        @method('PUT')
                        {!! csrf_field() !!}

                        <div>
                            @input_maker_label('Name')
                            @input_maker_create('name', ['type' => 'string'], $role)
                        </div>

                        <div class="raw-margin-top-24">
                            @input_maker_label('Label')
                            @input_maker_create('label', ['type' => 'string'], $role)
                        </div>

                        <div class="raw-margin-top-24">
                            <h3>Permissions</h3>
                            @foreach(config('permissions', []) as $permission => $name)
                                <div class="custom-control custom-checkbox">
                                    @if (stristr($role->permissions, $permission))
                                        <input type="checkbox" class="custom-control-input" id="{{$name}}" name="permissions[{{ $permission }}]" checked>
                                    @else
                                        <input type="checkbox" class="custom-control-input" id="{{$name}}" name="permissions[{{ $permission }}]">
                                    @endif
                                    <label class="custom-control-label" for="{{$name}}">{{$name}}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="raw-margin-top-24">
                            <div class="btn-toolbar justify-content-between">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <a class="btn btn-secondary" href="{{ url('admin/roles') }}">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop
