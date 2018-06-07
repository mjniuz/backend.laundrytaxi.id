@extends('backend')
@section('page_header')
    <h4>Discussion List</h4>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <div class="pull-right">
                                {{--<a href="{{ url('backend/promo-code/form') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-pencil"></i> Create
                                </a>--}}
                            </div>
                        </div>
                        <div class="box-body">
                            {{ Form::open(['url' => url('backend/crowdfund/discuss'), 'method' => 'get']) }}
                            <div class="row gutter-10">
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Title</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['title']) ? $filters['title'] : "" }}" name="title"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-addon">Product Name</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['product_name']) ? $filters['product_name'] : "" }}" name="product_name"/>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    {{--<div class="input-group">
                                        <span class="input-group-addon">Deadline </span>
                                        <input type="text" class="form-control date" value="{{ isset($filters['deadline']) ? $filters['deadline'] : "" }}" name="deadline"/>
                                    </div>--}}
                                </div>
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right input-group">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i> Search
                                        </button>
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
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Comment</th>
                                        <th>Product</th>
                                        <th>Date</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($discussions as $discussion)
                                        <tr>
                                            <td>{{ $discussion->id }}</td>
                                            <td title="{{ $discussion->user->marking_code }}">{{ $discussion->user->name }}</td>
                                            <td>{{ $discussion->content }}</td>
                                            <td>{{ $discussion->product->title }}</td>
                                            <td>{{ $discussion->created_at }}</td>
                                            <td>
                                                <a href="{{ url('backend/crowdfund/product-detail/' . $discussion->product->id . '#discuss') }}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-search"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                @if(isset($discussions))
                                    {!! $discussions->appends(Input::except('page'))->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection