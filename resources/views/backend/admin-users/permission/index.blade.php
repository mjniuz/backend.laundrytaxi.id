@extends('backend')
@section('page_header')
    <h4>Role Permission</h4>
    <div class="pull-right">
        <a href="{{ url('backend/administrator/permission/create') }}" class="btn btn-sm btn-success">
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
                {!! $permissionList !!}
            </div>        
        </div>
        </div>
        </div>
        </div>
    </div>
@endsection