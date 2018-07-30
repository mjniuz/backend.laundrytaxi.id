@extends('backend')
@section('page_header')
@endsection
@section('js')
    <script src="{{ asset("js/backend.merchant.js?v=1.0") }}"></script>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <div class="pull-right">
                                <a href="{{ url('backend/order') }}" class="btn btn-sm btn-warning">
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
                        <h4>Merchant Detail</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td><strong>Name</strong></td>
                                    <td valign="top">{{ $merchant->name  }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Merchant Phone</strong></td>
                                    <td valign="top">{{ $merchant->real_phone  }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cs Phone</strong></td>
                                    <td valign="top">{{ $merchant->phone  }}</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Balance</strong></td>
                                    <td valign="top">
                                        Rp {{ number_format($merchant->balance,0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Quality Score</strong></td>
                                    <td valign="top">
                                        {{ $merchant->score }}
                                    </td>
                                </tr>
                                @if(!is_null($merchant->suspended_at))
                                    <tr>
                                        <td valign="top"><strong>Suspend</strong></td>
                                        <td valign="top">
                                            {{ $merchant->suspended_at }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><strong>Unsuspend Schedule</strong></td>
                                        <td valign="top">
                                            {{ $merchant->unsuspend_schedule }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><strong>Suspend Message</strong></td>
                                        <td valign="top">
                                            {{ $merchant->suspend_message }}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Image</strong></td>
                                    <td valign="top"><img src="{{ $merchant->avatar  }}" class="img img-thumbnail"/></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <h4>
                            Balance History
                            <a href="{{ url('backend/merchant/balances?merchant_id=' . $merchant->id) }}" class="btn btn-xs btn-primary pull-right">
                                <i class="fa fa-list"></i> List All
                            </a>
                            <a href="#" class="btn btn-xs btn-success pull-right" data-toggle="modal" data-target="#modal-add-transaction">
                                <i class="fa fa-plus"></i> Add Transaction
                            </a>
                        </h4>
                        <div class="table-responsive" style="overflow:auto;max-height: 300px;">
                            <table class="table table-bordered table-hover table-striped">
                                <thead>
                                <tr>
                                    <td>
                                        ID
                                    </td>
                                    <td>
                                        Type
                                    </td>
                                    <td>
                                        Amount
                                    </td>
                                    <td>
                                        Message
                                    </td>
                                    <td>
                                        By
                                    </td>
                                    <td>
                                        #
                                    </td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($merchant->balance_histories as $item)
                                    <tr>
                                        <td>
                                            {{ $item->id }}
                                        </td>
                                        <td>
                                            {{ $item->in_out }}
                                        </td>
                                        <td>
                                            {{ number_format($item->amount,0) }}
                                        </td>
                                        <td>
                                            {{ $item->message }}
                                        </td>
                                        <td>
                                            <a href="{{ url('backend/administrator/edit/' . $item->admin_user_id) }}" class="btn btn-xs btn-primary">
                                                <i class="fa fa-user"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-xs btn-primary modal-detail-transaction" data-dump="{{ json_encode($item) }}">
                                                <i class="fa fa-search-plus"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
                                    Delivered
                                </th>
                                <th>
                                    Detail
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($merchant->orders as $order)
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
                                        {{ $order->deliverd_at }}
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

    <div id="modal-detail-transaction" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-add-tracking-title">
                        Detail Transaction ID <span id="transaction-id"></span>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="box-body pad">
                        <div class="form-group">
                            <label for="amount">Current Balance</label>
                            <input class="form-control number" value="{{ number_format($merchant->balance,0) }}" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="amount_value">By Admin</label>
                            <br/>
                            <a href="" id="admin_url_value" target="_blank">Admin Detail</a>
                        </div>
                        <div class="form-group">
                            <label for="order_value">Order Invoice</label>
                            <br/>
                            <a href="" id="order_value" target="_blank">Invoice ID</a>
                        </div>
                        <div class="form-group">
                            <label for="in_out_value">Type</label>
                            <input class="form-control" value="" id="in_out_value" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="amount_value">Amount (IDR)</label>
                            <input name="amount_value" class="form-control number" id="amount_value" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="balance_total_value">Balance Total (IDR)</label>
                            <input name="balance_total_value" class="form-control number" id="balance_total_value" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="message_value">Message</label>
                            <input name="message_value" class="form-control" id="message_value" readonly/>
                        </div>
                        <div class="form-group">
                            <label for="created_at_value">Created at</label>
                            <input name="created_at_value" class="form-control" id="created_at_value" readonly/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-add-transaction" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modal-add-tracking-title">
                        Add Transaction
                    </h4>
                </div>
                <div class="modal-body">
                    {{ Form::open(['url' => url('backend/merchant/add-transaction/' . $merchant->id),
                        'class' => 'form',
                        'autocomplete'  => 'off',
                        'method'    => 'post']) }}
                    <div class="box-body pad">
                        <div class="form-group">
                            <label for="amount">Current Balance</label>
                            <input class="form-control number" value="{{ number_format($merchant->balance,0) }}" readonly/>
                        </div>
                        <div class="form-group{{ $errors->has('in_out') ? ' has-error' : '' }}">
                            <label for="tracking">Type</label>
                            <select name="in_out" class="form-control" required>
                                <option value="">Pilih Opsi</option>
                                <option value="in">Uang masuk</option>
                                <option value="out">Uang Keluar</option>
                            </select>
                            @if($errors->has('in_out'))
                                <p class="help-block">{{ $errors->first('in_out') }}</p>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount">Amount (IDR)</label>
                            <input name="amount" class="form-control number" id="amount" required/>
                            @if($errors->has('amount'))
                                <p class="help-block">{{ $errors->first('amount') }}</p>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                            <label for="status">Message</label>
                            <input name="message" class="form-control" id="message" required/>
                            @if($errors->has('message'))
                                <p class="help-block">{{ $errors->first('message') }}</p>
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
@endsection