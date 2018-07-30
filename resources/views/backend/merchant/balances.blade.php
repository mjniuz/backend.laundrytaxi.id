@extends('backend')
@section('page_header')
    <h4>Balance List</h4>
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
                    </div>
                    <div class="col-lg-12">
                        @if(Session::has('status'))
                            <p class="alert alert-{{ Session::get('alert-class', 'info') }}">{{ Session::get('status') }}</p>
                        @endif
                        @if(!empty($merchant))
                            <p>
                                Merchant {{ $merchant->name }} / {{ $merchant->phone }}
                            </p>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Order</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Current</th>
                                        <th>Message</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($balances as $balance)
                                        <tr>
                                            <td>{{ $balance->id }}</td>
                                            <td>
                                                @if(!is_null($balance->order_id))
                                                    <a href="{{ url('backend/order/detail/' . $balance->order_id) }}" class="btn btn-xs btn-primary">
                                                        {{ $balance->order_id }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{ $balance->in_out }}</td>
                                            <td>{{ number_format($balance->amount,0) }}</td>
                                            <td>{{ number_format($balance->balance_total,0) }}</td>
                                            <td>{{ $balance->message }}</td>
                                            <td>
                                                <a target="_blank" href="{{ url('backend/administrator/edit/' . $balance->admin_user_id) }}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-user"></i>
                                                </a>
                                            </td>
                                            <td>{{ $balance->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                @if(isset($balances))
                                    {!! $balances->appends(Input::except('page'))->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection