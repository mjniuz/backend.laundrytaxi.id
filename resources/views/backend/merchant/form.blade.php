@extends('backend')
@section('page_header')
    <h4>
	    Form Merchant
    </h4>
@stop
@section('breadcrumb')
	<li>
        <i class="fa fa-dashboard"></i> Dashboard
    </li>
    <li>
        <i class="fa fa-briefcase"></i> Form Merchant
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
                        Merchant Form
                    </h3>
                </div>
                
                @if(!isset($merchant))
                    {{ Form::open(['url' => url('backend/merchant/save'), 'autocomplete' => 'off', 'files' => true ]) }}
                @else
                    {{ Form::open(['url' => url('backend/merchant/save/' . $merchant->id), 'autocomplete' => 'off', 'files' => true ]) }}
                @endif
                <div class="box-body pad">
                    @if(Session::has('alert-class'))
                        <div class="text-center alert alert-{{ (Session::get('alert-class') == "success") ? "success" : "danger" }}" role="alert">{{ Session::get('status') }}</div>
                    @endif
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="first_name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{ isset($merchant) ? $merchant->name : old('name') }}" required>
                        @if($errors->has('name'))
                            <p class="help-block">{{ $errors->first('name') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        <label for="phone">CS Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Phone" value="{{ isset($merchant) ? $merchant->phone : old('phone') }}" required>
                        @if($errors->has('phone'))
                            <p class="help-block">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('real_phone') ? ' has-error' : '' }}">
                        <label for="real_phone">Merchant Phone</label>
                        <input type="text" name="real_phone" class="form-control" placeholder="Merchant Phone" value="{{ isset($merchant) ? $merchant->real_phone : old('real_phone') }}" required>
                        @if($errors->has('real_phone'))
                            <p class="help-block">{{ $errors->first('real_phone') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email" value="{{ isset($merchant) ? $merchant->email : old('email') }}" required>
                        @if($errors->has('email'))
                            <p class="help-block">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('avatar') ? ' has-error' : '' }}">
                        <label for="avatar">Avatar Link Image</label>
                        <input type="text" name="avatar" class="form-control" placeholder="Avatar" value="{{ isset($merchant) ? $merchant->avatar : old('avatar') }}" required>
                        @if($errors->has('avatar'))
                            <p class="help-block">{{ $errors->first('avatar') }}</p>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                        <label for="address">Address</label>
                        <textarea name="address" class="form-control" required>{{ isset($merchant) ? $merchant->address : old('address') }}</textarea>
                        @if($errors->has('address'))
                            <p class="help-block">{{ $errors->first('address') }}</p>
                        @endif
                    </div>
                <div class="box-footer pull-right">
                    <a href="{{ url('backend/merchant') }}" class="btn btn-default">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">Save</button>
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