@extends('backend')
@section('page_header')
    <h4>Product Detail</h4>
@endsection
@section('js')
    <script src="{{ asset("js/backend.crowdfund.js?v=1") }}"></script>
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <div class="pull-right">
                                <a href="{{ url('backend/crowdfund') }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-reply-all"></i> Back
                                </a>

                                @if($product->approval_text != 'Approved')
                                <a href="{{ url('backend/crowdfund/approval/approve/' . $product->id) }}" class="btn btn-sm btn-success btn-confirm" data-confirm="Anda akan menerima product ini sebagai crowdfund">
                                    <i class="fa fa-check"></i> Approve
                                </a>
                                @endif
                                @if($product->approval_text != 'Reject')
                                    <button data-toggle="modal" data-target="#modal-reject-message" class="btn btn-sm btn-warning">
                                        <i class="fa fa-close"></i> Reject
                                    </button>
                                    {{--<a href="{{ url('backend/crowdfund/delete-product/' . $product->id) }}" class="btn btn-sm btn-danger delete">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>--}}
                                @endif
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
                                    <td><strong>Shipping</strong></td>
                                    <td>
                                        <a href="{{ url('backend/shipping/detail/' . $product->shipping_id) }}" target="_blank">
                                            {{ $product->shipping->invoice_no }} <i class="fa fa-external-link"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>User</strong></td>
                                    <td valign="top">
                                        <a href="{{ url('backend/org-users/detail/' . $product->user_id) }}" target="_blank">
                                            {{ $product->user->name }} / {{ $product->user->phone }} / {{ $product->user->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Title</strong></td>
                                    <td valign="top">{{ $product->title }}</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Image</strong></td>
                                    <td valign="top">
                                        <img src="{{ $product->image_link }}" class="img-thumbnail img" width="150px"/>
                                        <hr/>
                                        <div class="row">
                                        @foreach($product->options as $option)
                                            @if($option->key == 'Image')
                                                <div class="col-md-3">
                                                    <img src="{{ $option->image_link }}" class="img-thumbnail img"/>
                                                </div>
                                            @endif
                                        @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Desc</strong></td>
                                    <td valign="top">{!! $product->description !!}</td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Options</strong></td>
                                    <td valign="top">
                                        <div class="row">
                                            @foreach($product->options as $option)
                                                @if($option->key != 'Image')
                                                    <div class="col-md-6">
                                                        {{ $option->key }}
                                                    </div>
                                                    <div class="col-md-6">
                                                        {{ $option->value }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Freight</strong></td>
                                    <td>{{ $product->freight->title }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Deadline</strong></td>
                                    <td>{{ $product->deadline }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Approval</strong></td>
                                    <td>
                                        {{ $product->approval_text }}
                                        <hr/>
                                        {{ $product->approval_message }}
                                    </td>
                                </tr>
                                @if($product->approval_text != "Pending")
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>{{ $product->status }}</td>
                                    </tr>
                                @endif
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
                                        Min Order Qty
                                    </td>
                                    <td>{{ number_format($product->min_order_qty, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Max Order Qty
                                    </td>
                                    <td>{{ number_format($product->max_order_qty, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Has Order</strong>
                                    </td>
                                    <td>{{ number_format($product->has_order, 0) }}
                                        <a href="{{ url('backend/crowdfund/order?payment_status=PAID&cf_product_id=' . $product->id) }}" class="btn btn-xs btn-primary" target="_blank">
                                            <i class="fa fa-search"></i> List
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Exchange Rate
                                    </td>
                                    <td>{{ number_format($product->exchange_rate, 0) }}/RMB</td>
                                </tr>
                                <tr>
                                    <td>
                                        Price Product
                                    </td>
                                    <td>{{ number_format($product->product_item, 0) }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Bm
                                    </td>
                                    <td>
                                        {{ number_format($product->bm_idr, 0) }} ({{ number_format($product->bm_percent, 0) }}%)
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        PPn
                                    </td>
                                    <td>
                                        {{ number_format($product->ppn_idr, 0) }} ({{ number_format($product->ppn_percent, 0) }}%)
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        PPh
                                    </td>
                                    <td>
                                        {{ number_format($product->pph_idr, 0) }} ({{ number_format($product->pph_percent, 0) }}%)
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Local Charge
                                    </td>
                                    <td>
                                        {{ number_format($product->local_charge_idr, 0) }} ({{ number_format($product->local_charge_percent, 0) }}%)
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Other Fee
                                    </td>
                                    <td>
                                        {{ number_format($product->other_fee_item, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Admin Fee
                                        @if(is_null($product->approved_at))
                                            <a data-toggle="modal" data-target="#modal-add-admin-fee" href="#" class="btn btn-xs btn-primary"><i class="fa fa-pencil"></i> Update</a>
                                        @endif
                                    </td>
                                    <td>
                                        {{ number_format($product->admin_fee_item, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Local Avg Price
                                    </td>
                                    <td>
                                        {{ number_format($product->local_avg_price, 0) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Grand Total/item
                                    </td>
                                    <td>
                                        {{ number_format($product->price_idr, 0) }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <hr/>
                            <h4>Discuss</h4>
                            <table class="table table-bordered" id="discuss">
                                <thead>
                                <tr>
                                    <td>
                                        ID
                                    </td>
                                    <td>
                                        Comment
                                    </td>
                                    <td>
                                        User
                                    </td>
                                    <td>#</td>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($discusses as $discuss)
                                    <tr>
                                        <td rowspan="{{ $discuss->childs->count() + 1 }}" title="{{ $discuss->created_at }}" width="10%">
                                            {{ $discuss->id }}
                                        </td>
                                        <td title="{{ $discuss->created_at }}">
                                            {{ $discuss->content }}
                                        </td>
                                        <td>
                                            <a href="{{ url('backend/org-users/detail/' . $discuss->user_id) }}" target="_blank">
                                                {{ $discuss->user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ url('backend/crowdfund/discuss/delete/' . $discuss->id) }}" class="btn btn-xs btn-danger delete">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @if($discuss->childs->count())
                                        @foreach($discuss->childs as $child)
                                            <tr>
                                                <td title="{{ $child->created_at }}">
                                                    {{ $child->content }}
                                                </td>
                                                <td>
                                                    <a href="{{ url('backend/org-users/detail/' . $child->user_id) }}" target="_blank">
                                                        {{ $child->user->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ url('backend/crowdfund/discuss/delete/' . $child->id) }}" class="btn btn-xs btn-danger delete">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right">
                            <!-- Modal -->
                            <div id="modal-add-admin-fee" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" id="modal-refund-tag">
                                                Add admin Fee
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            {{ Form::open(['url' => url('backend/crowdfund/update-admin-fee/' . $product->id),
                                                'class' => 'form',
                                                'autocomplete'  => 'off']) }}
                                                <div class="box-body pad">
                                                    <div class="form-group{{ $errors->has('admin_fee_item') ? ' has-error' : '' }}">
                                                        <label for="admin_fee_item">Total Payment IDR</label>
                                                        <input type="text" name="admin_fee_item" class="form-control number" value="{{ number_format($product->admin_fee_item,0) }}" required>
                                                        @if($errors->has('admin_fee_item'))
                                                            <p class="help-block">{{ $errors->first('admin_fee_item') }}</p>
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

                            <div id="modal-reject-message" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title" id="modal-refund-tag">
                                                Reject Product
                                            </h4>
                                        </div>
                                        <div class="modal-body">
                                            {{ Form::open(['url' => url('backend/crowdfund/approval/reject/' . $product->id),
                                                'class' => 'form',
                                                'autocomplete'  => 'off',
                                                'method'    => 'get']) }}
                                            <div class="box-body pad">
                                                <div class="form-group{{ $errors->has('reject_message') ? ' has-error' : '' }}">
                                                    <label for="reject_message">Reject Message</label>
                                                    <input type="text" name="reject_message" class="form-control" placeholder="Message" value="{{ $product->reject_message }}" required>
                                                    @if($errors->has('reject_message'))
                                                        <p class="help-block">{{ $errors->first('reject_message') }}</p>
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
        </div>
    </div>
@endsection