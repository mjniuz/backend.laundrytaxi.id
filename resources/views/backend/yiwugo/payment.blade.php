@extends('backend')
@section('page_header')
@endsection
@section('js')

@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                {{ Form::open(['url' => url('backend/yiwugo/payment-save/' . $order->id), 'autocomplete' => 'off', 'files' => true ]) }}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
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
                                    <td>
                                        Confirm Note
                                    </td>
                                    <td>
                                        {{ $order->confirmation_note }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Confirm File
                                    </td>
                                    <td>
                                        @if($order->confirmation_file)
                                            <a href="{{ $order->confirmation_file }}" class="btn btn-xs btn-default">
                                                <i class="fa fa-external-link"></i>
                                            </a>
                                        @else
                                            No Confirm File
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Payment Status</strong></td>
                                    <td valign="top">
                                        <select class="form-control" name="order_status" required>
                                            <option value="UNPAID" {{ ( $order->payment_status == 'UNPAID') ? 'selected' : '' }}>UNPAID</option>
                                            <option value="PAID" {{ ( $order->payment_status == 'PAID') ? 'selected' : '' }}>PAID</option>
                                            <option value="EXPIRED" {{ ( $order->payment_status == 'EXPIRED') ? 'selected' : '' }}>EXPIRED</option>
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check"></i> Save
                        </button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection