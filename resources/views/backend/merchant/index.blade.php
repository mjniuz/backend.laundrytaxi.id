@extends('backend')
@section('page_header')
    <h4>Merchant List</h4>
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
                        <div class="box-body">
                            {{ Form::open(['url' => url('backend/merchant'), 'method' => 'get']) }}
                            <div class="row gutter-10">
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Name</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['name']) ? $filters['name'] : "" }}" name="name"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-addon">Phone</span>
                                        <input type="text" class="form-control" value="{{ isset($filters['phone']) ? $filters['phone'] : "" }}" name="phone"/>
                                    </div>
                                </div>
                                <div class="col-md-1 pull-right">
                                    <div class="pull-right input-group">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="col-lg-12">
                        @if(Session::has('status'))
                            <p class="alert alert-{{ Session::get('alert-class', 'info') }}">{{ Session::get('status') }}</p>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Is Active</th>
                                        <th>Balance</th>
                                        <th>Created At</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($merchants as $merchant)
                                        <tr>
                                            <td>{{ $merchant->name }}</td>
                                            <td>{{ $merchant->real_phone }}</td>
                                            <td>{{ (is_null($merchant->suspended_at) ? "Active" : "No") }}</td>
                                            <td>{{ number_format($merchant->balance,0) }}</td>
                                            <td>{{ $merchant->created_at }}</td>
                                            <td>
                                                <a href="{{ url('backend/merchant/detail/' . $merchant->id) }}" class="btn btn-xs btn-primary">
                                                    <i class="fa fa-search"></i> Detail
                                                </a>
                                                <a href="{{ url('backend/merchant/form/' . $merchant->id) }}" class="btn btn-xs btn-info">
                                                    <i class="fa fa-pencil"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center">
                                @if(isset($users))
                                    {!! $users->appends(Input::except('page'))->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection