@extends('backend')
@section('page_header')
    <h4>Order List</h4>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <div class="pull-right">

                            </div>
                        </div>
                        <div class="box-body">
                            {{ Form::open(['url' => url('backend/crowdfund/order-confirmed'), 'method' => 'get']) }}
                            <div class="row gutter-10">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Title</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['title']) ? $filters['title'] : "" }}" name="title"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Order ID</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['order_id']) ? $filters['order_id'] : "" }}" name="order_id"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">User Name</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['name']) ? $filters['name'] : "" }}" name="name"/>
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
                                        <th>Name</th>
                                        <th>Invoice ID</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Confirm Note</th>
                                        <th>Created</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td title="{{ $order->user->marking_code }}">{{ $order->user->name }}</td>
                                            <td>{{ $order->invoice_no }}</td>
                                            <td>{{ ucfirst($order->status_order) }}</td>
                                            <td>{{ number_format($order->total_price, 0) }}</td>
                                            <td>{{ $order->confirmation_note }}</td>
                                            <td>{{ $order->created_at }}</td>
                                            <td>
                                                <a href="{{ url('backend/crowdfund/order-detail/' . $order->id) }}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-search"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                @if(isset($orders))
                                    {!! $orders->appends(Input::except('page'))->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection