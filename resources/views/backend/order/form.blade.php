@extends('backend')
@section('page_header')
    <h4>
        Order Create by Admin
    </h4>
@stop
@section('js')
    <script src="{{ asset("js/backend.order.js?v=1.6") }}"></script>
@stop
@section('breadcrumb')
	<li>
        <i class="fa fa-dashboard"></i> Dashboard
    </li>
@stop
@section('main')
    <div class="page-content container-fluid">
    <div class="panel panel-bordered">
    <div class="panel-body">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">
                        Order Form
                    </h3>
                </div>
                {{ Form::open(['url' => url('backend/order/save'), 'autocomplete' => 'off', 'files' => true ]) }}

                <div class="box-body pad">
                    @if(Session::has('alert-class'))
                        <div class="text-center alert alert-{{ (Session::get('alert-class') == "success") ? "success" : "danger" }}" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                        <label for="user_id">User</label>
                        <select class="form-control" id="user_id_select2" name="user_id">
                            <option value="{{ isset($shipping) ? $shipping->user_id : 0 }}">{{ (!empty(old('user_id'))) ? old('user_id') : '' }}</option>
                        </select>
                        @if($errors->has('user_id'))
                            <p class="help-block">{{ $errors->first('user_id') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('full_name') ? ' has-error' : '' }}">
                        <label for="full_name">Phone</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Full Name" value="{{ !empty(old('full_name')) ? old('full_name') : '' }}" id="full_name" required>
                        @if($errors->has('full_name'))
                            <p class="help-block">{{ $errors->first('full_name') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ !empty(old('phone')) ? old('phone') : '' }}" id="phone" required>
                        @if($errors->has('phone'))
                            <p class="help-block">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('full_address') ? ' has-error' : '' }}">
                        <label for="full_address">Full Address</label>
                        <input type="text" name="full_address" class="form-control" placeholder="Full Address" value="{{ !empty(old('full_address')) ? old('full_address') : '' }}" id="full_address" required>
                        @if($errors->has('full_address'))
                            <p class="help-block">{{ $errors->first('full_address') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('address_note') ? ' has-error' : '' }}">
                        <label for="address_note">Address Note</label>
                        <input type="text" name="address_note" class="form-control" placeholder="Address Note" value="{{ !empty(old('address_note')) ? old('address_note') : '' }}" id="address_note" required>
                        @if($errors->has('address_note'))
                            <p class="help-block">{{ $errors->first('address_note') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('lng') ? ' has-error' : '' }}">
                        <label for="lng">Lng Coordinate</label>
                        <input type="text" name="lng" class="form-control" placeholder="Lng" value="{{ !empty(old('lng')) ? old('lng') : '' }}" id="lng" required>
                        @if($errors->has('lng'))
                            <p class="help-block">{{ $errors->first('lng') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('lat') ? ' has-error' : '' }}">
                        <label for="lat">Lat Coordinate</label>
                        <input type="text" name="lat" class="form-control" placeholder="Lat" value="{{ !empty(old('lat')) ? old('lat') : '' }}" id="lat" required>
                        @if($errors->has('lat'))
                            <p class="help-block">{{ $errors->first('lat') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('package_id') ? ' has-error' : '' }}">
                        <label for="package_id">Package</label>
                        <select class="form-control" name="package_id" required>
                            @foreach($packages as $package)
                                <option value="{{ $package['id'] }}">{{ $package['name'] }} {{ $package['description'] }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('package_id'))
                            <p class="help-block">{{ $errors->first('package_id') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('actual_weight') ? ' has-error' : '' }}">
                        <label for="actual_weight">Actual Weight</label>
                        <input type="text" name="actual_weight" class="form-control" placeholder="Actual Weight" value="{{ !empty(old('actual_weight')) ? old('actual_weight') : '' }}" id="actual_weight" required>
                        @if($errors->has('actual_weight'))
                            <p class="help-block">{{ $errors->first('actual_weight') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('actual_count') ? ' has-error' : '' }}">
                        <label for="actual_count">Actual Count</label>
                        <input type="text" name="actual_count" class="form-control" placeholder="Actual Count" value="{{ !empty(old('actual_count')) ? old('actual_count') : '' }}" id="actual_count" required>
                        @if($errors->has('actual_count'))
                            <p class="help-block">{{ $errors->first('actual_count') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('note') ? ' has-error' : '' }}">
                        <label for="note">Note</label>
                        <input type="text" name="note" class="form-control" placeholder="Note" value="{{ !empty(old('note')) ? old('note') : '' }}" id="note"/>
                        @if($errors->has('note'))
                            <p class="help-block">{{ $errors->first('note') }}</p>
                        @endif
                    </div>
                    <div class="box-footer pull-right">
                        <a href="{{ url('backend/administrator') }}" class="btn btn-default">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success confirm">Save</button>
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection