@extends('backend')
@section('page_header')
    <h4>Admin Group</h4>
    <div class="pull-right">
        <a href="{{ url('backend/administrator/group/create') }}" class="btn btn-sm btn-success">
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
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Shipping List</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>{{ $group->id }}</td>
                            <td>{{ $group->title }}</td>
                            <td>{{ $group->slug }}</td>
                            <td>
                                <a href="{{ url('backend/shipping?group_id=' . $group->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-ship"></i> Detail
                                </a>
                            </td>
                            <td>
                                <a href="{{ url('backend/administrator/group/edit/'. $group->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <a href="{{ url('backend/administrator/group/delete/'. $group->id) }}" class="btn btn-sm btn-danger btn-delete">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    @if(isset($groups))
                        {!! $groups->appends(Input::except('page'))->links() !!}
                    @endif
                </div>
            </div>
        </div>
        </div>
        </div>
        </div>
    </div>
@endsection