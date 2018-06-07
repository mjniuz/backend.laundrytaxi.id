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
                            {{ Form::open(['url' => url('backend/order'), 'method' => 'get']) }}
                            <div class="row gutter-10">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Invoice</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['invoice_no']) ? $filters['invoice_no'] : "" }}" name="invoice_no"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Name</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['name']) ? $filters['name'] : "" }}" name="name"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Phone</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['phone']) ? $filters['phone'] : "" }}" name="phone"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Status</span>
                                        <select class="form-control" name="status">
                                            <option value="" {{ (isset($filters['status']) AND $filters['status'] == '') ? 'selected' : '' }}>All</option>
                                            <option value="rejected" {{ (isset($filters['status']) AND $filters['status'] == 'rejected') ? 'selected' : '' }}>Rejected</option>
                                            <option value="approved" {{ (isset($filters['status']) AND $filters['status'] == 'approved') ? 'selected' : '' }}>Approved</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Process On</span>
                                        <select class="form-control" name="process">
                                            <option value="" {{ (isset($filters['process']) AND $filters['process'] == '') ? 'selected' : '' }}>All</option>
                                            <option value="pickup_at" {{ (isset($filters['process']) AND $filters['process'] == 'pickup_at') ? 'selected' : '' }}>Picked up</option>
                                            <option value="process_at" {{ (isset($filters['pickup_at']) AND $filters['pickup_at'] == 'process_at') ? 'selected' : '' }}>Process</option>
                                            <option value="delivered_at" {{ (isset($filters['pickup_at']) AND $filters['pickup_at'] == 'delivered_at') ? 'selected' : '' }}>Delivered</option>
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
                                        <th>Invoice</th>
                                        <th>Name</th>
                                        <th>Weight</th>
                                        <th>Package</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $order->invoice_no }}</td>
                                            <td title="{{ $order->user->phone }}">{{ $order->full_name }} {{ $order->user->phone }}</td>
                                            <td>{{ ($order->actual_weight > 0) ? number_format($order->actual_weight,0) : number_format($order->estimate_weight,0) }}</td>
                                            <td>{{ ucfirst($order->package) }}</td>
                                            <td>{{ number_format($order->grand_total, 0) }}</td>
                                            <td>{!! $order->status_text !!}</td>
                                            <td>{{ $order->updated_at }}</td>
                                            <td>
                                                <a href="{{ url('backend/order/detail/' . $order->id) }}" class="btn btn-xs btn-primary">
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