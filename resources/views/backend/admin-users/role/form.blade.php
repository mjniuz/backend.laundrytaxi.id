@extends('backend')
@section('page_header')
    <h4>
	Role Form
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
                        Role Form
                    </h3>
                </div>
                @if(isset($role))
                    {{ Form::open(['url' => url('backend/administrator/role/update/' . $role->id), 'autocomplete' => 'off', 'files' => true ]) }}
                @else
                    {{ Form::open(['url' => url('backend/administrator/role/save'), 'autocomplete' => 'off', 'files' => true ]) }}
                @endif
                <div class="box-body pad">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="name" value="{{ isset($role) ? $role->name : old('name') }}" required>
                        @if($errors->has('name'))
                            <p class="help-block">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="slug" value="{{ isset($role) ? $role->slug : old('slug') }}" required>
                        @if($errors->has('slug'))
                            <p class="help-block">{{ $errors->first('slug') }}</p>
                        @endif
                    </div>
                <div class="box-footer pull-right">
                    <a href="{{ url('backend/administrator/role') }}" class="btn btn-default">
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