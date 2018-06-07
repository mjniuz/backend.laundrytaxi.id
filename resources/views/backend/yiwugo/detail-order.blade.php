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
                                <a href="{{ url('backend/yiwugo/approval/' . $order->id) }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-check"></i> Create Quote
                                </a>
                                <a href="{{ url('backend/yiwugo/payment/' . $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="fa fa-money"></i> Update Payment
                                </a>
                                <a href="{{ url('backend/yiwugo/order') }}" class="btn btn-sm btn-primary">
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
                    <div class="col-lg-6">
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
                                        <a href="{{ url('backend/org-users/detail/' . $order->user_id) }}" target="_blank">
                                            {{ $order->user->name }} / {{ $order->user->phone }} / {{ $order->user->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Status Order</strong></td>
                                    <td valign="top">
                                        {{ $order->order_status }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td>
                                        Product Price
                                    </td>
                                    <td>{{ number_format($order->product_price, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Delivery Fee
                                    </td>
                                    <td>{{ number_format($order->delivery_fee, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Unique Amount
                                    </td>
                                    <td>{{ number_format($order->unique_amount, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Total Price
                                    </td>
                                    <td>{{ number_format($order->total_price, 0) }}</td>
                                </tr>
                                <tr>
                                    <td valign="top">Status Payment</td>
                                    <td valign="top">
                                        @if($order->payment_status == "PAID")
                                            <span class="label label-success">{{ $order->payment_status }}</span>
                                        @else
                                            <span class="label label-danger">{{ $order->payment_status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($order->payment_status != "PAID")
                                    <tr>
                                        <td valign="top">Expired At</td>
                                        <td valign="top">
                                            {{ $order->expired_at }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td valign="top">Payment Method</td>
                                    <td valign="top">
                                        {{ $order->payment_method }}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top">Payment Confirmation</td>
                                    <td valign="top">
                                        @if($order->confirmation_note == '')
                                            Belum Konfirmasi
                                        @else
                                            <a href="{{ $order->confirmation_file }}" class="btn btn-xs btn-primary" target="_blank">
                                                <i class="fa fa-file"></i> File
                                            </a>
                                            <p>
                                                {{ $order->confirmation_note }}
                                            </p>
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
                                    Name
                                </th>
                                <th>
                                    Image
                                </th>
                                <th>
                                    Qty
                                </th>
                                <th>
                                    Weight / CBM
                                </th>
                                <th>
                                    Price Est
                                </th>
                                <th>
                                    Price Actual
                                </th>
                                <th>
                                    Adm Fee
                                </th>
                                <th>
                                    Deliv Fee
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->yg_order_detail as $detail)
                                <tr>
                                    <td>
                                        @if($detail->flag == 'yiwugo')
                                            <a href="{{ url('product-detail/' . $detail->yg_product_id) }}" class="" target="_blank">
                                                {{ $detail->product_title }}
                                            </a>
                                        @else
                                            <a href="{{ url('1688/product-detail/' . $detail->yg_product_id) }}" class="" target="_blank">
                                                {{ $detail->product_title }}
                                            </a>
                                        @endif
                                        {{--<a href="{{ url('yiwugo/product-detail/' . $detail->yg_product_id) }}" target="_blank">

                                        </a>--}}
                                        <p class="red-bg">
                                            {{ $detail->product_note }}
                                        </p>
                                    </td>
                                    <td>
                                        <img src="{{ $detail->product_image }}" class="img-thumbnail img" width="150px"/>
                                    </td>
                                    <td>
                                        {{ number_format($detail->quantity,0) }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->weight,0) }} / {{ number_format($detail->cbm,0) }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->price_estimate_per_product,0) }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->price_actual_all_product,0) }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->admin_fee,0) }}
                                    </td>
                                    <td>
                                        {{ number_format($detail->delivery_fee,0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ $detail->delivery_name }}
                                    </td>
                                    <td>
                                        {{ $detail->full_address }}
                                    </td>
                                    <td>
                                        Tracking : {{ $detail->tracking }} <button type="button" class="btn btn-xs btn-primary tracking-modal" data-note="{{ $detail->tracking_note }}" data-value="{{ $detail->tracking }}" data-id="{{ $detail->id }}"><i class="fa fa-pencil"></i></button>
                                    </td>
                                    <td colspan="4">
                                        Tracking Note : {{ $detail->tracking_note }}
                                    </td>
                                </tr>
                                @if($detail->yg_product_detail->supplier_info->from == 'yiwugo')
                                @php
                                    $productDetail  = $detail->yg_product_detail->supplier_info;
                                @endphp
                                <tr>
                                    <td>
                                        Shop Name : {{ $productDetail->name }}
                                    </td>
                                    <td>
                                        Phone : {{ $productDetail->phone }}
                                    </td>
                                    <td>
                                        Email : {{ $productDetail->email }}
                                    </td>
                                    <td>
                                        Mobile : {{ $productDetail->mobile }}
                                    </td>
                                    <td colspan="4">
                                        <ul>
                                            @foreach($productDetail->info as $key => $info)
                                                @if(!empty($info))
                                                    <li>{{ $key }} : {{ $info }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                                @endif
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