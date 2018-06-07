@extends('backend')
@section("page_header")
    <h1>
        Dashboard
        <small>Report this Month {{ date("F Y") }}</small>
    </h1>
@endsection
@section("js")
    <script type="text/javascript">
        $(document).ready(function () {
            $.getJSON($('base').data('url')+ "/api/dashboard/total-seminar", function(data) {
                $("#count-total-seminar").append(data);
            });

            $.getJSON($('base').data('url')+ "/api/dashboard/participant", function(data) {
                $("#count-participant").append(data);
            });

            $.getJSON($('base').data('url')+ "/api/dashboard/total-shipping", function(data) {
                $("#count-total-shipping").append(data);
            });

            $.getJSON($('base').data('url')+ "/api/dashboard/total-page-finance", function(data) {
                $("#count-total-page-finance").append(data);
            });

            $.getJSON($('base').data('url') + "/api/dashboard/unprocessed-supplier", function(data){
                $("#total-unprocessed-supplier").append(data);
            });

            $.getJSON($('base').data('url')+ "/api/dashboard/chat", function (data) {
                $.each(data, function(i, item) {
                  $("#last-message").append("<div class='item' style='padding:0 20px 0 0;'>" +
                      "<div class='col-md-1'>" +
                        "<img src='{{ url('asset-images/profile.png') }}' class='online thumbnail' width='50px' height='50xp'>" +
                      "</div>" +
                      "<div class='col-md-9'>" +
                      "<p class='message' style='margin:0 0 0 18px;'>"+
                      item.message +
                        "<br/><br/>" +
                      "<i style='font-style:italic'>" + item.created_at + "</i>" +
                      "</p>" +
                      "</div>" +
                      "<div class='col-md-2'>" +
                      "<a href='{{ url('backend/shipping/detail') }}/" + item.shipping_id + "#shipping_message' class='btn btn-sm btn-primary'>Read More</a>" +
                      "</div>" +
                      "</div>")
                });
            });
        });    
    </script>
@endsection
@section('main')

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3><span id="count-total-seminar"></span></h3>

                        <p>Total Seminar</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-bag"></i>
                    </div>
                    <a href="{{ url('backend/seminar') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3><span id="count-participant"></span></h3>

                        <p>Total Participant</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                    </div>
                    <a href="{{ url('backend/org-registration-participants') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3><span id="count-total-shipping"></span></h3>

                        <p>Total Shipping</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
                    <a href="{{ url('backend/shipping') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-gray">
                    <div class="inner">
                        <h3><span id="count-total-page-finance"></span></h3>

                        <p>Total Page Finance</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="{{ url('backend/shipping-finance') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            {{-- hardcode for philip --}}
            @if(\Sentinel::check()->id != 40)
                <section class="col-lg-7 connectedSortable">

                    <!-- Chat box -->
                    <div class="box box-success">
                        <div class="box-header">
                          <i class="fa fa-comments-o"></i>

                          <h3 class="box-title">User Message</h3>

                        </div>
                        <div class="box-body chat" id="chat-box" style="padding:0;margin:0;">
                          <!-- chat item -->
                          <div id="last-message"></div>
                          <!-- /.item -->
                        </div>
                    </div>

                    <!-- /.box (chat box) -->

                </section>
            @endif
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <div class="col-lg-5">
                <!-- small box -->
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>
                            <span id="total-unprocessed-supplier"></span>
                        </h3>

                        <p>Un Processed Supplier</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-alert-circled"></i>
                    </div>
                    <a href="{{ url('backend/shipping?status=finance_price_paid&status_where_no=supplier_process') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
@endsection