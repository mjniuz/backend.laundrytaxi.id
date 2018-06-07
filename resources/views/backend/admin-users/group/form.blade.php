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
	Group Form
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
                        Group Form
                    </h3>
                </div>
                @if(isset($group))
                    {{ Form::open(['url' => url('backend/administrator/group/update/' . $group->id), 'autocomplete' => 'off']) }}
                @else
                    {{ Form::open(['url' => url('backend/administrator/group/save'), 'autocomplete' => 'off' ]) }}
                @endif
                <div class="box-body pad">
                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                        <label for="title">Title</label>
                        <input type="text" name="title" class="form-control" placeholder="title" value="{{ isset($group) ? $group->title : old('title') }}" required>
                        @if($errors->has('title'))
                            <p class="help-block">{{ $errors->first('title') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                        <label for="slug">Slug</label>
                        <input type="text" name="slug" class="form-control" placeholder="slug" value="{{ isset($group) ? $group->slug : old('slug') }}" required>
                        @if($errors->has('slug'))
                            <p class="help-block">{{ $errors->first('slug') }}</p>
                        @endif
                    </div>
                <div class="box-footer pull-right">
                    @if(isset($group))
                        <a href="{{ url('backend/administrator/group/delete/' . $group->id) }}" class="btn btn-danger">
                            Delete
                        </a>
                    @endif
                    <a href="{{ url('backend/administrator/group') }}" class="btn btn-primary">
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