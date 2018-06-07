@extends('backend')
@section('page_header')
    <h4>
	Admin User
    </h4>
@stop
@section('breadcrumb')
	<li>
        <i class="fa fa-dashboard"></i> Dashboard
    </li>
    <li>
        <i class="fa fa-briefcase"></i> Admin
    </li>
    <li class="active">
        <i class="fa fa-gift"></i> Backend User Form
    </li>
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
                        backend User Form
                    </h3>
                </div>
                
                @if(isset($myProfile))
                    {{ Form::open(['url' => url('backend/profile-update'), 'autocomplete' => 'off', 'files' => true ]) }}
                @elseif(isset($user))
                    {{ Form::open(['url' => url('backend/administrator/update/' . $user->id), 'autocomplete' => 'off', 'files' => true ]) }}
                @else
                    {{ Form::open(['url' => url('backend/administrator/save'), 'autocomplete' => 'off', 'files' => true ]) }}
                @endif
                <div class="box-body pad">
                    @if(Session::has('alert-class'))
                        <div class="text-center alert alert-{{ (Session::get('alert-class') == "success") ? "success" : "danger" }}" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        <label for="first_name">First Name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="Name" value="{{ isset($user) ? $user->first_name : old('first_name') }}" required>
                        @if($errors->has('first_name'))
                            <p class="help-block">{{ $errors->first('first_name') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        <label for="last_name">Last Name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Name" value="{{ isset($user) ? $user->last_name : old('last_name') }}" required>
                        @if($errors->has('last_name'))
                            <p class="help-block">{{ $errors->first('last_name') }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ isset($user) ? $user->email : old('email') }}" required>
                        @if($errors->has('email'))
                            <p class="help-block">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('group_id') ? ' has-error' : '' }}">
                        <label for="group">Group</label>
                        <select name="group_id" class="form-control" required>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ (isset($user) AND $user->group_id == $group->id) ? "selected" : "" }}>
                                    {{ $group->title }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('group'))
                            <p class="help-block">{{ $errors->first('group') }}</p>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" value="">
                        @if($errors->has('password'))
                            <p class="help-block">{{ $errors->first('password') }}</p>
                        @endif
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