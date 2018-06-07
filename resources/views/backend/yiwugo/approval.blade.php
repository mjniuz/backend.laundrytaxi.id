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
                {{ Form::open(['url' => url('backend/yiwugo/approval-save/' . $order->id), 'autocomplete' => 'off', 'files' => true ]) }}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <h4>Order Detail</h4>
                            <div class="pull-right">
                                <a href="{{ url('backend/yiwugo/order-detail/' . $order->id) }}" class="btn btn-sm btn-primary">
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
                                        <select class="form-control" name="order_status" required>
                                            <option value="PENDING" {{ ( $order->order_status == 'PENDING') ? 'selected' : '' }}>Pending</option>
                                            <option value="PRICE_LIST" {{ ( $order->order_status == 'PRICE_LIST') ? 'selected' : '' }}>Price List</option>
                                            <option value="ON_PROGRESS" {{ ( $order->order_status == 'ON_PROGRESS') ? 'selected' : '' }}>On Delivery</option>
                                            <option value="HOLD" {{ ( $order->order_status == 'HOLD') ? 'selected' : '' }}>Hold</option>
                                            <option value="SUCCESS" {{ ( $order->order_status == 'SUCCESS') ? 'selected' : '' }}>Success</option>
                                            <option value="EXPIRED" {{ ( $order->order_status == 'EXPIRED') ? 'selected' : '' }}>Expired</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Products</h4>
                        <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>
                                    Name
                                </th>
                                <th>
                                    From
                                </th>
                                <th>
                                    Note
                                </th>
                                <th>
                                    Status
                                </th>
                                <th>
                                    Weight
                                </th>
                                <th>
                                    CBM
                                </th>
                                <th>
                                    Price
                                </th>
                                <th>
                                    Admin Fee
                                </th>
                                <th>
                                    Sub
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order->yg_order_detail as $detail)
                                <tr>
                                    <td>
                                        <a href="{{ url('product-detail/' . $detail->yg_product_id) }}" target="_blank">
                                            {{ $detail->product_title }}
                                        </a>
                                        <p class="red-bg">
                                            {{ $detail->product_note }}
                                        </p>
                                    </td>
                                    <td>
                                        {{ $detail->yg_product_detail->flag }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-xs btn-primary add-note" data-id="{{ $detail->id }}">
                                            <i class="fa fa-pencil"></i> Note
                                        </button>
                                        <input type="hidden" name="detail[{{$detail->id}}][admin_note]" id="admin-note-{{ $detail->id }}" value="{{ $detail->admin_note }}">
                                    </td>
                                    <td>
                                        <select class="form-control" name="detail[{{$detail->id}}][status]" required>
                                            <option value="pending" {{ ( $detail->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                            <option value="price_list" {{ ( $detail->status == 'price_list') ? 'selected' : '' }}>Price List</option>
                                            <option value="on_delivery" {{ ( $detail->status == 'on_delivery') ? 'selected' : '' }}>On Delivery</option>
                                            <option value="hold" {{ ( $detail->status == 'hold') ? 'selected' : '' }}>Hold</option>
                                            <option value="success" {{ ( $detail->status == 'success') ? 'selected' : '' }}>Success</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control number count" data-type="weight" name="detail[{{$detail->id}}][weight]" value="{{ number_format($detail->weight,0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control number count" data-type="cbm" name="detail[{{$detail->id}}][cbm]" value="{{ number_format($detail->cbm,0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control number count product-{{ $detail->id }}" data-id="{{ $detail->id }}" data-type="price-product" name="detail[{{$detail->id}}][price_actual_all_product]" value="{{ number_format($detail->price_actual_all_product,0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control number count fee-{{ $detail->id }}" data-id="{{ $detail->id }}" data-type="admin-fee" name="detail[{{$detail->id}}][admin_fee]" value="{{ number_format($detail->admin_fee,0) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control number sub-{{ $detail->id }}" data-type="sub" value="" readonly>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4">
                                    Grand Total
                                </td>
                                <td>
                                    <input type="text" id="g-weight" class="form-control number" value="" readonly>
                                </td>
                                <td>
                                    <input type="text" id="g-cbm" class="form-control number" value="" readonly>
                                </td>
                                <td>
                                    <input type="text" id="g-price" class="form-control number" value="" readonly>
                                </td>
                                <td>
                                    <input type="text" id="g-admin_fee" class="form-control number" value="" readonly>
                                </td>
                                <td>
                                    <input type="text" id="g-sub" class="form-control number" value="" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8">
                                    &nbsp;
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
                <div id="modal-note" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">
                                    Admin Product Note
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="box-body pad">
                                    <div class="form-group">
                                        <textarea id="product-note-modal" name="admin_product_note" class="form-control" value="" data-id=""></textarea>
                                    </div>
                                    <button type="button" id="close-modal-product-note" class="btn btn-success">
                                        Submit
                                    </button>
                                </div>
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
@endsection