@extends('backend')
@section('page_header')
    <h4>Admin User</h4>
    <div class="pull-right">
        <a href="{{ url('backend/administrator/create') }}" class="btn btn-sm btn-success">
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
                {{ Form::open(['url' => url('backend/backend-user'), 'method' => 'get']) }}
                <div class="row gutter-10">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Name</span>
                            <input type="text" class="form-control" value="{{ isset($filters['full_name']) ? $filters['full_name'] : "" }}" name="full_name"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-addon">Email</span>
                            <input type="text" class="form-control" value="{{ isset($filters['email']) ? $filters['email'] : "" }}" name="email"/>
                        </div>
                    </div>
                    <div class="col-md-2 pull-right">
                        <div class="pull-right input-group">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @foreach($user->role as $role)
                                        {{ $role->role->name . ", "}}
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ url('backend/administrator/edit/'. $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ url('backend/administrator/role/user/'. $user->id) }}" class="btn btn-sm btn-warning">Role</a>
                                    <a href="{{ url('backend/administrator/delete/'. $user->id) }}" class="btn btn-sm btn-danger btn-delete">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    @if(isset($users))
                        {!! $users->appends(Input::except('page'))->links() !!}
                    @endif
                </div>
            </div>        
        </div>
        </div>
        </div>
        </div>
    </div>
@endsection