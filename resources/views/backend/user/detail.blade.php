@extends('backend')
@section('page_header')
@endsection
@section('js')
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <h4>User Detail</h4>
                            <div class="pull-right">
                                <a href="{{ url('backend/order') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-reply-all"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if(Session::has('status'))
                            <p class="alert alert-{{ Session::get('alert-class', 'info') }}">{{ Session::get('status') }}</p>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td valign="top">{{ $user->name  }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td valign="top">{{ $user->phone  }}</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Active At</strong></td>
                                    <td valign="top">
                                        {{ $user->activated }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-8">
                    </div>
                    <div class="col-md-12">
                        <hr/>
                        <h4>Orders</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Inv
                                </th>
                                <th>
                                    Weight
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Detail
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($user->orders as $order)
                                <tr>
                                    <td>
                                        {{ $order->invoice_no }}
                                    </td>
                                    <td>
                                        @if(!empty($order->actual_weight))
                                            {{ $order->actual_weight }}
                                        @else
                                            {{ $order->estimate_weight }}
                                        @endif
                                    </td>
                                    <td>
                                        {!! $order->status_text !!}
                                    </td>
                                    <td>
                                        <a href="{{ url('backend/order/detail/' . $order->id) }}" class="btn btn-xs btn-primary">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped">
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection