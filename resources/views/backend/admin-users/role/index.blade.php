@extends('backend')
@section('page_header')
    <h4>User Role</h4>
    <div class="pull-right">
        <a href="{{ url('backend/administrator/role/create') }}" class="btn btn-sm btn-success">
            <i class="fa fa-pencil"></i> Create
        </a>
    </div>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
         <div class="panel-body">
	    <div class="row">
        <div class="col-lg-12">
            <div class="box-body">

            </div>
        </div>
        <div class="col-lg-12">
            @if(Session::has('status'))
                <p class="alert alert-{{ Session::get('alert-class', 'info') }}">{{ Session::get('status') }}</p>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->slug }}</td>
                                <td>
                                    <a href="{{ url('backend/administrator/role/edit/'. $role->id) }}" class="btn btn-xs btn-primary">Edit</a>
                                    <a href="{{ url('backend/administrator/role/permission/'. $role->id) }}" class="btn btn-xs btn-warning">Permission</a>
                                    <a href="{{ url('backend/administrator/role/delete/'. $role->id) }}" class="btn btn-xs btn-danger btn-delete">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    @if(isset($roles))
                        {!! $roles->appends(Input::except('page'))->links() !!}
                    @endif
                </div>
            </div>        
        </div>
        </div>
        </div>
        </div>
    </div>
@endsection