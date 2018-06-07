@extends('backend')
@section('page_header')
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                {{ Form::open(['url' => url('backend/order/update-save/' . $order->id), 'autocomplete' => 'off', 'files' => true ]) }}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <h4>Update Status</h4>
                            <div class="pull-right">
                                <a href="{{ url('backend/order/detail/' . $order->id) }}" class="btn btn-sm btn-primary">
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
                                    <td valign="top"><strong>Status</strong></td>
                                    <td valign="top">
                                        <select class="form-control" name="status" required>
                                            <option value="{{ $status }}"> {{ ucfirst($status) }}</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Created / Pickup Date</strong></td>
                                    <td valign="top">
                                        {{ $order->created_at }} / {{ $order->pickup_at }}
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Package</strong></td>
                                    <td valign="top">
                                        {{ $order->package }}
                                    </td>
                                </tr>
                                @if($status == 'pickup_at')
                                    <tr>
                                        <td valign="top"><strong>Actual Weight Kg</strong></td>
                                        <td valign="top">
                                            <input type="number" class="form-control" name="actual_weight" required/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td valign="top"><strong>Actual Count Pcs</strong></td>
                                        <td valign="top">
                                            <input type="number" class="form-control" name="actual_count" required/>
                                        </td>
                                    </tr>
                                @endif
                                @if($status == 'process_at')
                                    <tr>
                                        <td valign="top"><strong>Delivery Date</strong></td>
                                        <td valign="top">
                                            <input type="text" class="form-control date" name="delivery_date" required/>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-success confirm"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection