@extends('backend')
@section('page_header')
@endsection
@section('js')
    <script src="{{ asset("js/backend.yiwugo.js?v=1") }}"></script>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <h4>Order Detail</h4>
                            <div class="pull-right">
                                    @if($order->status == 'approved')
                                        <a href="{{ url('backend/order/reject/' . $order->id) }}" class="btn btn-sm btn-danger">
                                            <i class="fa fa-close"></i> Reject Form
                                        </a>
                                    @endif
                                    @if(is_null($order->pickup_at))
                                        <a href="{{ url('backend/order/update/' . $order->id . '?status=pickup_at') }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i> Set Pickup
                                        </a>
                                    @endif
                                    @if(is_null($order->process_at) && !is_null($order->pickup_at))
                                        <a href="{{ url('backend/order/update/' . $order->id . '?status=process_at') }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i> Set Process
                                        </a>
                                    @endif
                                    @if(is_null($order->delivered_at) && !is_null($order->process_at))
                                        <a href="{{ url('backend/order/update/' . $order->id . '?status=delivered_at') }}" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i> Set Delivered
                                        </a>
                                     @endif
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
                                    <td><strong>Invoice</strong></td>
                                    <td valign="top">{{ $order->invoice_no }}</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>User</strong></td>
                                    <td valign="top">
                                        <a href="{{ url('backend/user/detail/' . $order->user_id) }}" target="_blank">
                                            {{ $order->full_name }} / {{ $order->phone }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Order Note</strong></td>
                                    <td valign="top">
                                        {{ $order->note }}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Address</strong></td>
                                    <td valign="top">
                                        {{ $order->full_address }}
                                        <br/>
                                        {{ $order->address_note }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <iframe width="100%" height="250" frameborder="0" style="border:0" src="https://maps.google.com/maps?q={{ $order->lat }}, {{ $order->lng }}&z=13&output=embed"></iframe>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td valign="top"><strong>Status Order</strong></td>
                                    <td valign="top">
                                        @if($order->status == 'rejected')
                                            <span class="label label-danger">{{ $order->status }}</span>
                                            <br/>
                                            {{ $order->success_comment }}
                                        @endif
                                        @if($order->status == 'approved')
                                            <span class="label label-success">{{ $order->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Package
                                    </td>
                                    <td>{{ $order->package }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Weight Estimate / Actual
                                    </td>
                                    <td>
                                        {{ number_format($order->estimate_weight, 0) }} /
                                        {{ number_format($order->actual_weight, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Count Total
                                    </td>
                                    <td>{{ number_format($order->actual_count, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Package Name
                                    </td>
                                    <td>{{ $package['name'] }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Package Desc
                                    </td>
                                    <td>{{ $package['description'] }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Price/kg
                                    </td>
                                    <td>{{ number_format($package['price_per_kg'],0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        max kg/after max
                                    </td>
                                    <td>{{ number_format($package['max'],0). ' / ' . number_format($package['after_max'],0) }}</td>
                                </tr>
                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <td>
                                        Created
                                    </td>
                                    <td>
                                        Pickup
                                    </td>
                                    <td>
                                        Process
                                    </td>
                                    <td>
                                        Delivered
                                    </td>
                                </tr>
                                </thead>
                                <tbody class="text-center">
                                <tr>
                                    <td>
                                        @if(!is_null($order->created_at))
                                            <i class="fa fa-check" data-toggle="tooltip" title="{{ $order->created_at }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($order->pickup_at))
                                            <i class="fa fa-check" data-toggle="tooltip" title="{{ $order->pickup_at }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($order->process_at))
                                            <i class="fa fa-check" data-toggle="tooltip" title="{{ $order->process_at }}"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!is_null($order->delivered_at))
                                            <i class="fa fa-check" data-toggle="tooltip" title="{{ $order->delivered_at }}"></i>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr/>
                        <h4>Products</h4>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Description
                                </th>
                                <th>
                                    Sub
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($details as $detail)
                                <tr>
                                    <td>
                                        {{ $detail['title'] }}
                                    </td>
                                    <td>
                                        {{ $detail['price_text'] }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>
                                    Grand Total
                                </th>
                                <td>
                                    {{ number_format($order->grand_total,0) }}
                                </td>
                            </tr>
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
                <div class="pull-right">
                    <div id="modal-add-tracking" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title" id="modal-add-tracking-title">
                                        Tracking Note
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    {{ Form::open(['url' => url('backend/yiwugo/order-add-tracking/'),
                                        'class' => 'form',
                                        'autocomplete'  => 'off',
                                        'method'    => 'get']) }}
                                    <div class="box-body pad">
                                        <input type="hidden" name="delivery_id" class="form-control" value="">
                                        <div class="form-group{{ $errors->has('tracking') ? ' has-error' : '' }}">
                                            <label for="tracking">Tracking Number</label>
                                            <input type="text" name="tracking" class="form-control" placeholder="Tracking Number" value="" id="tracking" required>
                                            <input type="hidden" name="id" value="" id="product-id">
                                        @if($errors->has('tracking'))
                                                <p class="help-block">{{ $errors->first('tracking') }}</p>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('tracking_note') ? ' has-error' : '' }}">
                                            <label for="status">Note</label>
                                            <textarea name="tracking_note" class="form-control" id="tracking_note"></textarea>
                                            @if($errors->has('tracking_note'))
                                                <p class="help-block">{{ $errors->first('tracking_note') }}</p>
                                            @endif
                                        </div>
                                        <button type="submit" class="btn btn-success confirm">
                                            Submit
                                        </button>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection