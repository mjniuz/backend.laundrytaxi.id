@extends('backend')
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.css">
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.5.2/select2.min.js"></script>
    <script src="{{ asset('js/backend.permission.js') }}"></script>
@endsection
@section('page_header')
    <h4>
	Permission Form
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
                        Permission Form
                    </h3>
                </div>
                @if(isset($permission))
                    {{ Form::open(['url' => url('backend/administrator/permission/update/' . $permission->id), 'autocomplete' => 'off', 'files' => true ]) }}
                @else
                    {{ Form::open(['url' => url('backend/administrator/permission/save'), 'autocomplete' => 'off', 'files' => true ]) }}
                @endif
                <div class="box-body pad">
                    <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
                        <label for="parent-list">Parent</label>
                        <br />
                        <input type="hidden" name="parent_id" id="parent-list" data-array="{{ json_encode($permissionList) }}" />
                        @if($errors->has('parent_id'))
                            <p class="help-block">{{ $errors->first('parent_id') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="name" value="{{ isset($permission) ? $permission->name : old('name') }}" required>
                        @if($errors->has('name'))
                            <p class="help-block">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                <div class="box-footer pull-right">
                    @if(isset($permission))
                        <a href="{{ url('backend/administrator/permission/delete/' . $permission->id) }}" class="btn btn-danger">
                            Delete
                        </a>
                    @endif
                    <a href="{{ url('backend/administrator/permission') }}" class="btn btn-primary">
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