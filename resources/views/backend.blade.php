<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>LaundryTaxi | Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset("bower_components/bootstrap/dist/css/bootstrap.min.css") }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset("bower_components/font-awesome/css/font-awesome.min.css") }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset("bower_components/Ionicons/css/ionicons.min.css") }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset("dist/css/AdminLTE.min.css") }}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset("dist/css/skins/_all-skins.min.css") }}">
    <!-- Morris chart -->
    <link rel="stylesheet" href="{{ asset("bower_components/morris.js/morris.css") }}">
    <!-- jvectormap -->
    <link rel="stylesheet" href="{{ asset("bower_components/jvectormap/jquery-jvectormap.css") }}">
    <!-- Date Picker -->
    <link rel="stylesheet" href="{{ asset("bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css") }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset("bower_components/bootstrap-daterangepicker/daterangepicker.css") }}">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{ asset("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/sweetalert/sweetalert2.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />


    <link rel="icon"
          type="image/png"
          href="https://importir.com/images/favicon.ico?v=1.2.3" />
    <link rel="stylesheet" href="{{ asset("bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield("css")
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('asset/admin/css/main.css?v=1.3') }}">
    <base data-url="{{ url('') }}"/>
</head>
<body class="hold-transition skin-yellow sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('backend') }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Importir</b></span>
        </a>
        @include("backend.partials.nav")
    </header>

    @include("backend.partials.sidebar")

    <div class="content-wrapper">
        <section class="content-header">
            @yield("page_header")

            <ol class="breadcrumb">
                <li>
                    <a href="#">
                        <i class="fa fa-dashboard"></i> Home
                    </a>
                </li>
                <li class="active">Dashboard</li>
            </ol>
        </section>
        <br />

        <section class="content">
            @yield("main")
        </section>
    </div>


    @include("backend.partials.footer")
    {{--@include("backend.partials.control-sidebar")--}}
    <div class="control-sidebar-bg"></div>

    <!-- The Modal -->
    <div id="modal-image" class="modal-img">
        <!-- The Close Button -->
        <span id="close-modal-image" class="close">&times;</span>
        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img-place" src="">
        <!-- Modal Caption (Image Text) -->
        <div id="img-caption"></div>
    </div>
</div>

@yield("js_first")
<!-- jQuery 3 -->
<script src="{{ asset("bower_components/jquery/dist/jquery.min.js") }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset("bower_components/jquery-ui/jquery-ui.min.js") }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset("bower_components/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- Morris.js charts -->
<script src="{{ asset("bower_components/raphael/raphael.min.js") }}"></script>
<!-- Sparkline -->
<script src="{{ asset("bower_components/jquery-sparkline/dist/jquery.sparkline.min.js") }}"></script>
<!-- jvectormap -->
<script src="{{ asset("plugins/jvectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
<script src="{{ asset("plugins/jvectormap/jquery-jvectormap-world-mill-en.js") }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset("bower_components/jquery-knob/dist/jquery.knob.min.js") }}"></script>
<!-- daterangepicker -->
<script src="{{ asset("bower_components/moment/min/moment.min.js") }}"></script>
<script src="{{ asset("bower_components/bootstrap-daterangepicker/daterangepicker.js") }}"></script>

<script type="text/javascript" src="{{ asset("bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
<!-- datepicker -->
<script src="{{ asset("bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js") }}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset("plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js") }}"></script>
<!-- Slimscroll -->
<script src="{{ asset("bower_components/jquery-slimscroll/jquery.slimscroll.min.js") }}"></script>
<!-- FastClick -->
<script src="{{ asset("bower_components/fastclick/lib/fastclick.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("dist/js/adminlte.min.js") }}"></script>

<script type="text/javascript" src="{{ asset('plugins/sweetalert/sweetalert2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset("dist/js/demo.js") }}"></script>
<script src="{{ asset("js/backend.main.js?v=1.6") }}"></script>

@if (\Request::is('backend/account/*'))
    <script src="{{ asset("js/backend.account.js?v=1.6") }}"></script>
@endif
@yield("js")

@yield('javascript')
</body>
</html>