@extends('backend')
@section('page_header')
    <h4>
	Role User Form
    </h4>
@stop
@section('main')
    <div class="page-content container-fluid">
    <div class="panel panel-bordered">
    <div class="panel-body">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">
                        Role User Form
                    </h3>
                </div>
                @if(isset($user))
                    {{ Form::open(['url' => url('backend/administrator/role/user-update/' . $user->id), 'autocomplete' => 'off', 'files' => true ]) }}
                @endif
                <div class="box-body pad">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">Name</label>
                        <input class="form-control" type="text" disabled="" value="{{ isset($user) ? $user->first_name : "" }}">
                        @if($errors->has('name'))
                            <p class="help-block">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="role">Role</label>
                        <br />
                        @foreach($roles as $role)
                            <div class="checkbox">
                                <label><input type="checkbox" name="role[]" value="{{ $role->id }}" {{ (array_key_exists($role->id, $userRole) ? "checked" : "") }}>{{ $role->name }}</label>
                            </div>
                        @endforeach
                    </div>
                <div class="box-footer pull-right">
                    <a href="{{ url('backend/administrator') }}" class="btn btn-default">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
@endsection