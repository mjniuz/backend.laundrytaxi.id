@extends('backend')
@section('page_header')
@endsection
@section('main')
    <div class="page-content container-fluid">
        <div class="panel panel-bordered">
            <div class="panel-body">
                {{ Form::open(['url' => url('backend/order/reject-save/' . $order->id), 'autocomplete' => 'off', 'files' => true ]) }}

                <div class="row">
                    <div class="col-lg-12">
                        <div class="box-header">
                            <h4>Order Reject</h4>
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
                                            <option value="rejected" {{ ( $order->status == 'rejected') ? 'selected' : '' }}>Reject</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Message</strong></td>
                                    <td valign="top">
                                        <input type="text" class="form-control" name="message" required/>
                                    </td>
                                </tr>
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