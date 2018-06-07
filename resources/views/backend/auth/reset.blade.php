@extends('backend.auth.auth')

@section('main')
    <div class="login-box {{ Session::has('error') ? ' has-error' : '' }}">
        <h1>IMPORTIR</h1>
        <h2>Reset Password</h2>
        <form method="post" action="{{ url('backend/reset-password') }}">
            <div class="form-group">
                <label for="email"><i class="glyphicon glyphicon-user"></i></label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
            </div>
            @if(Session::has('alert-class'))
                <div class="alert alert-{{ (Session::get('alert-class') == "success") ? "success" : "error" }}" role="alert">{{ Session::get('status') }}</div>
            @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button class="btn btn-block btn-lg btn-success">Reset</button>
        </form>
</div>
@endsection