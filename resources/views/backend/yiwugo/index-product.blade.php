@extends('backend')
@section('page_header')
    <h4>Products List</h4>
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
                            {{ Form::open(['url' => url('backend/yiwugo'), 'method' => 'get']) }}
                            <div class="row gutter-10">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Title</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['title']) ? $filters['title'] : "" }}" name="title"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Description</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['description']) ? $filters['description'] : "" }}" name="description"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">HS Code</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['hs_code']) ? $filters['hs_code'] : "" }}" name="hs_code"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Status</span>
                                        <select class="form-control" name="status">
                                            <option value="" {{ (isset($filters['status']) AND $filters['status'] == '') ? 'selected' : '' }}>All</option>
                                            <option value="pending" {{ (isset($filters['status']) AND $filters['status'] == 'pending') ? 'selected' : '' }}>Pending</option>
                                            <option value="reject" {{ (isset($filters['status']) AND $filters['status'] == 'reject') ? 'selected' : '' }}>Reject</option>
                                            <option value="ready" {{ (isset($filters['status']) AND $filters['status'] == 'ready') ? 'selected' : '' }}>Ready</option>
                                            <option value="in_progress" {{ (isset($filters['status']) AND $filters['status'] == 'in_progress') ? 'selected' : '' }}>In Progress</option>
                                            <option value="done" {{ (isset($filters['status']) AND $filters['status'] == 'done') ? 'selected' : '' }}>Done</option>
                                        </select>
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
                                        <th>Image</th>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>From</th>
                                        <th>Price Range</th>
                                        <th>Created</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ $product->image }}" width="40px" class="img thumbnail"/>
                                            </td>
                                            <td>{{ $product->product_id }}</td>
                                            <td>{{ $product->title }}</td>
                                            <td>{{ $product->flag }}</td>
                                            <td>{{ $product->price_fix }}</td>
                                            <td>{{ $product->created_at }}</td>
                                            <td>
                                                @if($product->flag == 'yiwugo')
                                                    <a href="{{ url('product-detail/' . $product->product_id) }}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-search"></i> Detail
                                                    </a>
                                                @else
                                                    <a href="{{ url('1688/product-detail/' . $product->product_id) }}" class="btn btn-xs btn-primary">
                                                        <i class="fa fa-search"></i> Detail
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                @if(isset($products))
                                    {!! $products->appends(Input::except('page'))->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection